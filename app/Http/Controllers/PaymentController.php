<?php

namespace App\Http\Controllers;

use App\Mail\PaidWelcomeOtpMail;
use App\Models\License;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    private const PRICE = '299.00';
    private const PRODUCT = 'InfiMal Pro';
    private const OTP_TTL_MINUTES = 15;

    public function createOrder(Request $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();

        if ($user->hasPaidAccess()) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Account is already active.'], Response::HTTP_CONFLICT)
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
                        'currency_code' => 'USD',
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

        abort_unless($approvalUrl, Response::HTTP_UNPROCESSABLE_ENTITY, 'PayPal approval URL is missing.');

        Payment::updateOrCreate(
            ['payment_id' => $payload['id']],
            [
                'user_id' => $user->id,
                'plan' => self::PRODUCT,
                'amount' => (float) self::PRICE,
                'currency' => 'USD',
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
        $request->validate([
            'token' => ['required', 'string'],
        ]);

        $orderId = (string) $request->string('token');
        $user = $request->user();

        $orderResponse = Http::withToken($this->paypalToken())
            ->acceptJson()
            ->get($this->paypalBaseUrl()."/v2/checkout/orders/{$orderId}");

        abort_unless($orderResponse->successful(), Response::HTTP_UNPROCESSABLE_ENTITY, 'Unable to verify PayPal order.');

        $order = $orderResponse->json();
        abort_unless((string) data_get($order, 'purchase_units.0.custom_id') === (string) $user->id, Response::HTTP_FORBIDDEN, 'Payment ownership mismatch.');

        $captureResponse = Http::withToken($this->paypalToken())
            ->acceptJson()
            ->post($this->paypalBaseUrl()."/v2/checkout/orders/{$orderId}/capture");

        abort_unless($captureResponse->successful(), Response::HTTP_UNPROCESSABLE_ENTITY, 'Unable to capture payment.');

        $capturePayload = $captureResponse->json();
        $captureId = (string) data_get($capturePayload, 'purchase_units.0.payments.captures.0.id', $orderId);

        $this->finalizeSuccessfulPayment($user, $captureId, $capturePayload);

        return redirect()
            ->route('otp.verify.form')
            ->with('success', 'Payment completed. Verify OTP to activate your account.');
    }

    public function cancel(): RedirectResponse
    {
        return redirect()->route('billing')->with('error', 'Payment cancelled. Complete checkout to continue.');
    }

    public function webhook(Request $request): Response
    {
        if (! $this->isValidWebhook($request)) {
            return response('invalid signature', Response::HTTP_UNAUTHORIZED);
        }

        $eventType = (string) $request->input('event_type');
        if (! in_array($eventType, ['CHECKOUT.ORDER.APPROVED', 'PAYMENT.CAPTURE.COMPLETED'], true)) {
            return response('ignored', Response::HTTP_OK);
        }

        $resource = (array) $request->input('resource', []);
        $userId = (int) (data_get($resource, 'custom_id') ?: data_get($resource, 'purchase_units.0.custom_id'));
        $orderId = (string) (data_get($resource, 'supplementary_data.related_ids.order_id') ?: data_get($resource, 'id'));

        $user = User::find($userId);
        if ($user && $orderId !== '') {
            $this->finalizeSuccessfulPayment($user, $orderId, $request->all());
        }

        return response('ok', Response::HTTP_OK);
    }

    public function paypalWebhook(Request $request): Response
    {
        return $this->webhook($request);
    }

    public function showOtpForm(): RedirectResponse
    {
        $user = request()->user();

        if (! $user->hasPaid()) {
            return redirect()->route('payment');
        }

        if ($user->otp_verified_at) {
            return redirect()->route('dashboard');
        }

        if (! $user->otp_code || ! $user->otp_expires_at || now()->greaterThan($user->otp_expires_at)) {
            $this->issueOtp($user->fresh());
        }

        return redirect()->route('billing')->with('info', 'Enter the OTP sent to your email to activate access.');
    }

    public function resendOtp(Request $request): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user->hasPaid(), Response::HTTP_FORBIDDEN, 'Payment required first.');

        $this->issueOtp($user, true);

        return back()->with('success', 'A new OTP has been sent to your email.');
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $user = $request->user()->fresh();

        if ($user->otp_verified_at) {
            return redirect()->route('dashboard')->with('success', 'Your account is already verified.');
        }

        if (! $user->otp_code || ! $user->otp_expires_at) {
            return back()->withErrors(['otp' => 'OTP is missing. Please resend OTP.']);
        }

        if (now()->greaterThan($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'OTP expired. Please request a new OTP.']);
        }

        if (! Hash::check($validated['otp'], $user->otp_code)) {
            return back()->withErrors(['otp' => 'Invalid OTP provided.']);
        }

        $user->forceFill([
            'otp_verified_at' => now(),
            'otp_code' => null,
            'otp_expires_at' => null,
        ])->save();

        return redirect()->route('dashboard')->with('success', 'OTP verified. Welcome to InfiMal.');
    }

    private function finalizeSuccessfulPayment(User $user, string $paymentId, array $payload): void
    {
        DB::transaction(function () use ($user, $paymentId, $payload): void {
            Payment::updateOrCreate(
                ['payment_id' => $paymentId],
                [
                    'user_id' => $user->id,
                    'plan' => self::PRODUCT,
                    'amount' => (float) self::PRICE,
                    'currency' => 'USD',
                    'status' => 'completed',
                    'payment_method' => 'paypal',
                    'metadata' => $payload,
                ]
            );

            $license = License::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'license_key' => License::generateLicenseKey(),
                    'plan_type' => self::PRODUCT,
                    'price' => (float) self::PRICE,
                    'duration_days' => 0,
                    'status' => 'active',
                    'is_active' => true,
                    'is_lifetime' => true,
                ]
            );

            $license->forceFill([
                'status' => 'active',
                'is_active' => true,
                'is_lifetime' => true,
            ])->save();

            $user->forceFill([
                'is_paid' => true,
                'payment_status' => 'paid',
                'plan_name' => self::PRODUCT,
                'paid_at' => now(),
                'payment_date' => now(),
                'payment_amount' => (float) self::PRICE,
                'transaction_id' => $paymentId,
                'license_key' => $license->license_key,
                'license_status' => 'active',
                'otp_verified_at' => null,
            ])->save();

            $this->issueOtp($user->fresh(), true);
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
        ])->save();

        Mail::to($user->email)
            ->send((new PaidWelcomeOtpMail($user, $otp))->from('noreply@yourdomain.com', config('app.name', 'InfiMal')));
    }

    private function paypalToken(): string
    {
        $response = Http::asForm()
            ->withBasicAuth((string) config('services.paypal.client_id'), (string) config('services.paypal.secret'))
            ->post($this->paypalBaseUrl().'/v1/oauth2/token', ['grant_type' => 'client_credentials']);

        abort_unless($response->successful(), Response::HTTP_UNPROCESSABLE_ENTITY, 'PayPal authentication failed.');

        return (string) $response->json('access_token');
    }

    private function paypalBaseUrl(): string
    {
        return config('services.paypal.mode') === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
    }

    private function isValidWebhook(Request $request): bool
    {
        $expectedToken = config('services.paypal.webhook_token');

        if (! $expectedToken) {
            return app()->environment('local');
        }

        return hash_equals($expectedToken, (string) $request->header('X-PayPal-Webhook-Token'));
    }
}
