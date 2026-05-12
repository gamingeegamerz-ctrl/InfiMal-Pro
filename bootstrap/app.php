<?php

use App\Http\Middleware\CheckPaidUser;
use App\Http\Middleware\EnsurePaidAccess;
use App\Http\Middleware\EnforceOnboardingState;
use App\Http\Middleware\EnsureActiveSubscription;
use App\Http\Middleware\EnforceUsageLimits;
use App\Http\Middleware\IsAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use App\Console\Commands\CreateSesInfrastructure;
use App\Console\Commands\MonitorBounceComplaintRates;
use App\Console\Commands\UnsuspendUser;
use App\Console\Commands\UpdateUserWarmupLimits;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withCommands([
        CreateSesInfrastructure::class,
        MonitorBounceComplaintRates::class,
        UnsuspendUser::class,
        UpdateUserWarmupLimits::class,
    ])
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'paid' => CheckPaidUser::class,
            'paid.access' => EnsurePaidAccess::class,
            'flow.state' => EnforceOnboardingState::class,
            'subscription.active' => EnsureActiveSubscription::class,
            'usage.limits' => EnforceUsageLimits::class,
            'admin' => IsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();