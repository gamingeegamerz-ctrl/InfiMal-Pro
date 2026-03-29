<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class CheckTrustScore
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = auth()->id();
        
        $trust = DB::table('user_trust')
            ->where('user_id', $userId)
            ->first();
            
        // Check if trust score is above minimum
        if ($trust && $trust->trust_score < 50) {
            return redirect()->route('dashboard')
                ->with('warning', 'Your trust score is too low. Please improve your email sending practices.');
        }
        
        // Check if user is frozen
        if ($trust && $trust->is_frozen) {
            return redirect()->route('dashboard')
                ->with('error', 'Your account is temporarily frozen. Please contact support.');
        }
        
        return $next($request);
    }
}
