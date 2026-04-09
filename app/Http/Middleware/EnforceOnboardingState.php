<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnforceOnboardingState
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        $state = $user->access_state;
        $sessionStep = (string) $request->session()->get('onboarding_step', '');
        $canonicalStep = $this->stepForState($state);

        if ($sessionStep !== $canonicalStep) {
            $request->session()->put('onboarding_step', $canonicalStep);
        }

        if ($this->isAllowedForState($request, $state)) {
            return $next($request);
        }

        return match ($state) {
            'REGISTERED_NOT_PAID' => redirect()->route('payment')->with('error', 'Complete payment to continue.'),
            'PAID_NOT_VERIFIED' => redirect()->route('otp.verify.form')->with('error', 'Verify OTP to continue.'),
            default => $next($request),
        };
    }

    private function isAllowedForState(Request $request, string $state): bool
    {
        $alwaysAllowed = [
            'logout',
            'verification.send',
            'payment.webhook.paypal',
            'billing.webhook.paypal',
        ];

        if ($request->routeIs($alwaysAllowed)) {
            return true;
        }

        if ($state === 'REGISTERED_NOT_PAID') {
            return $request->routeIs([
                'billing',
                'payment',
                'billing.checkout',
                'payment.success',
                'payment.cancel',
                'google.complete.*',
            ]);
        }

        if ($state === 'PAID_NOT_VERIFIED') {
            return $request->routeIs([
                'otp.verify.*',
                'billing',
                'payment',
                'payment.cancel',
                'google.complete.*',
            ]);
        }

        return true;
    }

    private function stepForState(string $state): string
    {
        return match ($state) {
            'REGISTERED_NOT_PAID' => 'payment_required',
            'PAID_NOT_VERIFIED' => 'otp_verification_required',
            default => 'active',
        };
    }
}
