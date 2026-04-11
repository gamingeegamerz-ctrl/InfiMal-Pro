<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AutoScaleEmailWorkers extends Command
{
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
