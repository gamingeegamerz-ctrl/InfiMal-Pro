<?php

namespace App\Console\Commands;

use App\Services\ProductionSafetyService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DailyHealthReport extends Command
{
    protected $signature = 'infimal:daily-health-report';

    protected $description = 'Generate daily health summary for operations';

    public function handle(ProductionSafetyService $safety): int
    {
        $metrics = $safety->collectGlobalMetrics();

        $smtpStats = DB::table('email_logs')
            ->select('smtp_id', DB::raw('COUNT(*) as total'), DB::raw("SUM(CASE WHEN status IN ('sent','delivered') THEN 1 ELSE 0 END) as ok"))
            ->whereDate('created_at', today())
            ->whereNotNull('smtp_id')
            ->groupBy('smtp_id')
            ->get()
            ->map(fn ($r) => [
                'smtp_id' => $r->smtp_id,
                'success_rate' => $r->total > 0 ? round(($r->ok / $r->total) * 100, 2) : 0,
                'volume' => (int) $r->total,
            ]);

        $top = $smtpStats->sortByDesc('success_rate')->first();
        $worst = $smtpStats->sortBy('success_rate')->first();

        $report = [
            'total_emails_sent' => $metrics['total_sent'],
            'success_rate' => $metrics['success_rate'],
            'bounce_rate' => $metrics['bounce_rate'],
            'complaint_rate' => $metrics['complaint_rate'],
            'top_smtp' => $top,
            'worst_smtp' => $worst,
        ];

        $safety->alert('Daily health report', $report);

        $this->info(json_encode($report));

        return self::SUCCESS;
    }
}
