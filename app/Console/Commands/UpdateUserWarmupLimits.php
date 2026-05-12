<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UpdateUserWarmupLimits extends Command
{
    protected $signature = 'infimail:update-warmup-limits {--reset-today : Reset today_sent to zero}';
    protected $description = 'Update user SES warm-up stages and optionally reset daily send counters.';

    public function handle(): int
    {
        User::query()->chunkById(500, function ($users): void {
            foreach ($users as $user) {
                $age = max(1, $user->created_at?->diffInDays(now()) + 1);
                [$stage, $limit] = match (true) {
                    $age <= 7 => ['days_1_7', 50],
                    $age <= 14 => ['days_8_14', 200],
                    $age <= 21 => ['days_15_21', 500],
                    $age <= 30 => ['days_22_30', 1000],
                    default => ['unlimited', 10000],
                };

                $changes = ['warmup_stage' => $stage, 'daily_limit' => $limit];
                if ($this->option('reset-today')) {
                    $changes['today_sent'] = 0;
                }

                $user->forceFill($changes)->save();
            }
        });

        $this->info('Warm-up limits updated'.($this->option('reset-today') ? ' and daily counters reset.' : '.'));
        return self::SUCCESS;
    }
}
