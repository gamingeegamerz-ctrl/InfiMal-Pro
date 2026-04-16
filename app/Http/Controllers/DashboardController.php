<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\EmailLog;
use App\Models\Message;
use App\Models\SMTPAccount;
use App\Models\Subscriber;
use App\Services\UserActivityService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly UserActivityService $activityService)
    {
    }

    public function index(): View
    {
        $user = Auth::user();
        $this->activityService->sync($user);

        $campaigns = Campaign::where('user_id', $user->id);
        $subscribers = Subscriber::where('user_id', $user->id);
        $logs = EmailLog::where('user_id', $user->id);

        $sent = (clone $logs)->count();
        $delivered = (clone $logs)->where('status', 'delivered')->count();
        $opens = (clone $logs)->where('opened', true)->count();
        $clicks = (clone $logs)->where('clicked', true)->count();
        $bounces = (clone $logs)->where('status', 'bounced')->count();
        $complaints = (clone $logs)->whereNotNull('complained_at')->count();
        $replies = (clone $logs)->whereNotNull('replied_at')->count();

        $recentTrend = collect(range(6, 0))->map(function ($daysAgo) use ($user) {
            $date = now()->subDays($daysAgo)->toDateString();
            $dayLogs = EmailLog::query()->where('user_id', $user->id)->whereDate('created_at', $date);

            return [
                'label' => now()->subDays($daysAgo)->format('M d'),
                'sent' => (clone $dayLogs)->count(),
                'delivered' => (clone $dayLogs)->where('status', 'delivered')->count(),
                'opens' => (clone $dayLogs)->where('opened', true)->count(),
                'clicks' => (clone $dayLogs)->where('clicked', true)->count(),
            ];
        })->values();

        $campaignAnalytics = DB::table('email_logs')
            ->selectRaw('campaign_id, COUNT(*) as sent')
            ->selectRaw("SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered")
            ->selectRaw("SUM(CASE WHEN status = 'bounced' THEN 1 ELSE 0 END) as bounced")
            ->selectRaw('SUM(CASE WHEN opened = 1 THEN 1 ELSE 0 END) as opened')
            ->selectRaw('SUM(CASE WHEN clicked = 1 THEN 1 ELSE 0 END) as clicked')
            ->where('user_id', $user->id)
            ->groupBy('campaign_id')
            ->orderByDesc('sent')
            ->limit(8)
            ->get();

        $smtpPerformance = DB::table('email_logs')
            ->join('smtps', 'smtps.id', '=', 'email_logs.smtp_id')
            ->selectRaw('smtps.id, COALESCE(smtps.name, smtps.smtp_host) as smtp_name')
            ->selectRaw('COUNT(email_logs.id) as sent')
            ->selectRaw("SUM(CASE WHEN email_logs.status = 'delivered' THEN 1 ELSE 0 END) as delivered")
            ->selectRaw("SUM(CASE WHEN email_logs.status = 'bounced' THEN 1 ELSE 0 END) as bounced")
            ->where('email_logs.user_id', $user->id)
            ->groupBy('smtps.id', 'smtps.name', 'smtps.smtp_host')
            ->orderByDesc('sent')
            ->limit(5)
            ->get();

        return view('dashboard', [
            'stats' => [
                'total_campaigns' => (clone $campaigns)->count(),
                'total_subscribers' => (clone $subscribers)->count(),
                'emails_sent' => $sent,
                'delivery_rate' => $sent > 0 ? round(($delivered / $sent) * 100, 2) : 0,
                'open_rate' => $sent > 0 ? round(($opens / $sent) * 100, 2) : 0,
                'click_rate' => $sent > 0 ? round(($clicks / $sent) * 100, 2) : 0,
                'bounce_rate' => $sent > 0 ? round(($bounces / $sent) * 100, 2) : 0,
                'complaint_rate' => $sent > 0 ? round(($complaints / $sent) * 100, 2) : 0,
                'reply_rate' => $sent > 0 ? round(($replies / $sent) * 100, 2) : 0,
                'smtp_accounts' => SMTPAccount::ownedBy($user->id)->count(),
                'unread_messages' => Message::where('user_id', $user->id)->where('is_read', false)->count(),
            ],
            'recentCampaigns' => (clone $campaigns)->latest()->limit(5)->get(),
            'recentSubscribers' => (clone $subscribers)->latest()->limit(5)->get(),
            'trend' => $recentTrend,
            'campaignAnalytics' => $campaignAnalytics,
            'smtpPerformance' => $smtpPerformance,
            'onboardingState' => $this->activityService->onboardingState($user),
            'isInactiveUser' => $this->activityService->isInactive($user),
        ]);
    }
}
