<?php

namespace App\Providers;

use App\Services\DeliverabilityConfigService;
use App\Services\MonitoringService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(DeliverabilityConfigService $deliverability, MonitoringService $monitoring): void
    {
        if (! app()->environment('production')) {
            return;
        }

        $status = $deliverability->status();
        if (! in_array(false, $status, true)) {
            return;
        }

        $key = 'deliverability-alerted:'.now()->format('Y-m-d');
        if (! Cache::add($key, true, now()->endOfDay())) {
            return;
        }

        Log::channel('security')->warning('Email deliverability records are incomplete', $status);
        $monitoring->critical('Deliverability configuration incomplete', $status);
    }
}
