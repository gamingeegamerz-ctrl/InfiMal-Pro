<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AggregateHourlyEmailAnalytics extends Command
{
    protected $signature = 'infimal:aggregate-email-analytics';

    protected $description = 'Aggregate email analytics hourly for dashboard performance';

    public function handle(): int
    {
        $start = now()->subHour()->startOfHour();
        $end = now()->subHour()->endOfHour();

        $rows = DB::table('email_logs')
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('user_id, campaign_id, smtp_id')
            ->selectRaw('? as bucket_hour', [$start->toDateTimeString()])
            ->selectRaw("SUM(CASE WHEN status = 'sent' THEN 1 ELSE 0 END) as sent")
            ->selectRaw("SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered")
            ->selectRaw("SUM(CASE WHEN status = 'bounced' THEN 1 ELSE 0 END) as bounced")
            ->selectRaw("SUM(CASE WHEN clicked = 1 THEN 1 ELSE 0 END) as clicked")
            ->selectRaw("SUM(CASE WHEN opened = 1 THEN 1 ELSE 0 END) as replied")
            ->groupBy('user_id', 'campaign_id', 'smtp_id')
            ->get();

        foreach ($rows as $row) {
            DB::table('hourly_email_analytics')->updateOrInsert(
                [
                    'user_id' => $row->user_id,
                    'campaign_id' => $row->campaign_id,
                    'smtp_id' => $row->smtp_id,
                    'bucket_hour' => $row->bucket_hour,
                ],
                [
                    'sent' => $row->sent,
                    'delivered' => $row->delivered,
                    'bounced' => $row->bounced,
                    'complaints' => 0,
                    'clicked' => $row->clicked,
                    'replied' => $row->replied,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        $this->info('Hourly analytics aggregation complete for '.$start->toDateTimeString());

        return self::SUCCESS;
    }
}
