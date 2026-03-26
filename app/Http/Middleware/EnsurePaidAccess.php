<?php

namespace App\Http\Middleware;

use App\Models\License;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePaidAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->routeIs('payment') || $request->is('paypal/*') || $request->routeIs('otp.verify.*')) {
            return $next($request);
        }

        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->hasPaid() && !$user->activeLicense()->exists() && $user->license_status === 'active' && $user->license_key) {
            License::firstOrCreate(
                ['user_id' => $user->id, 'license_key' => $user->license_key],
                ['is_active' => true, 'plan_type' => 'pro', 'duration_days' => 3650, 'expires_at' => now()->addYears(10)]
            );
            $user->refresh();
        }

        if (!$user->hasPaid()) {
            return redirect()->route('payment')->with('error', 'Payment is required to continue.');
        }

        if (!$user->hasActiveLicense()) {
            return redirect()->route('payment')->with('error', 'Active license is required.');
        }

        if ($user->isOtpRequired() && !$user->otp_verified_at) {
            return redirect()->route('otp.verify.form')->with('error', 'Please verify OTP first.');
        }

        return $next($request);
    }
}
