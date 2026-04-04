<?php

use App\Http\Middleware\CheckPaidUser;
use App\Http\Middleware\EnsurePaidAccess;
use App\Http\Middleware\EnforceOnboardingState;
use App\Http\Middleware\EnsureActiveSubscription;
use App\Http\Middleware\EnforceUsageLimits;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'paid' => CheckPaidUser::class,
            'paid.access' => EnsurePaidAccess::class,
            'flow.state' => EnforceOnboardingState::class,
            'subscription.active' => EnsureActiveSubscription::class,
            'usage.limits' => EnforceUsageLimits::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();