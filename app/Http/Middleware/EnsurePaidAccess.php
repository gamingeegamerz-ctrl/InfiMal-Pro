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
        if ($request->routeIs('payment*') || $request->is('paypal/*') || $request->routeIs('otp.verify.*')) {
            return $next($request);
        }

        $user = $request->user();
        if (!$user) {
            return redirect()->route('login');
        }

        if (!$user->hasPaid()) {
            return redirect()->route('payment')->with('error', 'Payment is required to continue.');
        }

        // Legacy safeguard: keep paid users from being blocked by stale license rows.
        if (!$user->activeLicense()->exists() && $user->license_status === 'active' && !empty($user->license_key)) {
            License::updateOrCreate(
                ['user_id' => $user->id, 'license_key' => $user->license_key],
                ['is_active' => true, 'plan_type' => 'pro', 'duration_days' => 3650, 'expires_at' => now()->addYears(10)]
            );
            $user->refresh();
        }

        if (!$user->activeLicense()->exists()) {
            return redirect()->route('payment')->with('error', 'Active license is required.');
        }

        // Legacy safeguard: grandfather existing paid users that predate OTP flow.
        if (is_null($user->otp_verified_at) && is_null($user->otp_code) && is_null($user->otp_expires_at)) {
            $user->forceFill(['otp_verified_at' => now()])->save();
            $user->refresh();
        }

        if (!$user->otp_verified_at) {
            return redirect()->route('otp.verify.form')->with('error', 'Please verify OTP first.');
        }

        return $next($request);
    }
}
