<?php

namespace App\Console\Commands;

use App\Services\ProductionSafetyService;
use App\Services\ConfigGuardService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MonitorSystemHealth extends Command
{
    protected $signature = 'infimal:monitor-system-health';

    protected $description = 'Monitor global delivery health and auto-toggle safety controls';

    public function handle(ProductionSafetyService $safety, ConfigGuardService $guard): int
    {
        $m = $safety->collectGlobalMetrics();

        if ($m['queue_size'] > 150000) {
            $safety->alert('Queue backlog overflow', $m);
            $safety->enableSafeMode('queue_backlog_overflow');
        }

        if ($m['bounce_rate'] > 5.0) {
            $safety->alert('Bounce spike detected', $m);
            $safety->enableSafeMode('bounce_spike');
        }

        if ($m['complaint_rate'] > 1.0) {
            $safety->alert('Complaint spike detected', $m);
            $safety->enableSafeMode('complaint_spike');
        }

        if ($m['bounce_rate'] > 12.0 || $m['complaint_rate'] > 3.0) {
            $safety->activateGlobalPause('critical reputation event');
        }


        if ($m['bounce_rate'] > 8.0 || $m['complaint_rate'] > 2.0) {
            foreach (['warmup.daily_growth_percent', 'throttle.max_send_rate', 'scaling.max_workers'] as $key) {
                $guard->rollback($key, 'Auto rollback due to post-change risk spike');
            }
            $safety->alert('Auto rollback executed after risk spike', $m, 'critical');
        }


        if ($m['success_rate'] < 80.0) {
            $safety->alert('Send success rate degraded', $m);
        }


        $lastProcessed = DB::table('email_jobs')->where('status', 'sent')->max('sent_at');
        if ($m['queue_size'] > 5000 && (!$lastProcessed || now()->diffInMinutes($lastProcessed) > 15)) {
            $safety->alert('Worker inactivity detected', ['queue_size' => $m['queue_size'], 'last_processed' => $lastProcessed]);
        }


        if (app()->environment('production') && (bool) config('app.debug')) {
            $safety->alert('Production debug mode enabled', ['app_debug' => true]);
        }

        $this->info('Health monitored: '.json_encode($m));

        return self::SUCCESS;
    }
}
