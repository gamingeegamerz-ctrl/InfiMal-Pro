<?php

namespace App\Http\Controllers;

use App\Jobs\SendOtpMailJob;
use App\Models\License;
use App\Models\Payment;
use App\Models\User;
use App\Services\MonitoringService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    private const PRICE = '299.00';
    private const CURRENCY = 'USD';
    private const PRODUCT = 'InfiMal Pro Lifetime';
    private const PRODUCT = 'InfiMal Pro Monthly';
    private const PRODUCT = 'InfiMal Pro';
    private const OTP_TTL_MINUTES = 15;
    private const OTP_RESEND_COOLDOWN_SECONDS = 60;
    private const OTP_MAX_ATTEMPTS_PER_MINUTE = 5;
    private const OTP_MAX_FAILED_ATTEMPTS = 5;
    private const OTP_LOCK_MINUTES = 15;

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

        abort_unless($approvalUrl, Response::HTTP_UNPROCESSABLE_ENTITY, 'PayPal approval URL is missing.');

        Payment::updateOrCreate(
            ['payment_id' => (string) $payload['id']],
            [
                'user_id' => $user->id,
                'plan' => self::PRODUCT,
                'amount' => (float) self::PRICE,
                'currency' => self::CURRENCY,
                'currency' => 'USD',
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
        $request->validate([
            'token' => ['required', 'string', 'max:64'],
            'token' => ['required', 'string'],
        ]);

        $orderId = (string) $request->string('token');
        $user = $request->user();

        $order = $this->fetchOrder($orderId);
        $this->assertOrderIsValidForUser($order, $user);


        $order = $this->fetchOrder($orderId);
        $this->assertOrderIsValidForUser($order, $user);


        $order = $this->fetchOrder($orderId);
        $this->assertOrderIsValidForUser($order, $user);


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
        abort_unless(data_get($capturePayload, 'status') === 'COMPLETED', Response::HTTP_UNPROCESSABLE_ENTITY, 'Payment capture is not completed.');

        $captureAmount = (string) data_get($capturePayload, 'purchase_units.0.payments.captures.0.amount.value', '');
        $captureCurrency = (string) data_get($capturePayload, 'purchase_units.0.payments.captures.0.amount.currency_code', '');
        abort_unless($this->amountMatches($captureAmount) && $captureCurrency === self::CURRENCY, Response::HTTP_UNPROCESSABLE_ENTITY, 'Captured amount verification failed.');

        $captureId = (string) data_get($capturePayload, 'purchase_units.0.payments.captures.0.id', $orderId);

        $this->finalizeSuccessfulPayment($user, $captureId, $capturePayload);

        return redirect()->route('otp.verify.form')->with('success', 'Payment completed. Verify OTP to activate your account.');

        $captureAmount = (string) data_get($capturePayload, 'purchase_units.0.payments.captures.0.amount.value', '');
        $captureCurrency = (string) data_get($capturePayload, 'purchase_units.0.payments.captures.0.amount.currency_code', '');
        abort_unless($this->amountMatches($captureAmount) && $captureCurrency === self::CURRENCY, Response::HTTP_UNPROCESSABLE_ENTITY, 'Captured amount verification failed.');

        $captureId = (string) data_get($capturePayload, 'purchase_units.0.payments.captures.0.id', $orderId);

        $this->finalizeSuccessfulPayment($user, $captureId, $capturePayload);

        return redirect()->route('otp.verify.form')->with('success', 'Payment completed. Verify OTP to activate your account.');

        $capturePayload = $captureResponse->json();
        abort_unless(data_get($capturePayload, 'status') === 'COMPLETED', Response::HTTP_UNPROCESSABLE_ENTITY, 'Payment capture is not completed.');

        $captureAmount = (string) data_get($capturePayload, 'purchase_units.0.payments.captures.0.amount.value', '');
        $captureCurrency = (string) data_get($capturePayload, 'purchase_units.0.payments.captures.0.amount.currency_code', '');
        abort_unless($this->amountMatches($captureAmount) && $captureCurrency === self::CURRENCY, Response::HTTP_UNPROCESSABLE_ENTITY, 'Captured amount verification failed.');

        $captureId = (string) data_get($capturePayload, 'purchase_units.0.payments.captures.0.id', $orderId);

        $this->finalizeSuccessfulPayment($user, $captureId, $capturePayload);

        return redirect()->route('otp.verify.form')->with('success', 'Payment completed. Verify OTP to activate your account.');

        $captureAmount = (string) data_get($capturePayload, 'purchase_units.0.payments.captures.0.amount.value', '');
        $captureCurrency = (string) data_get($capturePayload, 'purchase_units.0.payments.captures.0.amount.currency_code', '');
        abort_unless($this->amountMatches($captureAmount) && $captureCurrency === self::CURRENCY, Response::HTTP_UNPROCESSABLE_ENTITY, 'Captured amount verification failed.');

        $captureId = (string) data_get($capturePayload, 'purchase_units.0.payments.captures.0.id', $orderId);

        $this->finalizeSuccessfulPayment($user, $captureId, $capturePayload);

        return redirect()->route('otp.verify.form')->with('success', 'Payment completed. Verify OTP to activate your account.');

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
        Log::channel('webhooks')->info('PayPal webhook received.', [
        Log::info('PayPal webhook received.', [
            'transmission_id' => $request->header('PAYPAL-TRANSMISSION-ID'),
            'event_type' => $request->input('event_type'),
        ]);

        if (! $this->verifyWebhookSignature($request)) {
            $context = ['transmission_id' => $request->header('PAYPAL-TRANSMISSION-ID'), 'ip' => $request->ip()];
            Log::channel('security')->warning('PayPal webhook signature validation failed.', $context);
            app(MonitoringService::class)->critical('Webhook signature failure', $context);

            Log::channel('security')->warning('PayPal webhook signature validation failed.', [
                'transmission_id' => $request->header('PAYPAL-TRANSMISSION-ID'),
            ]);

            Log::warning('PayPal webhook signature validation failed.', [
                'transmission_id' => $request->header('PAYPAL-TRANSMISSION-ID'),
            ]);

        if (! $this->isValidWebhook($request)) {
            return response('invalid signature', Response::HTTP_UNAUTHORIZED);
        }

        $eventType = (string) $request->input('event_type');
        if ($eventType !== 'PAYMENT.CAPTURE.COMPLETED') {
            return response('ignored', Response::HTTP_OK);
        }

        $resource = (array) $request->input('resource', []);
        $captureId = (string) data_get($resource, 'id', '');
        $orderId = (string) data_get($resource, 'supplementary_data.related_ids.order_id', '');

        if ($captureId === '' || $orderId === '') {
            return response('invalid payload', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $existingCompleted = Payment::where('payment_id', $captureId)->where('status', 'completed')->exists();
        if ($existingCompleted) {
            Log::channel('webhooks')->info('Duplicate webhook ignored (idempotent).', ['capture_id' => $captureId]);
            return response('duplicate', Response::HTTP_OK);
        }

        $order = $this->fetchOrder($orderId);
        $userId = (int) data_get($order, 'purchase_units.0.custom_id');
        $user = User::find($userId);

        if (! $user) {
            app(MonitoringService::class)->critical('Webhook user resolution failed', ['order_id' => $orderId, 'capture_id' => $captureId]);
            return response('user not found', Response::HTTP_NOT_FOUND);
        }

        $this->assertOrderIsValidForUser($order, $user);

        $this->finalizeSuccessfulPayment($user, $captureId, [
            'webhook' => $request->all(),
            'verified_order' => $order,
        ]);

        Log::channel('payments')->info('PayPal webhook payment finalized.', [
            'user_id' => $user->id,
            'capture_id' => $captureId,
        ]);

        }

        $existingCompleted = Payment::where('payment_id', $captureId)->where('status', 'completed')->exists();
        if ($existingCompleted) {
            Log::channel('webhooks')->info('Duplicate webhook ignored (idempotent).', ['capture_id' => $captureId]);
            return response('duplicate', Response::HTTP_OK);
        }

        $order = $this->fetchOrder($orderId);
        $userId = (int) data_get($order, 'purchase_units.0.custom_id');
        $user = User::find($userId);

        if (! $user) {
            app(MonitoringService::class)->critical('Webhook user resolution failed', ['order_id' => $orderId, 'capture_id' => $captureId]);
            return response('user not found', Response::HTTP_NOT_FOUND);
        }

        $this->assertOrderIsValidForUser($order, $user);

        $this->finalizeSuccessfulPayment($user, $captureId, [
            'webhook' => $request->all(),
            'verified_order' => $order,
        ]);

        Log::channel('payments')->info('PayPal webhook payment finalized.', [
            'user_id' => $user->id,
            'capture_id' => $captureId,
        ]);

        return response('ok', Response::HTTP_OK);
    }

    public function paypalWebhook(Request $request): Response
    {
        return $this->webhook($request);
    }

    public function showOtpForm(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasPaid()) {
            return redirect()->route('payment');
        }

        if ($user->otp_verified_at) {
            return redirect()->route('dashboard');
        }

        if ($this->isOtpExpired($user)) {
            $this->issueOtp($user->fresh(), true);
        }

        return redirect()->route('billing')->with('info', 'Enter the OTP sent to your email to activate access.');
    }

    public function resendOtp(Request $request): RedirectResponse
    {
        $user = $request->user()->fresh();

        abort_unless($user->hasPaid(), Response::HTTP_FORBIDDEN, 'Payment required first.');

        if ($user->otp_locked_until && now()->lt($user->otp_locked_until)) {
            return back()->withErrors(['otp' => 'OTP is temporarily locked. Try again later.']);
        }

        if ($user->otp_last_sent_at && now()->diffInSeconds($user->otp_last_sent_at) < self::OTP_RESEND_COOLDOWN_SECONDS) {
            return back()->withErrors(['otp' => 'Please wait before requesting a new OTP.']);
        }

        $this->issueOtp($user, true);

        return back()->with('success', 'A new OTP has been sent to your email.');
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $user = $request->user()->fresh();
        $attemptKey = 'otp-attempt:'.$user->id.':'.$request->ip();

        if ($user->otp_verified_at) {
            return redirect()->route('dashboard')->with('success', 'Your account is already verified.');
        }

        if ($user->otp_locked_until && now()->lt($user->otp_locked_until)) {
            return back()->withErrors(['otp' => 'Too many failed attempts. Try again later.']);
        }

        if (RateLimiter::tooManyAttempts($attemptKey, self::OTP_MAX_ATTEMPTS_PER_MINUTE)) {
            return back()->withErrors(['otp' => 'Rate limit exceeded. Wait a minute and retry.']);
        }

        RateLimiter::hit($attemptKey, 60);

        if (! $user->otp_code || ! $user->otp_expires_at) {
            return back()->withErrors(['otp' => 'OTP is missing. Please resend OTP.']);
        }

        if ($this->isOtpExpired($user)) {
            return back()->withErrors(['otp' => 'OTP expired. Please request a new OTP.']);
        }

        if (! Hash::check($validated['otp'], $user->otp_code)) {
            $failedAttempts = (int) $user->otp_failed_attempts + 1;
            $user->forceFill([
                'otp_failed_attempts' => $failedAttempts,
                'otp_locked_until' => $failedAttempts >= self::OTP_MAX_FAILED_ATTEMPTS ? now()->addMinutes(self::OTP_LOCK_MINUTES) : null,
            ])->save();

            Log::channel('security')->warning('OTP verification failed', ['user_id' => $user->id, 'ip' => $request->ip(), 'failed_attempts' => $failedAttempts]);

            return back()->withErrors(['otp' => 'Invalid OTP provided.']);
        }

        $user->forceFill([
            'otp_verified_at' => now(),
            'otp_code' => null,
            'otp_expires_at' => null,
            'otp_failed_attempts' => 0,
            'otp_locked_until' => null,
            'onboarding_step' => 'active',
        ])->save();

        $request->session()->put('onboarding_step', 'active');
        RateLimiter::clear($attemptKey);


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

        Log::channel('payments')->info('PayPal webhook payment finalized.', [
            'user_id' => $user->id,
            'capture_id' => $captureId,
        ]);

        return response('ok', Response::HTTP_OK);
    }

    public function paypalWebhook(Request $request): Response
    {
        return $this->webhook($request);
    }

    public function showOtpForm(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasPaid()) {
            return redirect()->route('payment');
        }

        if ($user->otp_verified_at) {
            return redirect()->route('dashboard');
        }

        if ($this->isOtpExpired($user)) {
            $this->issueOtp($user->fresh(), true);
        }

        return redirect()->route('billing')->with('info', 'Enter the OTP sent to your email to activate access.');
    }

    public function resendOtp(Request $request): RedirectResponse
    {
        $user = $request->user()->fresh();

        abort_unless($user->hasPaid(), Response::HTTP_FORBIDDEN, 'Payment required first.');

        if ($user->otp_locked_until && now()->lt($user->otp_locked_until)) {
            return back()->withErrors(['otp' => 'OTP is temporarily locked. Try again later.']);
        }

        if ($user->otp_last_sent_at && now()->diffInSeconds($user->otp_last_sent_at) < self::OTP_RESEND_COOLDOWN_SECONDS) {
            return back()->withErrors(['otp' => 'Please wait before requesting a new OTP.']);
        }

        $this->issueOtp($user, true);

        return back()->with('success', 'A new OTP has been sent to your email.');
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $user = $request->user()->fresh();
        $attemptKey = 'otp-attempt:'.$user->id.':'.$request->ip();

        if ($user->otp_verified_at) {
            return redirect()->route('dashboard')->with('success', 'Your account is already verified.');
        }

        if ($user->otp_locked_until && now()->lt($user->otp_locked_until)) {
            return back()->withErrors(['otp' => 'Too many failed attempts. Try again later.']);
        }

        if (RateLimiter::tooManyAttempts($attemptKey, self::OTP_MAX_ATTEMPTS_PER_MINUTE)) {
            return back()->withErrors(['otp' => 'Rate limit exceeded. Wait a minute and retry.']);
        }

        RateLimiter::hit($attemptKey, 60);

        if (! $user->otp_code || ! $user->otp_expires_at) {
            return back()->withErrors(['otp' => 'OTP is missing. Please resend OTP.']);
        }

        if ($this->isOtpExpired($user)) {
            return back()->withErrors(['otp' => 'OTP expired. Please request a new OTP.']);
        }

        if (! Hash::check($validated['otp'], $user->otp_code)) {
            $failedAttempts = (int) $user->otp_failed_attempts + 1;
            $user->forceFill([
                'otp_failed_attempts' => $failedAttempts,
                'otp_locked_until' => $failedAttempts >= self::OTP_MAX_FAILED_ATTEMPTS ? now()->addMinutes(self::OTP_LOCK_MINUTES) : null,
            ])->save();

            Log::channel('security')->warning('OTP verification failed', ['user_id' => $user->id, 'ip' => $request->ip(), 'failed_attempts' => $failedAttempts]);
    }

    public function showOtpForm(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasPaid()) {
            return redirect()->route('payment');
        }

        if ($user->otp_verified_at) {
            return redirect()->route('dashboard');
        }

        if ($this->isOtpExpired($user)) {
            $this->issueOtp($user->fresh(), true);
        }

        return redirect()->route('billing')->with('info', 'Enter the OTP sent to your email to activate access.');
    }

    public function resendOtp(Request $request): RedirectResponse
    {
        $user = $request->user()->fresh();

        abort_unless($user->hasPaid(), Response::HTTP_FORBIDDEN, 'Payment required first.');

        if ($user->otp_locked_until && now()->lt($user->otp_locked_until)) {
            return back()->withErrors(['otp' => 'OTP is temporarily locked. Try again later.']);
        }

        if ($user->otp_last_sent_at && now()->diffInSeconds($user->otp_last_sent_at) < self::OTP_RESEND_COOLDOWN_SECONDS) {
            return back()->withErrors(['otp' => 'Please wait before requesting a new OTP.']);
        }

        $this->issueOtp($user, true);

        return back()->with('success', 'A new OTP has been sent to your email.');
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $user = $request->user()->fresh();
        $attemptKey = 'otp-attempt:'.$user->id.':'.$request->ip();

        if ($user->otp_verified_at) {
            return redirect()->route('dashboard')->with('success', 'Your account is already verified.');
        }

        if ($user->otp_locked_until && now()->lt($user->otp_locked_until)) {
            return back()->withErrors(['otp' => 'Too many failed attempts. Try again later.']);
        }

        if (RateLimiter::tooManyAttempts($attemptKey, self::OTP_MAX_ATTEMPTS_PER_MINUTE)) {
            return back()->withErrors(['otp' => 'Rate limit exceeded. Wait a minute and retry.']);
        }

        RateLimiter::hit($attemptKey, 60);
            Log::info('Duplicate webhook ignored (idempotent).', ['capture_id' => $captureId]);
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

        Log::info('PayPal webhook payment finalized.', [
            'user_id' => $user->id,
            'capture_id' => $captureId,
        ]);

        return response('ok', Response::HTTP_OK);
    }

    public function paypalWebhook(Request $request): Response
    {
        return $this->webhook($request);
    }

    public function showOtpForm(Request $request): RedirectResponse
    {
        $user = $request->user();
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

        if ($this->isOtpExpired($user)) {
            $this->issueOtp($user->fresh(), true);
        if (! $user->otp_code || ! $user->otp_expires_at || now()->greaterThan($user->otp_expires_at)) {
            $this->issueOtp($user->fresh());
        }

        return redirect()->route('billing')->with('info', 'Enter the OTP sent to your email to activate access.');
    }

    public function resendOtp(Request $request): RedirectResponse
    {
        $user = $request->user()->fresh();

        abort_unless($user->hasPaid(), Response::HTTP_FORBIDDEN, 'Payment required first.');

        if ($user->otp_locked_until && now()->lt($user->otp_locked_until)) {
            return back()->withErrors(['otp' => 'OTP is temporarily locked. Try again later.']);
        }

        if ($user->otp_last_sent_at && now()->diffInSeconds($user->otp_last_sent_at) < self::OTP_RESEND_COOLDOWN_SECONDS) {
            return back()->withErrors(['otp' => 'Please wait before requesting a new OTP.']);
        }

        $this->issueOtp($user, true);

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
        $attemptKey = 'otp-attempt:'.$user->id.':'.$request->ip();

        if ($user->otp_verified_at) {
            return redirect()->route('dashboard')->with('success', 'Your account is already verified.');
        }

        if ($user->otp_locked_until && now()->lt($user->otp_locked_until)) {
            return back()->withErrors(['otp' => 'Too many failed attempts. Try again later.']);
        }

        if (RateLimiter::tooManyAttempts($attemptKey, self::OTP_MAX_ATTEMPTS_PER_MINUTE)) {
            return back()->withErrors(['otp' => 'Rate limit exceeded. Wait a minute and retry.']);
        }

        RateLimiter::hit($attemptKey, 60);

        if (! $user->otp_code || ! $user->otp_expires_at) {
            return back()->withErrors(['otp' => 'OTP is missing. Please resend OTP.']);
        }

        if ($this->isOtpExpired($user)) {
            return back()->withErrors(['otp' => 'OTP expired. Please request a new OTP.']);
        }

        if (! Hash::check($validated['otp'], $user->otp_code)) {
            $failedAttempts = (int) $user->otp_failed_attempts + 1;
            $user->forceFill([
                'otp_failed_attempts' => $failedAttempts,
                'otp_locked_until' => $failedAttempts >= self::OTP_MAX_FAILED_ATTEMPTS ? now()->addMinutes(self::OTP_LOCK_MINUTES) : null,
            ])->save();


        $user = $request->user()->fresh();

        if ($user->otp_verified_at) {
            return redirect()->route('dashboard')->with('success', 'Your account is already verified.');
        }

        if (! $user->otp_code || ! $user->otp_expires_at) {
            return back()->withErrors(['otp' => 'OTP is missing. Please resend OTP.']);
        }

        if ($this->isOtpExpired($user)) {
        if (now()->greaterThan($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'OTP expired. Please request a new OTP.']);
        }

        if (! Hash::check($validated['otp'], $user->otp_code)) {
            $failedAttempts = (int) $user->otp_failed_attempts + 1;
            $user->forceFill([
                'otp_failed_attempts' => $failedAttempts,
                'otp_locked_until' => $failedAttempts >= self::OTP_MAX_FAILED_ATTEMPTS ? now()->addMinutes(self::OTP_LOCK_MINUTES) : null,
            ])->save();

            Log::channel('security')->warning('OTP verification failed', ['user_id' => $user->id, 'ip' => $request->ip(), 'failed_attempts' => $failedAttempts]);

            return back()->withErrors(['otp' => 'Invalid OTP provided.']);
        }

        $user->forceFill([
            'otp_verified_at' => now(),
            'otp_code' => null,
            'otp_expires_at' => null,
            'otp_failed_attempts' => 0,
            'otp_locked_until' => null,
            'onboarding_step' => 'active',
        ])->save();

        $request->session()->put('onboarding_step', 'active');
        RateLimiter::clear($attemptKey);

            return back()->withErrors(['otp' => 'Invalid OTP provided.']);
        }

        $user->forceFill([
            'otp_verified_at' => now(),
            'otp_code' => null,
            'otp_expires_at' => null,
            'otp_failed_attempts' => 0,
            'otp_locked_until' => null,
            'onboarding_step' => 'active',
        ])->save();

        $request->session()->put('onboarding_step', 'active');
        RateLimiter::clear($attemptKey);

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
                    'currency' => self::CURRENCY,
                    'status' => 'completed',
                    'payment_method' => 'paypal',
                    'metadata' => $payload,
                ]
            );

            $durationDays = (int) config('infimal.subscription.duration_days', 30);

            $license = License::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'license_key' => License::generateLicenseKey(),
                    'plan_type' => self::PRODUCT,
                    'price' => (float) self::PRICE,
                    'duration_days' => $durationDays,
                    'status' => 'active',
                    'is_active' => true,
                    'is_lifetime' => false,
                    'expires_at' => now()->addDays($durationDays),
                ]
            );

            $license->forceFill([
                'plan_type' => self::PRODUCT,
                'duration_days' => $durationDays,
                'status' => 'active',
                'is_active' => true,
                'is_lifetime' => false,
                'expires_at' => now()->addDays($durationDays),
                    'duration_days' => 0,
                    'status' => 'active',
                    'is_active' => true,
                    'is_lifetime' => true,
                    'expires_at' => null,
                ]
            );

            $license->forceFill([
                'plan_type' => self::PRODUCT,
                'duration_days' => 0,
                'status' => 'active',
                'is_active' => true,
                'is_lifetime' => true,
                'expires_at' => null,
                ]
            );

            $license->forceFill(['status' => 'active', 'is_active' => true, 'is_lifetime' => true])->save();
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
                'onboarding_step' => 'otp_verification_required',
            ])->save();

            $this->issueOtp($user->fresh(), true);
        });

        session()->put('onboarding_step', 'otp_verification_required');

        Log::channel('payments')->info('Payment finalized', ['user_id' => $user->id, 'payment_id' => $paymentId]);
    }

    private function issueOtp(User $user, bool $force = false): void
    {
        if (! $force && ! $this->isOtpExpired($user) && $user->otp_code) {
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

            $this->issueOtp($user->fresh(), true);
        });

        session()->put('onboarding_step', 'otp_verification_required');

        Log::channel('payments')->info('Payment finalized', ['user_id' => $user->id, 'payment_id' => $paymentId]);

            $this->issueOtp($user->fresh(), true);
        });

        session()->put('onboarding_step', 'otp_verification_required');

        Log::channel('payments')->info('Payment finalized', ['user_id' => $user->id, 'payment_id' => $paymentId]);

            $this->issueOtp($user->fresh(), true);
        });

        session()->put('onboarding_step', 'otp_verification_required');
    }

    private function issueOtp(User $user, bool $force = false): void
    {
        if (! $force && ! $this->isOtpExpired($user) && $user->otp_code) {
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
        Mail::to($user->email)->send(
            (new PaidWelcomeOtpMail($user, $otp))->from('noreply@yourdomain.com', config('app.name', 'InfiMal'))
        );
    }

    private function isOtpExpired(User $user): bool
    {
        return ! $user->otp_expires_at || now()->greaterThan($user->otp_expires_at);
    }

    private function fetchOrder(string $orderId): array
    {
        $orderResponse = Http::withToken($this->paypalToken())
            ->acceptJson()
            ->get($this->paypalBaseUrl()."/v2/checkout/orders/{$orderId}");

        abort_unless($orderResponse->successful(), Response::HTTP_UNPROCESSABLE_ENTITY, 'Unable to verify PayPal order.');

        return (array) $orderResponse->json();
    }

    private function assertOrderIsValidForUser(array $order, User $user): void
    {

        abort_unless($orderResponse->successful(), Response::HTTP_UNPROCESSABLE_ENTITY, 'Unable to verify PayPal order.');

        return (array) $orderResponse->json();
    }

    private function assertOrderIsValidForUser(array $order, User $user): void
    {
        $status = (string) data_get($order, 'status');
        $customId = (string) data_get($order, 'purchase_units.0.custom_id');
        $amount = (string) data_get($order, 'purchase_units.0.amount.value', '');
        $currency = (string) data_get($order, 'purchase_units.0.amount.currency_code', '');

        abort_unless(in_array($status, ['APPROVED', 'COMPLETED'], true), Response::HTTP_UNPROCESSABLE_ENTITY, 'PayPal order status is invalid.');
        abort_unless($customId === (string) $user->id, Response::HTTP_FORBIDDEN, 'Payment ownership mismatch.');
        abort_unless($currency === self::CURRENCY, Response::HTTP_UNPROCESSABLE_ENTITY, 'Currency mismatch.');
        abort_unless($this->amountMatches($amount), Response::HTTP_UNPROCESSABLE_ENTITY, 'Amount mismatch.');
    }

    private function amountMatches(string $amount): bool
    {
        return number_format((float) $amount, 2, '.', '') === number_format((float) self::PRICE, 2, '.', '');

        abort_unless(in_array($status, ['APPROVED', 'COMPLETED'], true), Response::HTTP_UNPROCESSABLE_ENTITY, 'PayPal order status is invalid.');
        abort_unless($customId === (string) $user->id, Response::HTTP_FORBIDDEN, 'Payment ownership mismatch.');
        abort_unless($currency === self::CURRENCY, Response::HTTP_UNPROCESSABLE_ENTITY, 'Currency mismatch.');
        abort_unless($this->amountMatches($amount), Response::HTTP_UNPROCESSABLE_ENTITY, 'Amount mismatch.');
    }

    private function amountMatches(string $amount): bool
    {
        return number_format((float) $amount, 2, '.', '') === number_format((float) self::PRICE, 2, '.', '');

        abort_unless($orderResponse->successful(), Response::HTTP_UNPROCESSABLE_ENTITY, 'Unable to verify PayPal order.');

        return (array) $orderResponse->json();
    }

    private function assertOrderIsValidForUser(array $order, User $user): void
    {
        $status = (string) data_get($order, 'status');
        $customId = (string) data_get($order, 'purchase_units.0.custom_id');
        $amount = (string) data_get($order, 'purchase_units.0.amount.value', '');
        $currency = (string) data_get($order, 'purchase_units.0.amount.currency_code', '');

        abort_unless(in_array($status, ['APPROVED', 'COMPLETED'], true), Response::HTTP_UNPROCESSABLE_ENTITY, 'PayPal order status is invalid.');
        abort_unless($customId === (string) $user->id, Response::HTTP_FORBIDDEN, 'Payment ownership mismatch.');
        abort_unless($currency === self::CURRENCY, Response::HTTP_UNPROCESSABLE_ENTITY, 'Currency mismatch.');
        abort_unless($this->amountMatches($amount), Response::HTTP_UNPROCESSABLE_ENTITY, 'Amount mismatch.');
    }

    private function amountMatches(string $amount): bool
    {
        return number_format((float) $amount, 2, '.', '') === number_format((float) self::PRICE, 2, '.', '');

        abort_unless(in_array($status, ['APPROVED', 'COMPLETED'], true), Response::HTTP_UNPROCESSABLE_ENTITY, 'PayPal order status is invalid.');
        abort_unless($customId === (string) $user->id, Response::HTTP_FORBIDDEN, 'Payment ownership mismatch.');
        abort_unless($currency === self::CURRENCY, Response::HTTP_UNPROCESSABLE_ENTITY, 'Currency mismatch.');
        abort_unless($this->amountMatches($amount), Response::HTTP_UNPROCESSABLE_ENTITY, 'Amount mismatch.');
    }

    private function amountMatches(string $amount): bool
    {
        return number_format((float) $amount, 2, '.', '') === number_format((float) self::PRICE, 2, '.', '');
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
        return config('services.paypal.mode') === 'live' ? 'https://api-m.paypal.com' : 'https://api-m.sandbox.paypal.com';
    }

    private function verifyWebhookSignature(Request $request): bool
    {
        $webhookId = (string) config('services.paypal.webhook_id');
        if ($webhookId === '') {
            return app()->environment('local');
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

        $status = (string) Arr::get($verificationResponse->json(), 'verification_status');

        Log::channel('webhooks')->info('PayPal webhook verification response.', [
        Log::info('PayPal webhook verification response.', [
            'successful' => $verificationResponse->successful(),
            'verification_status' => $status,
            'transmission_id' => $request->header('PAYPAL-TRANSMISSION-ID'),
        ]);

        return $verificationResponse->successful() && $status === 'SUCCESS';

        return $verificationResponse->successful() && $status === 'SUCCESS';
    private function isValidWebhook(Request $request): bool
    {
        $expectedToken = config('services.paypal.webhook_token');

        if (! $expectedToken) {
            return app()->environment('local');
        }

        return hash_equals($expectedToken, (string) $request->header('X-PayPal-Webhook-Token'));
    }
}
