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

        if (!$user) {
            return redirect()->route('login');
        }

        // ?? ADMIN BYPASS – Email list aur is_admin flag dono check karo
        $adminEmails = [
            'admin@infimal.site',
            'contact@infimal.site',
            'sainikhilsaini143@gmail.com',
            'khileshrathod1729@gmail.com',
            'kanishghongade@gmail.com',
            'gamingeegamerz@gmail.com'
        ];

        if (in_array($user->email, $adminEmails) || ($user->is_admin ?? false)) {
            return $next($request);
        }

        // Allowed routes without payment/verification
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

        // Check payment
        if (!$user->is_paid) {
            return redirect()->route('payment')
                ->with('error', 'Please complete payment to continue.');
        }

        // Check verification
        if (!$user->is_verified) {
            return redirect()->route('otp.verify.form')
                ->with('error', 'Please verify OTP to continue.');
        }

        return $next($request);
    }
}