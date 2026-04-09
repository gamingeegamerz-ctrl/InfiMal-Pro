<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/dashboard';

    public function boot(): void
    {
        $this->configureRateLimiting();
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', fn (Request $request) => Limit::perMinute(60)->by($request->user()?->id ?: $request->ip()));

        RateLimiter::for('login', fn (Request $request) => [
            Limit::perMinute(8)->by(strtolower((string) $request->input('email')).'|'.$request->ip()),
        ]);

        RateLimiter::for('register', fn (Request $request) => [
            Limit::perMinute(5)->by($request->ip()),
        ]);

        RateLimiter::for('payment', fn (Request $request) => [
            Limit::perMinute(12)->by(($request->user()?->id ?: 'guest').'|'.$request->ip()),
        ]);

        RateLimiter::for('otp', fn (Request $request) => [
            Limit::perMinute(6)->by(($request->user()?->id ?: 'guest').'|'.$request->ip()),
        ]);

        RateLimiter::for('webhook', fn (Request $request) => [
            Limit::perMinute(60)->by($request->ip()),
            Limit::perMinute(120)->by($request->header('PAYPAL-TRANSMISSION-ID', $request->ip())),
        ]);
    }
}
