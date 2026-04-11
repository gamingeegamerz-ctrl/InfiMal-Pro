<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AutoScaleEmailWorkers extends Command
{
    protected $signature = 'infimal:auto-scale-workers {--min-workers=1} {--max-workers=20} {--scale-step=2}';

    protected $description = 'Compute dynamic worker target based on queue pressure';

    public function handle(): int
    {
        $pendingJobs = DB::table('email_jobs')->where('status', 'queued')->count();
        $queueBacklog = DB::table('jobs')->where('queue', 'emails')->count();
        $load = $pendingJobs + $queueBacklog;

        $minWorkers = max(1, (int) $this->option('min-workers'));
        $maxWorkers = max($minWorkers, (int) $this->option('max-workers'));
        $step = max(1, (int) $this->option('scale-step'));

        $current = (int) Cache::get('workers:emails:target', $minWorkers);
        $lastAdjustAt = Cache::get('workers:emails:last_adjust_at');

        $trend = Cache::get('workers:emails:trend', []);
        $trend[] = $load;
        $trend = array_slice($trend, -5);
        Cache::put('workers:emails:trend', $trend, now()->addMinutes(30));

        if ($lastAdjustAt && now()->diffInMinutes($lastAdjustAt) < 7) {
            $this->info("Cooldown active; keeping workers={$current} (load={$load})");
            return self::SUCCESS;
        }

        $highConsistent = count(array_filter($trend, fn ($v) => $v > 2000)) >= 3;
        $lowConsistent = count(array_filter($trend, fn ($v) => $v < 300)) >= 3;

        $target = $current;
        if ($highConsistent) {
            $target = min($maxWorkers, $current + $step);
        } elseif ($lowConsistent) {
            $target = max($minWorkers, $current - 1);
        }

        if ($target !== $current) {
            Cache::put('workers:emails:last_adjust_at', now(), now()->addHour());
        }

        Cache::put('workers:emails:target', $target, now()->addMinutes(10));

        $this->info("Recommended workers={$target} (current={$current}, load={$load})");

        return self::SUCCESS;
    }
}
