<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\EmailLog;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function index(): View
    {
        return view('analytics.index', $this->buildPayload());
    }

    public function campaigns(): View
    {
        return view('analytics.index', $this->buildPayload());
    }

    public function subscribers(): View
    {
        return view('analytics.index', $this->buildPayload());
    }

    public function reports(): View
    {
        return view('analytics.index', $this->buildPayload());
    }

    public function export()
    {
        return response()->json($this->buildPayload());
    }

    private function buildPayload(): array
    {
        $userId = Auth::id();
        $base = EmailLog::where('user_id', $userId);
        $sent = (clone $base)->count();
        $opens = (clone $base)->where('opened', true)->count();
        $clicks = (clone $base)->where('clicked', true)->count();
        $bounces = (clone $base)->where('status', 'bounced')->count();

        $daily = collect(range(6, 0))->reverse()->map(function ($daysAgo) use ($userId) {
            $day = now()->subDays($daysAgo)->toDateString();

            return [
                'label' => now()->subDays($daysAgo)->format('M d'),
                'sent' => EmailLog::where('user_id', $userId)->whereDate('created_at', $day)->count(),
                'opens' => EmailLog::where('user_id', $userId)->where('opened', true)->whereDate('created_at', $day)->count(),
                'clicks' => EmailLog::where('user_id', $userId)->where('clicked', true)->whereDate('created_at', $day)->count(),
                'bounces' => EmailLog::where('user_id', $userId)->where('status', 'bounced')->whereDate('created_at', $day)->count(),
            ];
        })->values();

        return [
            'overview' => [
                'total_campaigns' => Campaign::where('user_id', $userId)->count(),
                'total_subscribers' => Subscriber::where('user_id', $userId)->count(),
                'emails_sent' => $sent,
                'open_rate' => $sent > 0 ? round(($opens / $sent) * 100, 2) : 0,
                'click_rate' => $sent > 0 ? round(($clicks / $sent) * 100, 2) : 0,
                'bounce_rate' => $sent > 0 ? round(($bounces / $sent) * 100, 2) : 0,
            ],
            'recent_activity' => (clone $base)->latest()->limit(25)->get(),
            'campaigns' => Campaign::where('user_id', $userId)->latest()->limit(20)->get(),
            'dailySeries' => $daily,
        ];
    }
}
