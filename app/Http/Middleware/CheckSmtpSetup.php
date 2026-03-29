<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class CheckSmtpSetup
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = auth()->id();
        
        // Check if user has configured SMTP
        $smtpCount = DB::table('smtp_configs')
            ->where('user_id', $userId)
            ->where('is_active', true)
            ->count();
            
        if ($smtpCount === 0) {
            return redirect()->route('smtp.index')
                ->with('error', 'Please configure your SMTP settings first.');
        }
        
        return $next($request);
    }
}
