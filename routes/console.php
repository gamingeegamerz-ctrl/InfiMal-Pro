<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('infimal:backup-db')
    ->dailyAt('02:15')
    ->withoutOverlapping()
    ->onOneServer();

Schedule::command('infimal:dispatch-scheduled-emails --chunk=750 --max=5000')
    ->everyMinute()
    ->withoutOverlapping();

Schedule::command('infimal:aggregate-email-analytics')
    ->hourly()
    ->withoutOverlapping();

Schedule::command('infimal:enforce-admin-smtp-protection --global-max=200000 --campaign-max=50000')
    ->everyFiveMinutes()
    ->withoutOverlapping();

Schedule::command('infimal:auto-scale-workers --max-workers=20')
    ->everyMinute()
    ->withoutOverlapping();

Schedule::command('infimal:monitor-system-health')
    ->everyMinute()
    ->withoutOverlapping();

Schedule::command('infimal:daily-health-report')
    ->dailyAt('00:15')
    ->withoutOverlapping();
Schedule::command('infimal:enforce-admin-smtp-protection')
    ->everyFiveMinutes()
    ->withoutOverlapping();

Schedule::command('infimal:auto-scale-workers')
    ->everyMinute()
    ->withoutOverlapping();
