<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPaidUser
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $user = $request->user();

        if (! $user->hasPaid() || ! $user->hasActiveLicense()) {
            return redirect()->route('billing')->with('error', 'Payment is required before you can access InfiMal Pro.');
        }

        return $next($request);
    }
}
