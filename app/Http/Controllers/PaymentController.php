<?php

namespace App\Http\Controllers;

use App\Mail\PaidWelcomeOtpMail;
use App\Models\License;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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

        $licenseKey = $user->license_key ?: 'INFIMAL-' . strtoupper(Str::random(24));

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

        $otp = (string) random_int(100000, 999999);

        $user->update([
            'is_paid' => true,
            'payment_status' => 'paid',
            'paid_at' => now(),
            'license_key' => $licenseKey,
            'license_status' => 'active',
            'transaction_id' => $captureId,
            'otp_code' => bcrypt($otp),
            'otp_expires_at' => now()->addMinutes(15),
            'otp_verified_at' => null,
        ]);

        Mail::to($user->email)->queue(new PaidWelcomeOtpMail($user, $otp));

        return response('OK', 200);
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

    public function resendOtp(Request $request)
    {
        $user = $request->user();

        if (!$user->hasPaid() || !$user->hasActiveLicense()) {
            return redirect()->route('payment')->withErrors(['otp' => 'Payment verification is still pending.']);
        }

        $otp = (string) random_int(100000, 999999);
        $user->update([
            'otp_code' => bcrypt($otp),
            'otp_expires_at' => now()->addMinutes(15),
            'otp_verified_at' => null,
        ]);

        Mail::to($user->email)->queue(new PaidWelcomeOtpMail($user, $otp));

        return back()->with('success', 'A new OTP has been sent to your email.');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);

        $user = $request->user();

        if (!$user->isOtpRequired()) {
            return redirect()->route('dashboard');
        }

        if (!$user->otp_expires_at || now()->greaterThan($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'OTP expired. Please request a new code.']);
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
}
