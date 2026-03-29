<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class Paid
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user has made payment
        $hasPayment = DB::table('payments')
            ->where('user_id', auth()->id())
            ->where('status', 'completed')
            ->exists();
            
        if (!$hasPayment) {
            return redirect()->route('payment')
                ->with('error', 'Payment required to access this feature.');
        }
        
        return $next($request);
    }
}
