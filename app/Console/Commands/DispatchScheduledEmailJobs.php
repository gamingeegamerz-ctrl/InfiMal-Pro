<?php

namespace App\Console\Commands;

use App\Services\ProductionSafetyService;
use App\Jobs\SendCampaignEmailJob;
use App\Models\EmailJob;
use Illuminate\Support\Facades\Cache;
use App\Jobs\SendCampaignEmailJob;
use App\Models\EmailJob;
use Illuminate\Console\Command;

class DispatchScheduledEmailJobs extends Command
{
    protected $signature = 'infimal:dispatch-scheduled-emails {--chunk=750} {--max=5000}';

    protected $description = 'Dispatch due user email jobs in scalable chunks without cross-queue leakage';

    public function handle(ProductionSafetyService $safety): int
    {
        $chunk = max(100, (int) $this->option('chunk'));
        $max = max($chunk, (int) $this->option('max'));

        if ($safety->isGlobalPause()) {
            $this->warn('Global pause enabled; dispatch skipped.');
            return self::SUCCESS;
        }

        if ($safety->isSafeMode()) {
            $max = (int) floor($max * 0.6);
        }


        if (Cache::get('manual_override_force_send', false)) {
            $safety->alert('Manual override attempt blocked', ['action' => 'force_send_dispatch'], 'high');
            $this->warn('Manual force-send override is blocked by guardrail.');
            return self::SUCCESS;
        }


        EmailJob::query()
            ->where('status', 'queued')
    public function handle(): int
    {
        $chunk = max(100, (int) $this->option('chunk'));
        $max = min((int) config('infimal.scheduler.max_jobs_per_run', 5000), max($chunk, (int) $this->option('max')));
        $windowMinutes = (int) config('infimal.scheduler.window_minutes', 10);
        $windowEnd = now()->addMinutes($windowMinutes);
        $dispatched = 0;

        EmailJob::query()
            ->queued()
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->update([
                'status' => 'failed',
                'error_message' => 'Expired from backlog cleanup',
                'failed_at' => now(),
            ]);

        $backlog = EmailJob::query()->queued()->count();
        if ($backlog > 100000) {
            $max = (int) floor($max * 0.8); // intake damping under extreme backlog
        }

        $dispatched = 0;
        $plan = [1 => 0.50, 2 => 0.30, 3 => 0.20];

        foreach ($plan as $priority => $ratio) {
            $budget = max(1, (int) floor($max * $ratio));
            $sentThisBucket = 0;

            EmailJob::query()
                ->queued()
                ->readyToSend()
                ->where('priority', $priority)
                ->where(function ($q) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                })
                ->orderBy('id')
                ->chunkById($chunk, function ($jobs) use (&$dispatched, &$sentThisBucket, $budget, $max) {
                    foreach ($jobs as $job) {
                        if ($dispatched >= $max || $sentThisBucket >= $budget) {
                            return false;
                        }

                        SendCampaignEmailJob::dispatch($job->id)->onQueue('emails');
                        $dispatched++;
                        $sentThisBucket++;
                    }

                    return true;
                });
        }

        $this->info('Dispatched '.$dispatched.' due queued user jobs with weighted fairness.');
                'error_message' => 'Expired in backlog cleanup.',
                'failed_at' => now(),
            ]);

        EmailJob::query()
            ->queued()
            ->readyToSend()
            ->where(function ($q) use ($windowEnd) {
                $q->whereNull('scheduled_at')->orWhere('scheduled_at', '<=', $windowEnd);
            })
            ->orderByDesc('priority')
            ->orderBy('scheduled_at')
            ->chunkById($chunk, function ($jobs) use (&$dispatched, $max) {
                foreach ($jobs as $job) {
                    if ($dispatched >= $max) {
                        return false;
                    }

                    SendCampaignEmailJob::dispatch($job->id)->onQueue('emails');
                    $dispatched++;
                }

                return true;
            });

        $this->info('Dispatched '.$dispatched.' queued jobs in '.$windowMinutes.' minute window.');

        return self::SUCCESS;
    }
}
