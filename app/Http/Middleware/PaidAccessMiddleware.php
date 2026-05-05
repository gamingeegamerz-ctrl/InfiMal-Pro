<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PaidAccessMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        // Guest ? login
        if (!$user) {
            return redirect()->route('login');
        }

        // Admin ? sab allowed
        if ($user->is_admin) {
            return $next($request);
        }

        // Routes that are ALWAYS allowed (even if user hasn't paid/verified)
        $allowedRoutes = [
            'billing',          // payment page
            'payment.success',  // PayPal success
            'payment.cancel',   // PayPal cancel
            'otp.verify.form',  // OTP form
            'otp.verify.submit',
            'otp.verify.resend',
        ];

        $currentRoute = $request->route()->getName();

        // If current route is in allowed list ? just proceed
        if (in_array($currentRoute, $allowedRoutes)) {
            return $next($request);
        }

        // For all other routes: check if user has FULL paid access
        if (!$user->hasPaidAccess()) {
            // User hasn't paid or OTP not verified ? billing page
            return redirect()->route('billing')
                ->with('error', 'Please complete payment and OTP verification to access this feature.');
        }

        return $next($request);
    }
}