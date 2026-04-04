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

        if (! $user->hasPaid()) {
            return redirect()->route('payment')->with('error', 'Payment is required to access this area.');
        }

        if (! $user->otp_verified_at) {
            return redirect()->route('otp.verify.form')->with('error', 'Verify OTP before accessing the app.');
        }

        return $next($request);
    }
}
