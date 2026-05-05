<?php

namespace App\Http\Middleware;

use App\Services\UsageLimitService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnforceUsageLimits
{
    protected $limit;

    public function __construct(UsageLimitService $limit)
    {
        $this->limit = $limit;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        if ($user->is_admin) {
            return $next($request);
        }

        // Example limit checks – adjust as per your service methods
        if ($request->routeIs('campaigns.store') && method_exists($this->limit, 'dailyCampaignLimitReached') && $this->limit->dailyCampaignLimitReached($user)) {
            return back()->withErrors(['limit' => 'Daily campaign limit reached.']);
        }

        if (($request->routeIs('campaigns.send') || $request->is('api/emails/send')) && method_exists($this->limit, 'dailyEmailLimitReached') && $this->limit->dailyEmailLimitReached($user)) {
            return back()->withErrors(['limit' => 'Daily email limit reached.']);
        }

        if ($request->routeIs(['subscribers.store', 'subscribers.import']) && method_exists($this->limit, 'subscriberLimitReached') && $this->limit->subscriberLimitReached($user)) {
            return back()->withErrors(['limit' => 'Subscriber limit reached.']);
        }

        return $next($request);
    }
}
