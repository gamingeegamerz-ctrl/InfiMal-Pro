<?php

namespace App\Services;

use App\Jobs\SendOpsAlertJob;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MonitoringService
{
    public function critical(string $event, array $context = []): void
    {
        // 1. Always log to the 'alerts' channel
        Log::channel('alerts')->critical($event, $context);

        // 2. Check if alerts are enabled globally
        if (! (bool) config('infimal.alerts.enabled', true)) {
            return;
        }

        // 3. Debounce – prevent duplicate alerts for same event+context within 1 minute
        $hash = sha1($event . '|' . json_encode($context));
        $key = 'alert:debounce:' . $hash;

        if (! Cache::add($key, now()->timestamp, now()->addMinute())) {
            return; // duplicate, skip
        }

        // 4. Build message and dispatch to dedicated queue
        $message = $event . ' | ' . json_encode($context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        SendOpsAlertJob::dispatch('[InfiMal Critical] ' . $event, $message)->onQueue('alerts');
    }
}
