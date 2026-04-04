<?php

namespace App\Providers;

use App\Services\DeliverabilityConfigService;
use App\Services\MonitoringService;
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
        $status = $deliverability->status();

        if (in_array(false, $status, true)) {
            Log::channel('security')->warning('Email deliverability records are incomplete', $status);
            $monitoring->critical('Deliverability configuration incomplete', $status);
        }
    }
}
