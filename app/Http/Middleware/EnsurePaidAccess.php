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
        // Allow access to payment and OTP routes without checking paid access
        if ($request->routeIs('payment*') || $request->is('paypal/*') || $request->routeIs('otp.verify.*')) {
            return $next($request);
        }

        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Auto-create license if user has license_key but no active license record
        if ($user->hasPaid() && !$user->activeLicense()->exists() && $user->license_status === 'active' && $user->license_key) {
            License::firstOrCreate(
                ['user_id' => $user->id, 'license_key' => $user->license_key],
                ['is_active' => true, 'plan_type' => 'pro', 'duration_days' => 3650, 'expires_at' => now()->addYears(10)]
            );
            $user->refresh();
        }

        // Check if user has paid access
        if ($user->hasPaidAccess()) {
            return $next($request);
        }

        // Payment required
        if (!$user->hasPaid()) {
            return redirect()->route('payment')->with('error', 'Payment is required to continue.');
        }

        // License required
        if (!$user->hasActiveLicense()) {
            return redirect()->route('payment')->with('error', 'Active license is required.');
        }

        // OTP verification required
        if ($user->otpRequired() && !$user->otp_verified_at) {
            return redirect()->route('otp.verify.form')->with('error', 'Please verify OTP first.');
        }

        return $next($request);
    }
}
