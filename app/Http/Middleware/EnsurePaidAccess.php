<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePaidAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->is_admin) {
            return $next($request);
        }

        if (! $user->is_paid || ! $user->is_verified) {
            return redirect()->route('payment')
                ->with('error', 'Please complete payment and OTP verification to continue.');
        $allowedRoutes = [
            'billing',
            'payment.success',
            'payment.cancel',
            'otp.verify.form',
            'otp.verify.submit',
            'otp.verify.resend',
        ];

        if ($request->route() && in_array($request->route()->getName(), $allowedRoutes, true)) {
            return $next($request);
        }

        if (! $user->hasPaidAccess()) {
            return redirect()->route('billing')
                ->with('error', 'Complete payment & OTP verification.');
        }

        return $next($request);
    }
}
