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

        $allowedRoutes = [
            'payment',
            'billing',
            'payment.success',
            'payment.cancel',
            'otp.verify.form',
            'otp.verify.submit',
            'otp.verify.resend',
            'logout',
        ];

        if ($request->route() && in_array($request->route()->getName(), $allowedRoutes, true)) {
            return $next($request);
        }

        if (! $user->is_paid) {
            return redirect()->route('payment')
                ->with('error', 'Please complete payment to continue.');
        }

        if ($user->is_paid && ! $user->is_verified) {
            return redirect()->route('otp.verify.form')
                ->with('error', 'Please verify OTP to continue.');
        }

        return $next($request);
    }
}
