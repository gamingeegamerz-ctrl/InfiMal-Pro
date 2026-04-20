<?php

namespace App\Http\Controllers;

use App\Jobs\SendOtpMailJob;
use App\Models\License;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    private const PRICE = '299.00';
    private const CURRENCY = 'USD';
    private const PRODUCT = 'InfiMal Pro Lifetime';
    private const OTP_TTL_MINUTES = 15;
    private const OTP_RESEND_COOLDOWN_SECONDS = 60;
    private const OTP_MAX_FAILED_ATTEMPTS = 5;
    private const OTP_LOCK_MINUTES = 15;

    public function createOrder(Request $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();

        if ($user->hasPaid() && $user->otp_verified_at) {
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

        $user->forceFill(['onboarding_step' => 'payment_required'])->save();
        $request->session()->put('onboarding_step', 'payment_required');

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

        return redirect()->route('otp.verify.form')->with('success', 'Payment successful. Enter OTP sent to your email.');
    }

    public function cancel(): RedirectResponse
    {
        return redirect()->route('billing')->with('error', 'Payment cancelled. Complete payment to continue.');
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

        $eventType = (string) $request->input('event_type');
        if ($eventType !== 'PAYMENT.CAPTURE.COMPLETED') {
            return response('ignored', Response::HTTP_OK);
        }

        $captureId = (string) data_get($request->input('resource', []), 'id', '');
        $orderId = (string) data_get($request->input('resource', []), 'supplementary_data.related_ids.order_id', '');

        if ($captureId === '' || $orderId === '') {
            return response('invalid payload', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (Payment::where('payment_id', $captureId)->where('status', 'completed')->exists()) {
            return response('duplicate', Response::HTTP_OK);
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

    public function showOtpForm(Request $request)
    {
        $user = $request->user();

        if (! $user->hasPaid()) {
            return redirect()->route('payment')->with('error', 'Complete payment first.');
        }

        if ($user->otp_verified_at) {
            return redirect()->route('dashboard');
        }

        return response()->view('billing.verify-otp', [
            'user' => $user,
            'otpExpiresAt' => $user->otp_expires_at,
        ]);
    }

    public function resendOtp(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasPaid()) {
            return redirect()->route('payment')->with('error', 'Complete payment first.');
        }

        if ($this->isOtpResendCoolingDown($user)) {
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

        if (! $user->hasPaid()) {
            return redirect()->route('payment')->with('error', 'Complete payment first.');
        }

        if ($user->otp_locked_until && now()->lt($user->otp_locked_until)) {
            return redirect()->route('otp.verify.form')->with('error', 'Too many failed attempts. Try again later.');
        }

        if (! $user->otp_code || $this->isOtpExpired($user)) {
            return redirect()->route('otp.verify.form')->with('error', 'OTP expired. Please resend OTP.');
        }

        if (! Hash::check((string) $request->string('otp'), (string) $user->otp_code)) {
            $failed = (int) $user->otp_failed_attempts + 1;
            $updates = ['otp_failed_attempts' => $failed];

            if ($failed >= self::OTP_MAX_FAILED_ATTEMPTS) {
                $updates['otp_locked_until'] = now()->addMinutes(self::OTP_LOCK_MINUTES);
            }

            $user->forceFill($updates)->save();

            return redirect()->route('otp.verify.form')->with('error', 'Invalid OTP.');
        }

        $user->forceFill([
            'otp_verified_at' => now(),
            'otp_code' => null,
            'otp_expires_at' => null,
            'otp_failed_attempts' => 0,
            'otp_locked_until' => null,
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

        $request->session()->put('onboarding_step', 'active');

        return redirect()->route('billing')->with('success', 'OTP verified! Here is your invoice.');
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
            $user->forceFill([
                'payment_status' => 'paid',
                'is_paid' => true,
                'paid_at' => now(),
                'payment_date' => now(),
                'payment_amount' => (float) self::PRICE,
                'transaction_id' => $paymentId,
                'onboarding_step' => 'otp_verification_required',
            ])->save();

            $this->issueOtp($user, force: true);
        });
    }

    private function issueOtp(User $user, bool $force = false): void
    {
        if (! $force && $user->otp_code && $user->otp_expires_at && now()->lt($user->otp_expires_at)) {
            return;
        }

        $otp = (string) random_int(100000, 999999);

        $user->forceFill([
            'otp_code' => Hash::make($otp),
            'otp_expires_at' => now()->addMinutes(self::OTP_TTL_MINUTES),
            'otp_verified_at' => null,
            'otp_failed_attempts' => 0,
            'otp_locked_until' => null,
            'otp_last_sent_at' => now(),
        ])->save();

        SendOtpMailJob::dispatch($user->id, $otp)->onQueue('emails');
    }

    private function isOtpExpired(User $user): bool
    {
        return ! $user->otp_expires_at || now()->greaterThan($user->otp_expires_at);
    }

    private function isOtpResendCoolingDown(User $user): bool
    {
        return $user->otp_last_sent_at && now()->diffInSeconds($user->otp_last_sent_at) < self::OTP_RESEND_COOLDOWN_SECONDS;
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
