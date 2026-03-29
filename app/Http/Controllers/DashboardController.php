<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\EmailLog;
use App\Models\Message;
use App\Models\SMTPAccount;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        if (! $user->hasPaid()) {
            abort(403, 'Access denied. Payment required.');
        }

        $campaigns = Campaign::where('user_id', $user->id);
        $subscribers = Subscriber::where('user_id', $user->id);
        $logs = EmailLog::where('user_id', $user->id);

        $sent = (clone $logs)->count();
        $opens = (clone $logs)->where('opened', true)->count();
        $clicks = (clone $logs)->where('clicked', true)->count();
        $bounces = (clone $logs)->where('status', 'bounced')->count();

        // MAIN VERSION FEATURE (KEPT, NOT REMOVED)
        $recentTrend = collect(range(6, 0))->map(function ($daysAgo) use ($user) {
            $date = now()->subDays($daysAgo);

            return [
                'label' => $date->format('M d'),
                'sent' => EmailLog::where('user_id', $user->id)
                    ->whereDate('created_at', $date->toDateString())
                    ->count(),

                'opens' => EmailLog::where('user_id', $user->id)
                    ->where('opened', true)
                    ->whereDate('created_at', $date->toDateString())
                    ->count(),

                'clicks' => EmailLog::where('user_id', $user->id)
                    ->where('clicked', true)
                    ->whereDate('created_at', $date->toDateString())
                    ->count(),
            ];
        })->values();

        return view('dashboard', [
            'stats' => [
                'total_campaigns' => (clone $campaigns)->count(),
                'total_subscribers' => (clone $subscribers)->count(),
                'emails_sent' => $sent,
                'open_rate' => $sent > 0 ? round(($opens / $sent) * 100, 2) : 0,
                'click_rate' => $sent > 0 ? round(($clicks / $sent) * 100, 2) : 0,
                'bounce_rate' => $sent > 0 ? round(($bounces / $sent) * 100, 2) : 0,
                'smtp_accounts' => SMTPAccount::ownedBy($user->id)->count(),
                'unread_messages' => Message::where('user_id', $user->id)
                    ->where('is_read', false)
                    ->count(),
            ],

            'recentCampaigns' => (clone $campaigns)->latest()->limit(5)->get(),

            'recentSubscribers' => (clone $subscribers)->latest()->limit(5)->get(),

            // MAIN FEATURE PRESERVED
            'trend' => $recentTrend,
        ]);
    }
}