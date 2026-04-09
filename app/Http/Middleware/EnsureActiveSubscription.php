<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $license = $user->activeLicense()->first();

        if (! $license || ! $license->expires_at || $license->expires_at->isPast()) {
            $user->forceFill([
                'payment_status' => 'expired',
                'is_paid' => false,
                'onboarding_step' => 'payment_required',
            ])->save();

            $request->session()->put('onboarding_step', 'payment_required');

            return redirect()->route('billing')->with('error', 'Your subscription has expired. Renew to continue.');
        }

        return $next($request);
    }
}
