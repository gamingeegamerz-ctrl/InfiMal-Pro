<?php

namespace App\Services;

use App\Models\CampaignAnalytics;
use App\Models\EmailLog;
use App\Models\SMTPAccount;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    public function userStats(int $userId): array
    {
        $base = EmailLog::where('user_id', $userId);

        return [
            'total_sent' => (clone $base)->count(),
            'opens' => (clone $base)->where('opened', true)->count(),
            'clicks' => (clone $base)->where('clicked', true)->count(),
            'bounces' => (clone $base)->where('status', 'bounced')->count(),
            'recent_activity' => (clone $base)->latest()->limit(20)->get(),
        ];
    }

    public function dashboard(int $userId): array
    {
        return Cache::remember("analytics:dashboard:user:{$userId}", now()->addMinutes(10), function () use ($userId) {
            $hourly = DB::table('hourly_email_analytics')->where('user_id', $userId);
            $hasHourly = (clone $hourly)->exists();

            $campaign = CampaignAnalytics::whereHas('campaign', fn ($q) => $q->where('user_id', $userId));
            $smtpBase = EmailLog::where('user_id', $userId);

            $campaignStats = [
                'sent' => $hasHourly ? (int) (clone $hourly)->sum('sent') : (clone $campaign)->where('event_type', 'sent')->count(),
                'delivered' => $hasHourly ? (int) (clone $hourly)->sum('delivered') : (clone $campaign)->where('event_type', 'delivered')->count(),
                'bounce' => $hasHourly ? (int) (clone $hourly)->sum('bounced') : (clone $campaign)->where('event_type', 'bounced')->count(),
                'complaint' => $hasHourly ? (int) (clone $hourly)->sum('complaints') : (clone $campaign)->whereIn('event_type', ['complaint', 'spam_complaint'])->count(),
                'click' => $hasHourly ? (int) (clone $hourly)->sum('clicked') : (clone $campaign)->where('event_type', 'clicked')->count(),
                'reply' => $hasHourly ? (int) (clone $hourly)->sum('replied') : (clone $campaign)->where('event_type', 'reply')->count(),
            ];

            $total = (clone $smtpBase)->count();
            $successful = (clone $smtpBase)->whereIn('status', ['sent', 'delivered'])->count();

            return [
                'campaign_stats' => $campaignStats,
                'smtp_stats' => [
                    'reputation_score' => round((float) SMTPAccount::ownedBy($userId)->userOwned()->avg('reputation_score'), 2),
                    'success_rate' => $total > 0 ? round(($successful / $total) * 100, 2) : 0.0,
                    'provider_performance' => $this->providerPerformance($userId),
                ],
                'engagement_score' => $this->engagementScoreByCampaign($userId),
            ];
        });
    }

    public function adminPerUserStats()
    {
        return DB::table('users')
            ->leftJoin('email_logs', 'users.id', '=', 'email_logs.user_id')
            ->selectRaw('users.id as user_id, users.name, users.email, COUNT(email_logs.id) as total_sent')
            ->selectRaw('SUM(CASE WHEN email_logs.opened = 1 THEN 1 ELSE 0 END) as opens')
            ->selectRaw('SUM(CASE WHEN email_logs.clicked = 1 THEN 1 ELSE 0 END) as clicks')
            ->selectRaw("SUM(CASE WHEN email_logs.status = 'bounced' THEN 1 ELSE 0 END) as bounces")
            ->groupBy('users.id', 'users.name', 'users.email')
            ->get()
            ->map(function ($row) {
                $row->smtp_count = SMTPAccount::where('user_id', $row->user_id)->userOwned()->count();
                return $row;
            });
    }

    private function providerPerformance(int $userId)
    {
        return DB::table('email_logs as logs')
            ->leftJoin('smtps', 'smtps.id', '=', 'logs.smtp_id')
            ->where('logs.user_id', $userId)
            ->selectRaw('COALESCE(smtps.host, "unknown") as provider')
            ->selectRaw('COUNT(logs.id) as total')
            ->selectRaw("SUM(CASE WHEN logs.status IN ('sent','delivered') THEN 1 ELSE 0 END) as successful")
            ->groupBy('provider')
            ->get()
            ->map(fn ($row) => [
                'provider' => $row->provider,
                'success_rate' => $row->total > 0 ? round(($row->successful / $row->total) * 100, 2) : 0,
                'volume' => (int) $row->total,
            ]);
    }

    private function engagementScoreByCampaign(int $userId)
    {
        return DB::table('campaign_analytics')
            ->join('campaigns', 'campaigns.id', '=', 'campaign_analytics.campaign_id')
            ->where('campaigns.user_id', $userId)
            ->select('campaign_analytics.campaign_id')
            ->selectRaw("SUM(CASE WHEN event_type = 'reply' THEN 5 WHEN event_type = 'clicked' THEN 3 WHEN event_type IN ('opened','open') THEN 1 ELSE 0 END) as score")
            ->groupBy('campaign_analytics.campaign_id')
            ->orderByDesc('score')
            ->get();
    }
}
