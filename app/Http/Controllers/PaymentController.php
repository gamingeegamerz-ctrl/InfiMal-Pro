<?php

namespace App\Http\Controllers;

use App\Jobs\SendOtpMailJob;
use App\Models\Invoice;
use App\Models\License;
use App\Models\OtpCode;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    private const PRICE = '299.00';
    private const CURRENCY = 'USD';
    private const PRODUCT = 'InfiMal Pro Lifetime';
    private const OTP_TTL_MINUTES = 15;
    private const OTP_RESEND_COOLDOWN_SECONDS = 60;

    public function showPaymentPage(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->is_admin || ($user->is_paid && $user->is_verified)) {
            return redirect()->route('dashboard')->with('success', 'You already have active access.');
        }

        if ($user->is_paid && ! $user->is_verified) {
            return redirect()->route('otp.verify.form')->with('info', 'Payment done. Please verify OTP.');
        }

        return view('billing.index', [
            'user' => $user,
            'planName' => 'InfiMal Pro',
            'price' => (float) self::PRICE,
            'paypalClientId' => config('services.paypal.client_id'),
            'paypalMode' => config('services.paypal.mode', 'sandbox'),
            'features' => [
                'Unlimited email sending through your own SMTP accounts',
                'Campaign management and audience segmentation',
                'Open, click, and bounce analytics',
                'Per-user SMTP isolation and secure credential storage',
                'Lifetime access after verified one-time payment',
            ],
        ]);
    }

    public function createOrder(Request $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();

        if ($user->is_paid && $user->is_verified) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Account already active.'], Response::HTTP_CONFLICT)
                : redirect()->route('dashboard');
        }

        $response = Http::withToken($this->paypalToken())
            ->acceptJson()
            ->post($this->paypalBaseUrl().'/v2/checkout/orders', [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'reference_id' => 'user-'.$user->id,
                    'custom_id' => (string) $user->id,
                    'description' => self::PRODUCT,
                    'amount' => [
                        'currency_code' => self::CURRENCY,
                        'value' => self::PRICE,
                    ],
                ]],
                'application_context' => [
                    'brand_name' => config('app.name', 'InfiMal'),
                    'shipping_preference' => 'NO_SHIPPING',
                    'user_action' => 'PAY_NOW',
                    'return_url' => route('payment.success'),
                    'cancel_url' => route('payment.cancel'),
                ],
            ]);

        abort_unless($response->successful(), Response::HTTP_UNPROCESSABLE_ENTITY, 'Unable to create PayPal order.');

        $payload = $response->json();
        $approvalUrl = collect($payload['links'] ?? [])->firstWhere('rel', 'approve')['href'] ?? null;
        abort_unless($approvalUrl, Response::HTTP_UNPROCESSABLE_ENTITY, 'PayPal approval URL missing.');

        Payment::updateOrCreate(
            ['payment_id' => (string) $payload['id']],
            [
                'user_id' => $user->id,
                'plan' => self::PRODUCT,
                'amount' => (float) self::PRICE,
                'currency' => self::CURRENCY,
                'status' => 'pending',
                'payment_method' => 'paypal',
                'metadata' => $payload,
            ]
        );

        return $request->expectsJson()
            ? response()->json(['approval_url' => $approvalUrl, 'order_id' => $payload['id']])
            : redirect()->away($approvalUrl);
    }

    public function success(Request $request): RedirectResponse
    {
        $request->validate(['token' => ['required', 'string', 'max:128']]);

        $orderId = (string) $request->string('token');
        $user = $request->user();

        $order = $this->fetchOrder($orderId);
        $this->assertOrderIsValidForUser($order, $user);

        $captureResponse = Http::withToken($this->paypalToken())
            ->acceptJson()
            ->post($this->paypalBaseUrl()."/v2/checkout/orders/{$orderId}/capture");

        abort_unless($captureResponse->successful(), Response::HTTP_UNPROCESSABLE_ENTITY, 'Unable to capture payment.');

        $capturePayload = $captureResponse->json();
        abort_unless(data_get($capturePayload, 'status') === 'COMPLETED', Response::HTTP_UNPROCESSABLE_ENTITY, 'Payment not completed.');

        $captureAmount = (string) data_get($capturePayload, 'purchase_units.0.payments.captures.0.amount.value', '');
        $captureCurrency = (string) data_get($capturePayload, 'purchase_units.0.payments.captures.0.amount.currency_code', '');
        abort_unless($this->amountMatches($captureAmount) && $captureCurrency === self::CURRENCY, Response::HTTP_UNPROCESSABLE_ENTITY, 'Captured amount check failed.');

        $captureId = (string) data_get($capturePayload, 'purchase_units.0.payments.captures.0.id', $orderId);
        $this->finalizeSuccessfulPayment($user, $captureId, $capturePayload);

        return redirect()->route('otp.verify.form')->with('success', 'Payment successful! OTP sent to your email.');
    }

    public function cancel(): RedirectResponse
    {
        return redirect()->route('payment')->with('error', 'Payment cancelled. Complete payment to continue.');
    }

    public function webhook(Request $request): Response
    {
        if (! $this->verifyWebhookSignature($request)) {
            Log::channel('security')->warning('PayPal webhook signature failed.', [
                'transmission_id' => $request->header('PAYPAL-TRANSMISSION-ID'),
                'ip' => $request->ip(),
            ]);

            return response('invalid signature', Response::HTTP_UNAUTHORIZED);
        }

        if ((string) $request->input('event_type') !== 'PAYMENT.CAPTURE.COMPLETED') {
            return response('ignored', Response::HTTP_OK);
        }

        $captureId = (string) data_get($request->input('resource', []), 'id', '');
        $orderId = (string) data_get($request->input('resource', []), 'supplementary_data.related_ids.order_id', '');

        if ($captureId === '' || $orderId === '') {
            return response('invalid payload', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $order = $this->fetchOrder($orderId);
        $userId = (int) data_get($order, 'purchase_units.0.custom_id');
        $user = User::find($userId);

        if (! $user) {
            return response('user not found', Response::HTTP_NOT_FOUND);
        }

        $this->assertOrderIsValidForUser($order, $user);
        $this->finalizeSuccessfulPayment($user, $captureId, [
            'webhook' => $request->all(),
            'verified_order' => $order,
        ]);

        return response('ok', Response::HTTP_OK);
    }

    public function paypalWebhook(Request $request): Response
    {
        return $this->webhook($request);
    }

    public function showOtpForm(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user->is_paid) {
            return redirect()->route('payment')->with('error', 'Complete payment first.');
        }

        if ($user->is_verified) {
            return redirect()->route('billing');
        }

        $otp = $user->otpCodes()
            ->whereNull('consumed_at')
            ->latest('id')
            ->first();

        return view('billing.verify-otp', [
            'user' => $user,
            'otpExpiresAt' => $otp?->expires_at,
        ]);
    }

    public function resendOtp(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user->is_paid) {
            return redirect()->route('payment')->with('error', 'Complete payment first.');
        }

        $lastOtp = $user->otpCodes()->latest('id')->first();
        if ($lastOtp && now()->diffInSeconds($lastOtp->created_at) < self::OTP_RESEND_COOLDOWN_SECONDS) {
            return redirect()->route('otp.verify.form')->with('error', 'Please wait 60 seconds before requesting another OTP.');
        }

        $this->issueOtp($user, force: true);

        return redirect()->route('otp.verify.form')->with('success', 'A new OTP has been sent to your email.');
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $user = $request->user();

        if (! $user->is_paid) {
            return redirect()->route('payment')->with('error', 'Complete payment first.');
        }

        $otpRecord = $user->otpCodes()
            ->whereNull('consumed_at')
            ->latest('id')
            ->first();

        if (! $otpRecord || now()->greaterThan($otpRecord->expires_at)) {
            return redirect()->route('otp.verify.form')->with('error', 'OTP expired. Please resend OTP.');
        }

        if (! Hash::check((string) $request->string('otp'), $otpRecord->otp_code)) {
            return redirect()->route('otp.verify.form')->with('error', 'Invalid OTP.');
        }

        DB::transaction(function () use ($user, $otpRecord): void {
            $otpRecord->update(['consumed_at' => now()]);

            $user->forceFill([
                'is_verified' => true,
                'otp_verified_at' => now(),
                'onboarding_step' => 'active',
            ])->save();

            License::firstOrCreate(
                ['user_id' => $user->id, 'status' => 'active'],
                [
                    'license_key' => License::generateLicenseKey(),
                    'plan_type' => 'Premium',
                    'price' => (float) self::PRICE,
                    'duration_days' => 36500,
                    'is_active' => true,
                    'is_lifetime' => true,
                    'expires_at' => null,
                ]
            );
        });

        return redirect()->route('billing')->with('success', 'OTP verified! Billing details are now available.');
    }

    private function finalizeSuccessfulPayment(User $user, string $paymentId, array $metadata = []): void
    {
        DB::transaction(function () use ($user, $paymentId, $metadata): void {
            Payment::updateOrCreate(
                ['payment_id' => $paymentId],
                [
                    'user_id' => $user->id,
                    'plan' => self::PRODUCT,
                    'amount' => (float) self::PRICE,
                    'currency' => self::CURRENCY,
                    'status' => 'completed',
                    'payment_method' => 'paypal',
                    'metadata' => $metadata,
                ]
            );

            Invoice::updateOrCreate(
                ['transaction_id' => $paymentId],
                [
                    'user_id' => $user->id,
                    'invoice_id' => 'INV-'.strtoupper(uniqid()),
                    'amount' => (float) self::PRICE,
                    'currency' => self::CURRENCY,
                    'status' => 'paid',
                    'billing_details' => [
                        'plan' => self::PRODUCT,
                    ],
                    'payment_method' => 'paypal',
                    'paid_at' => now(),
                ]
            );

            $user->forceFill([
                'payment_id' => $paymentId,
                'transaction_id' => $paymentId,
                'payment_status' => 'paid',
                'is_paid' => true,
                'is_verified' => false,
                'paid_at' => now(),
                'payment_date' => now(),
                'payment_amount' => (float) self::PRICE,
                'onboarding_step' => 'otp_verification_required',
            ])->save();

            $this->issueOtp($user, force: true);
        });
    }

    private function issueOtp(User $user, bool $force = false): void
    {
        $current = $user->otpCodes()->whereNull('consumed_at')->latest('id')->first();

        if (! $force && $current && now()->lt($current->expires_at)) {
            return;
        }

        $otp = (string) random_int(100000, 999999);

        OtpCode::create([
            'user_id' => $user->id,
            'otp_code' => Hash::make($otp),
            'expires_at' => now()->addMinutes(self::OTP_TTL_MINUTES),
        ]);

        SendOtpMailJob::dispatch($user->id, $otp)->onQueue('emails');
    }

    private function fetchOrder(string $orderId): array
    {
        $response = Http::withToken($this->paypalToken())
            ->acceptJson()
            ->get($this->paypalBaseUrl()."/v2/checkout/orders/{$orderId}");

        abort_unless($response->successful(), Response::HTTP_UNPROCESSABLE_ENTITY, 'Unable to verify PayPal order.');

        return (array) $response->json();
    }

    private function assertOrderIsValidForUser(array $order, User $user): void
    {
        abort_unless((string) data_get($order, 'purchase_units.0.custom_id') === (string) $user->id, Response::HTTP_FORBIDDEN, 'Payment ownership mismatch.');

        $amount = (string) data_get($order, 'purchase_units.0.amount.value', '');
        $currency = (string) data_get($order, 'purchase_units.0.amount.currency_code', '');

        abort_unless($this->amountMatches($amount) && $currency === self::CURRENCY, Response::HTTP_UNPROCESSABLE_ENTITY, 'Order amount mismatch.');
    }

    private function amountMatches(string $value): bool
    {
        return (float) $value === (float) self::PRICE;
    }

    private function paypalToken(): string
    {
        $response = Http::asForm()
            ->withBasicAuth((string) config('services.paypal.client_id'), (string) config('services.paypal.secret'))
            ->post($this->paypalBaseUrl().'/v1/oauth2/token', ['grant_type' => 'client_credentials']);

        abort_unless($response->successful(), Response::HTTP_UNPROCESSABLE_ENTITY, 'Unable to authenticate with PayPal.');

        return (string) $response->json('access_token');
    }

    private function paypalBaseUrl(): string
    {
        return config('services.paypal.mode') === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
    }

    private function verifyWebhookSignature(Request $request): bool
    {
        $webhookId = (string) config('services.paypal.webhook_id');

        if ($webhookId === '') {
            return false;
        }

        $verificationResponse = Http::withToken($this->paypalToken())
            ->acceptJson()
            ->post($this->paypalBaseUrl().'/v1/notifications/verify-webhook-signature', [
                'auth_algo' => $request->header('PAYPAL-AUTH-ALGO'),
                'cert_url' => $request->header('PAYPAL-CERT-URL'),
                'transmission_id' => $request->header('PAYPAL-TRANSMISSION-ID'),
                'transmission_sig' => $request->header('PAYPAL-TRANSMISSION-SIG'),
                'transmission_time' => $request->header('PAYPAL-TRANSMISSION-TIME'),
                'webhook_id' => $webhookId,
                'webhook_event' => $request->all(),
            ]);

        return $verificationResponse->successful()
            && $verificationResponse->json('verification_status') === 'SUCCESS';
    }
}
