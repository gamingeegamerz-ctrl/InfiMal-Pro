<?php

namespace App\Console\Commands;

use App\Services\ProductionSafetyService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AutoScaleEmailWorkers extends Command
{
    protected $signature = 'infimal:auto-scale-workers {--min-workers=1} {--max-workers=20} {--scale-step=2}';

    protected $description = 'Compute dynamic worker target based on queue pressure';

    public function handle(ProductionSafetyService $safety): int
    {
        $pendingJobs = DB::table('email_jobs')->where('status', 'queued')->count();
        $queueBacklog = DB::table('jobs')->where('queue', 'emails')->count();
        $load = $pendingJobs + $queueBacklog;

        $minWorkers = max(1, (int) $this->option('min-workers'));
        $maxWorkers = max($minWorkers, (int) $this->option('max-workers'));
        $step = max(1, (int) $this->option('scale-step'));

        $current = (int) Cache::get('workers:emails:target', $minWorkers);

        if ($safety->isGlobalPause()) {
            Cache::put('workers:emails:target', $minWorkers, now()->addMinutes(10));
            $this->info('Global pause active; forcing min workers.');
            return self::SUCCESS;
        }
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
            $effectiveStep = $safety->isSafeMode() ? 1 : $step;
            $target = min($maxWorkers, $current + $effectiveStep);
        } elseif ($lowConsistent) {
            $target = max($minWorkers, $current - 1);
        }

        if ($target !== $current) {
            Cache::put('workers:emails:last_adjust_at', now(), now()->addHour());
        }

        Cache::put('workers:emails:target', $target, now()->addMinutes(10));

        $this->info("Recommended workers={$target} (current={$current}, load={$load})");
    protected $signature = 'infimal:auto-scale-workers';

    protected $description = 'Compute recommended worker count from queue backlog';

    public function handle(): int
    {
        $pending = DB::table('jobs')->where('queue', 'emails')->count();
        $targetPerWorker = max(50, (int) config('infimal.workers.target_jobs_per_worker', 200));
        $maxWorkers = max(1, (int) config('infimal.workers.max_workers', 20));

        $recommended = (int) ceil($pending / $targetPerWorker);
        $recommended = max(1, min($recommended, $maxWorkers));

        Cache::put('workers:emails:recommended', $recommended, now()->addMinutes(10));

        $this->info("Pending={$pending}, recommended_workers={$recommended}");

        return self::SUCCESS;
    }
}
