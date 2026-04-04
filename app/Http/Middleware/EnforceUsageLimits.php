<?php

namespace App\Http\Middleware;

use App\Services\UsageLimitService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnforceUsageLimits
{
    public function __construct(private readonly UsageLimitService $limits)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user) {
            return $next($request);
        }

        if ($request->routeIs(['campaigns.store', 'campaigns.send']) && $this->limits->campaignLimitExceeded($user)) {
            return back()->withErrors(['limit' => 'Daily campaign creation limit reached.']);
        }

        if ($request->routeIs(['subscribers.store', 'subscribers.import']) && $this->limits->subscriberLimitExceeded($user)) {
            return back()->withErrors(['limit' => 'Subscriber limit reached for your account.']);
        }

        if (($request->routeIs('campaigns.send') || $request->is('api/emails/send')) && $this->limits->emailLimitExceeded($user)) {
            return response('Daily email limit reached.', Response::HTTP_TOO_MANY_REQUESTS);
        }

        return $next($request);
    }
}
