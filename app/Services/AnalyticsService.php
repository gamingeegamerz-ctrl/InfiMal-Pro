<?php

namespace App\Services;

use App\Models\EmailLog;
use App\Models\SMTPAccount;
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
                $row->smtp_count = SMTPAccount::where('user_id', $row->user_id)->count();
                return $row;
            });
    }
}
