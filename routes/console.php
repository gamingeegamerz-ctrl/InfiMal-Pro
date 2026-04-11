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

Schedule::command('infimal:enforce-admin-smtp-protection')
    ->everyFiveMinutes()
    ->withoutOverlapping();
