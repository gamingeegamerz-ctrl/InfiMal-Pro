<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        $adminEmails = [
            'kanishghongade@gmail.com',
            'admin@infimal.site',
            'infimal.site@gmail.com',
            'gamingeegamerz@gmail.com',
        ];

        if (auth()->check() && in_array(auth()->user()->email, $adminEmails)) {
            return $next($request);
        }

        abort(403, 'Admin access only.');
    }
}
