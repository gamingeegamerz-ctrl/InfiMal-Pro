<?php

namespace App\Services;

use App\Jobs\SendOpsAlertJob;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MonitoringService
{
    public function critical(string $event, array $context = []): void
    {
        Log::channel('alerts')->critical($event, $context);

        if (! (bool) config('infimal.alerts.enabled', true)) {
            return;
        }

        $debounceKey = 'alert:'.md5($event.'|'.($context['transmission_id'] ?? $context['order_id'] ?? 'generic'));
        if (! Cache::add($debounceKey, true, now()->addMinute())) {
            return;
        }

        $message = $event.' | '.json_encode($context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        SendOpsAlertJob::dispatch('[InfiMal Critical] '.$event, $message);
    }
}
