<?php

namespace App\Http\Controllers;

use App\Mail\PaidWelcomeOtpMail;
use App\Models\License;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    public function paypalWebhook(Request $request)
    {
        $eventType = (string) data_get($request->all(), 'event_type');
        if ($eventType !== 'PAYMENT.CAPTURE.COMPLETED') {
            return response('Ignored', 200);
        }

        $captureId = (string) data_get($request->all(), 'resource.id');
        $orderId = (string) data_get($request->all(), 'resource.supplementary_data.related_ids.order_id');

        if ($captureId === '' || $orderId === '') {
            return response('Invalid payload', 422);
        }

        $accessToken = app(PayPalController::class)->token();

        $order = Http::withToken($accessToken)
            ->get("https://api-m.sandbox.paypal.com/v2/checkout/orders/{$orderId}")
            ->throw()
            ->json();

        $amount = (float) data_get($order, 'purchase_units.0.payments.captures.0.amount.value', 0);
        $currency = (string) data_get($order, 'purchase_units.0.payments.captures.0.amount.currency_code', 'USD');
        $userId = (int) data_get($order, 'purchase_units.0.custom_id', 0);

        if ($amount < 299 || $currency !== 'USD' || $userId <= 0) {
            return response('Verification failed', 422);
        }

        $user = User::find($userId);
        if (!$user) {
            return response('User not found', 404);
        }

        Payment::updateOrCreate(
            ['payment_id' => $captureId],
            [
                'user_id' => $user->id,
                'plan' => 'InfiMal Pro',
                'amount' => $amount,
                'currency' => $currency,
                'status' => 'completed',
                'payment_method' => 'paypal',
                'metadata' => ['order_id' => $orderId],
            ]
        );

        $licenseKey = 'INFIMAL-' . strtoupper(Str::random(24));

        License::updateOrCreate(
            ['user_id' => $user->id, 'is_active' => true],
            [
                'license_key' => $licenseKey,
                'plan_type' => 'pro',
                'duration_days' => 3650,
                'expires_at' => now()->addYears(10),
                'is_active' => true,
            ]
        );

        $otp = $this->issueOtp($user);

        $user->update([
            'is_paid' => true,
            'payment_status' => 'paid',
            'paid_at' => now(),
            'license_key' => $licenseKey,
            'license_status' => 'active',
            'transaction_id' => $captureId,
            'otp_code' => $user->otp_code,
            'otp_expires_at' => $user->otp_expires_at,
            'otp_verified_at' => null,
    private const PRICE = '299.00';
    private const PRODUCT = 'InfiMal Pro';

    public function createOrder(Request $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();
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
                    'return_url' => route('payment.success'),
                    'cancel_url' => route('payment.cancel'),
                    'user_action' => 'PAY_NOW',
                ],
            ]);

        abort_unless($response->successful(), Response::HTTP_UNPROCESSABLE_ENTITY, $response->json('message', 'Unable to create PayPal order.'));

        $payload = $response->json();
        $approvalUrl = collect($payload['links'] ?? [])->firstWhere('rel', 'approve')['href'] ?? null;

        abort_if(! $approvalUrl, Response::HTTP_UNPROCESSABLE_ENTITY, 'Missing PayPal approval link.');

        Payment::updateOrCreate(
            ['payment_id' => $payload['id']],
            [
                'user_id' => $user->id,
                'plan' => self::PRODUCT,
                'amount' => 299.00,
                'currency' => 'USD',
                'status' => 'pending',
                'payment_method' => 'paypal',
                'metadata' => $payload,
            ]
        );

        if ($request->expectsJson()) {
            return response()->json([
                'order_id' => $payload['id'],
                'approval_url' => $approvalUrl,
            ]);
        }

        return redirect()->away($approvalUrl);
    }

    public function success(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required', 'string'],
            'PayerID' => ['nullable', 'string'],
        ]);

        $orderId = (string) $request->string('token');
        $accessToken = $this->paypalToken();

        $orderResponse = Http::withToken($accessToken)
            ->acceptJson()
            ->get($this->paypalBaseUrl()."/v2/checkout/orders/{$orderId}");

        abort_unless($orderResponse->successful(), Response::HTTP_UNPROCESSABLE_ENTITY, 'Unable to verify PayPal order.');

        $order = $orderResponse->json();
        $customId = (string) data_get($order, 'purchase_units.0.custom_id');
        $user = $request->user();

        abort_unless($customId !== '' && $customId === (string) $user->id, Response::HTTP_FORBIDDEN, 'This payment does not belong to the current account.');
        abort_unless(data_get($order, 'status') === 'APPROVED', Response::HTTP_UNPROCESSABLE_ENTITY, 'PayPal order is not approved.');

        $captureResponse = Http::withToken($accessToken)
            ->acceptJson()
            ->post($this->paypalBaseUrl()."/v2/checkout/orders/{$orderId}/capture");

        abort_unless($captureResponse->successful(), Response::HTTP_UNPROCESSABLE_ENTITY, 'Unable to capture approved PayPal payment.');

        $this->finalizeSuccessfulPayment($user, $orderId, $captureResponse->json());

        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Payment verified successfully. Your lifetime license is active.');
    }

    public function cancel(): RedirectResponse
    {
        return redirect()->route('billing')->with('error', 'PayPal checkout was cancelled. Your account remains unpaid.');
    }

    public function webhook(Request $request): Response
    {
        if (! $this->verifyWebhookSignature($request)) {
            return response('invalid signature', Response::HTTP_UNAUTHORIZED);
        }

        $eventType = (string) $request->input('event_type');
        if (! in_array($eventType, ['CHECKOUT.ORDER.APPROVED', 'PAYMENT.CAPTURE.COMPLETED'], true)) {
            return response('ignored', Response::HTTP_OK);
        }

        $resource = (array) $request->input('resource', []);
        $orderId = (string) ($resource['supplementary_data']['related_ids']['order_id'] ?? $resource['id'] ?? '');
        $customId = (string) (data_get($resource, 'custom_id') ?: data_get($resource, 'purchase_units.0.custom_id'));
        $user = User::find($customId);

        if ($user && $orderId !== '') {
            $this->finalizeSuccessfulPayment($user, $orderId, $request->all());
        }

        return response('ok', Response::HTTP_OK);
    }

    private function finalizeSuccessfulPayment(User $user, string $orderId, array $payload): void
    {
        DB::transaction(function () use ($user, $orderId, $payload): void {
            Payment::updateOrCreate(
                ['payment_id' => $orderId],
                [
                    'user_id' => $user->id,
                    'plan' => self::PRODUCT,
                    'amount' => 299.00,
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
                    'price' => 299.00,
                    'duration_days' => 0,
                    'status' => 'active',
                    'is_active' => true,
                    'is_lifetime' => true,
                    'features' => ['campaigns', 'subscribers', 'smtp', 'analytics', 'messages'],
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
                'payment_amount' => 299.00,
                'transaction_id' => $orderId,
                'license_key' => $license->license_key,
                'license_status' => 'active',
            ])->save();
        });
    }

    private function verifyWebhookSignature(Request $request): bool
    {
        $webhookId = config('services.paypal.webhook_id');
        if (! $webhookId) {
            return app()->environment('local');
        }

        $response = Http::withToken($this->paypalToken())
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

        return $response->successful() && Arr::get($response->json(), 'verification_status') === 'SUCCESS';
    }

    private function paypalToken(): string
    {
        $response = Http::asForm()
            ->withBasicAuth(config('services.paypal.client_id'), config('services.paypal.secret'))
            ->post($this->paypalBaseUrl().'/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);

        abort_unless($response->successful(), Response::HTTP_UNPROCESSABLE_ENTITY, 'PayPal authentication failed.');

        return (string) $response->json('access_token');
    }

    private function paypalBaseUrl(): string
    {
        return config('services.paypal.mode') === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
    public function createOrder(Request $request): JsonResponse
    {
        $user = $request->user();
        abort_if($user->hasPaid() && $user->hasActiveLicense(), Response::HTTP_CONFLICT, 'Your account is already active.');

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
                    'cancel_url' => route('billing'),
                ],
            ]);

        abort_unless($response->successful(), Response::HTTP_UNPROCESSABLE_ENTITY, $response->json('message', 'Unable to create PayPal order.'));

        Payment::updateOrCreate(
            ['payment_id' => $response->json('id')],
            [
                'user_id' => $user->id,
                'plan' => self::PRODUCT,
                'amount' => 299.00,
                'currency' => 'USD',
                'status' => 'pending',
                'payment_method' => 'paypal',
                'metadata' => $response->json(),
            ]
        );

        return response()->json($response->json());
    }

    public function captureOrder(Request $request, string $orderId): JsonResponse
    {
        $user = $request->user();
        $order = $this->fetchOrder($orderId);

        abort_unless(
            data_get($order, 'status') === 'APPROVED' && data_get($order, 'purchase_units.0.custom_id') === (string) $user->id,
            Response::HTTP_FORBIDDEN,
            'Order approval verification failed.'
        );

        $response = Http::withToken($this->paypalToken())
            ->acceptJson()
            ->post($this->paypalBaseUrl()."/v2/checkout/orders/{$orderId}/capture");

        abort_unless($response->successful(), Response::HTTP_UNPROCESSABLE_ENTITY, $response->json('message', 'Unable to capture PayPal order.'));

        $captureData = $response->json();
        $captureId = data_get($captureData, 'purchase_units.0.payments.captures.0.id', $orderId);

        $this->finalizeSuccessfulPayment($user, $orderId, $captureId, $captureData);

        return response()->json([
            'success' => true,
            'redirect' => route('dashboard'),
        ]);
    }

    public function webhook(Request $request): Response
    {
        if (! $this->verifyWebhookSignature($request)) {
            return response('invalid signature', 422);
        }

        $eventType = (string) $request->input('event_type');
        if (! in_array($eventType, ['CHECKOUT.ORDER.APPROVED', 'PAYMENT.CAPTURE.COMPLETED'], true)) {
            return response('ignored', 200);
        }

        $resource = (array) $request->input('resource', []);
        $orderId = data_get($resource, 'supplementary_data.related_ids.order_id') ?? Arr::get($resource, 'id');
        $captureId = data_get($resource, 'id', $orderId);
        $customId = data_get($resource, 'purchase_units.0.custom_id') ?? data_get($resource, 'custom_id');
        $user = User::find($customId);

        if ($user && $orderId) {
            $this->finalizeSuccessfulPayment($user, (string) $orderId, (string) $captureId, $request->all());
    // ✅ COMBINED (codex + main)
    public function startCheckout(Request $request): RedirectResponse
    {
        return $this->createOrder($request);
    }

    public function createOrder(Request $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();

        abort_if($user->hasPaid() && $user->hasActiveLicense(), 403, 'Already active');

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
                    'user_action' => 'PAY_NOW',
                    'return_url' => route('payment.success'),
                    'cancel_url' => route('payment.cancel'),
                ],
            ]);

        abort_unless($response->successful(), 422, 'PayPal order failed');

        $payload = $response->json();
        $approvalUrl = collect($payload['links'] ?? [])
            ->firstWhere('rel', 'approve')['href'] ?? null;

        abort_unless($approvalUrl, 422, 'Approval URL missing');

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
            ? response()->json(['approval_url' => $approvalUrl])
            : redirect()->away($approvalUrl);
    }

        Mail::to($user->email)->queue(new PaidWelcomeOtpMail($user, $otp));

        return response('OK', 200);
    public function success(Request $request): RedirectResponse
    {
        $user = $request->user();
        $orderId = (string) ($request->query('token') ?: '');

        abort_if($orderId === '', 422, 'Missing order');

        $capture = $this->capturePaypalOrder($orderId);

        $this->finalizeSuccessfulPayment($user, $orderId, $capture);

        return redirect()->route('otp.verify.form')
            ->with('success', 'Payment successful. Verify OTP.');
    }

    public function cancel(): RedirectResponse
    {
        return redirect()->route('billing')
            ->with('error', 'Payment cancelled.');
    }

    // ✅ WEBHOOK (MERGED BOTH SYSTEMS)
    public function webhook(Request $request): Response
    {
        if (! $this->verifyWebhookSignature($request)) {
            return response('invalid', 422);
        }

        $eventType = $request->input('event_type');
        $resource = $request->input('resource', []);

        $orderId = data_get($resource, 'id')
            ?? data_get($resource, 'supplementary_data.related_ids.order_id');

        $userId = data_get($resource, 'custom_id')
            ?? data_get($resource, 'purchase_units.0.custom_id');

        if ($orderId && $userId && ($user = User::find($userId))) {
            $this->finalizeSuccessfulPayment($user, $orderId, $request->all());
        }

        return response('ok', 200);
    }

    public function success(): RedirectResponse
    {
        return redirect()->route(Auth::user()?->hasPaid() && Auth::user()?->hasActiveLicense() ? 'dashboard' : 'billing');
    }

    private function finalizeSuccessfulPayment(User $user, string $orderId, string $captureId, array $payload): void
    {
        DB::transaction(function () use ($user, $orderId, $captureId, $payload): void {
    // ✅ CORE PAYMENT FINALIZER (UNCHANGED CORE LOGIC)
    private function finalizeSuccessfulPayment(User $user, string $orderId, array $payload): void
    {
        DB::transaction(function () use ($user, $orderId, $payload) {

            Payment::updateOrCreate(
                ['payment_id' => $orderId],
                [
                    'user_id' => $user->id,
                    'plan' => self::PRODUCT,
                    'amount' => 299.00,
                    'amount' => (float) self::PRICE,
                    'currency' => 'USD',
                    'status' => 'completed',
                    'payment_method' => 'paypal',
                    'metadata' => $payload,
                ]
            );

            $license = License::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'license_key' => $user->license_key ?: License::generateLicenseKey(),
                    'plan_type' => self::PRODUCT,
                    'price' => 299.00,
                    'duration_days' => 0,
                    'status' => 'active',
                    'is_active' => true,
                    'is_lifetime' => true,
                    'expires_at' => null,
                    'features' => ['campaigns', 'subscribers', 'smtp', 'analytics', 'messages'],
                ]
            );

            $user->forceFill([
                'is_paid' => true,
                'payment_status' => 'paid',
                'plan_name' => self::PRODUCT,
                'paid_at' => now(),
                'payment_date' => now(),
                'payment_amount' => 299.00,
                'transaction_id' => $captureId,
                'license_key' => $license->license_key,
                'license_status' => 'active',
            ])->save();
        });
    }

    private function fetchOrder(string $orderId): array
    {
        $response = Http::withToken($this->paypalToken())
            ->acceptJson()
            ->get($this->paypalBaseUrl()."/v2/checkout/orders/{$orderId}");

        abort_unless($response->successful(), Response::HTTP_UNPROCESSABLE_ENTITY, 'Unable to verify the PayPal order.');

        return $response->json();
    }

    private function verifyWebhookSignature(Request $request): bool
    {
        $webhookId = config('services.paypal.webhook_id');
        if (! $webhookId) {
            return false;
        }

        $response = Http::withToken($this->paypalToken())
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

        return $response->successful() && $response->json('verification_status') === 'SUCCESS';
    }

    private function paypalToken(): string
    {
        $response = Http::asForm()
            ->withBasicAuth(config('services.paypal.client_id'), config('services.paypal.secret'))
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



    public function success()
    {
        return redirect()->route('otp.verify.form')->with('success', 'Payment captured. Waiting for secure webhook verification.');
    }

    public function processPaddleCheckout(Request $request)
    {
        return response()->json(['message' => 'Paddle flow is disabled. Use PayPal checkout.'], 422);
    }

    public function showOtpForm()
    {
        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);

        $user = $request->user();

        if (!$user->otp_expires_at || now()->greaterThan($user->otp_expires_at)) {
            $otp = $this->issueOtp($user);
            Mail::to($user->email)->queue(new PaidWelcomeOtpMail($user, $otp));

            return back()->withErrors(['otp' => 'OTP expired. A new OTP has been sent to your email.']);
        }

        if (!password_verify((string) $request->input('otp'), (string) $user->otp_code)) {
            return back()->withErrors(['otp' => 'Invalid OTP code.']);
        }

        $user->update([
            'otp_verified_at' => now(),
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        return redirect()->route('dashboard')->with('success', 'OTP verified successfully.');
    }

    private function issueOtp(User $user): string
    {
        $otp = (string) random_int(100000, 999999);

        $user->forceFill([
            'otp_code' => bcrypt($otp),
            'otp_expires_at' => now()->addMinutes(15),
        ])->save();

        return $otp;
            $license = License::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'license_key' => $user->license_key ?: License::generateLicenseKey(),
                    'plan_type' => self::PRODUCT,
                    'price' => (float) self::PRICE,
                    'status' => 'active',
                    'is_active' => true,
                    'is_lifetime' => true,
                ]
            );

            $user->forceFill([
                'is_paid' => true,
                'payment_status' => 'paid',
                'plan_name' => self::PRODUCT,
                'paid_at' => now(),
                'transaction_id' => $orderId,
                'license_key' => $license->license_key,
                'license_status' => 'active',
            ])->save();

            $this->issueOtp($user);
        });
    }

    private function paypalToken(): string
    {
        $response = Http::asForm()
            ->withBasicAuth(
                config('services.paypal.client_id'),
                config('services.paypal.secret')
            )
            ->post($this->paypalBaseUrl().'/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);

        abort_unless($response->successful(), 422, 'PayPal auth failed');

        return $response->json('access_token');
    }

    private function paypalBaseUrl(): string
    {
        return config('services.paypal.mode') === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
    }

    private function verifyWebhookSignature(Request $request): bool
    {
        $webhookId = config('services.paypal.webhook_id');

        if (!$webhookId) return false;

        $response = Http::withToken($this->paypalToken())
            ->post($this->paypalBaseUrl().'/v1/notifications/verify-webhook-signature', [
                'webhook_id' => $webhookId,
                'webhook_event' => $request->all(),
            ]);

        return $response->successful();
    }

    // ✅ OTP SYSTEM (MAIN FEATURE KEPT)
    private function issueOtp(User $user): void
    {
        $otp = random_int(100000, 999999);

        $user->update([
            'otp_code' => bcrypt($otp),
            'otp_expires_at' => now()->addMinutes(15),
            'otp_verified_at' => null,
        ]);

        Mail::to($user->email)->queue(new PaidWelcomeOtpMail($user, $otp));
    }

    public function showOtpForm(): View
    {
        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate(['otp' => 'required|digits:6']);

        $user = $request->user();

        if (!password_verify($request->otp, $user->otp_code)) {
            return back()->withErrors(['otp' => 'Invalid OTP']);
        }

        $user->update(['otp_verified_at' => now()]);

        return redirect()->route('dashboard');
    }

    public function resendOtp(Request $request): RedirectResponse
    {
        $this->issueOtp($request->user());

        return back()->with('success', 'OTP resent');
    }
}
