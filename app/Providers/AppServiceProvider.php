<?php

namespace App\Providers;

use App\Services\DeliverabilityConfigService;
use App\Services\MonitoringService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(DeliverabilityConfigService $deliverability, MonitoringService $monitoring): void
    {
        $this->registerRateLimiters();

        if (! app()->environment('production')) {
            return;
        }

        $cacheKey = 'deliverability:healthcheck:'.now()->format('Y-m-d-H');
        if (! Cache::add($cacheKey, true, now()->addHour())) {
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

    private function registerRateLimiters(): void
    {
        RateLimiter::for('api', fn (Request $request) => Limit::perMinute(60)->by($request->user()?->id ?: $request->ip()));
        RateLimiter::for('login', fn (Request $request) => Limit::perMinute(8)->by(strtolower((string) $request->input('email')).'|'.$request->ip()));
        RateLimiter::for('register', fn (Request $request) => Limit::perMinute(5)->by($request->ip()));
        RateLimiter::for('payment', fn (Request $request) => Limit::perMinute(12)->by(($request->user()?->id ?: 'guest').'|'.$request->ip()));
        RateLimiter::for('otp', fn (Request $request) => Limit::perMinute(6)->by(($request->user()?->id ?: 'guest').'|'.$request->ip()));
        RateLimiter::for('webhook', fn (Request $request) => Limit::perMinute(60)->by($request->ip()));
    }
}
