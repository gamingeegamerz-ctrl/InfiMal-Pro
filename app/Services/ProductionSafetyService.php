<?php

namespace App\Services;

use App\Services\AuditLogService;
use App\Jobs\SendOpsAlertJob;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductionSafetyService
{
    public function __construct(private readonly AuditLogService $audit)
    {
    }
    private const SAFE_MODE_KEY = 'system_safe_mode';
    private const GLOBAL_PAUSE_KEY = 'system_global_pause';

    public function isSafeMode(): bool
    {
        return (bool) Cache::get(self::SAFE_MODE_KEY, false);
    }

    public function enableSafeMode(string $reason): void
    {
        Cache::put(self::SAFE_MODE_KEY, true, now()->addDay());
        $this->alert('Safe mode enabled', ['reason' => $reason], 'high');
    }

    public function disableSafeMode(): void
    {
        Cache::forget(self::SAFE_MODE_KEY);
    }

    public function isGlobalPause(): bool
    {
        return (bool) Cache::get(self::GLOBAL_PAUSE_KEY, false);
    }

    public function activateGlobalPause(string $reason): void
    {
        Cache::put(self::GLOBAL_PAUSE_KEY, true, now()->addHours(6));
        $this->alert('Global kill switch activated', ['reason' => $reason], 'critical');
    }

    public function collectGlobalMetrics(): array
    {
        $totalLogs = DB::table('email_logs')->whereDate('created_at', today())->count();
        $bounced = DB::table('email_logs')->whereDate('created_at', today())->where('status', 'bounced')->count();
        $complaints = DB::table('hourly_email_analytics')->whereDate('bucket_hour', today())->sum('complaints');
        $success = DB::table('email_logs')->whereDate('created_at', today())->whereIn('status', ['sent', 'delivered'])->count();
        $queue = DB::table('jobs')->count() + DB::table('email_jobs')->where('status', 'queued')->count();

        return [
            'queue_size' => (int) $queue,
            'bounce_rate' => $totalLogs > 0 ? round(($bounced / $totalLogs) * 100, 2) : 0.0,
            'complaint_rate' => $totalLogs > 0 ? round((((int) $complaints) / $totalLogs) * 100, 2) : 0.0,
            'success_rate' => $totalLogs > 0 ? round(($success / $totalLogs) * 100, 2) : 0.0,
            'total_sent' => (int) $totalLogs,
        ];
    }

    public function alert(string $title, array $context, string $severity = "medium"): void
    {
        $safe = $this->sanitize($context);

        if ($severity === 'low') {
            Log::channel('alerts')->info($title, $safe);
        } elseif ($severity === 'medium') {
            Log::channel('alerts')->warning($title, $safe);
            SendOpsAlertJob::dispatch('[InfiMal Alert] '.$title, json_encode($safe))->onQueue('alerts');
        } elseif ($severity === 'high') {
            Log::channel('alerts')->error($title, $safe);
            SendOpsAlertJob::dispatch('[InfiMal High] '.$title, json_encode($safe))->onQueue('alerts');
        } else {
            Log::channel('alerts')->critical($title, $safe);
            SendOpsAlertJob::dispatch('[InfiMal Critical] '.$title, json_encode($safe))->onQueue('alerts');
        }

        $this->audit->record('alert', $title, $safe, $severity);
    }

    public function safeModeRateFactor(): float
    {
        return $this->isSafeMode() ? 0.6 : 1.0;
    }

    private function sanitize(array $context): array
    {
        $json = json_encode($context);
        $json = preg_replace('/("?(password|api_key|token)"?\s*:\s*")[^"]+"/i', '$1[masked]"', (string) $json);

        return json_decode((string) $json, true) ?: [];
    }
}
