<?php

namespace App\Console\Commands;

use App\Jobs\SendCampaignEmailJob;
use App\Models\EmailJob;
use Illuminate\Console\Command;

class DispatchScheduledEmailJobs extends Command
{
    protected $signature = 'infimal:dispatch-scheduled-emails {--chunk=750} {--max=5000}';

    protected $description = 'Dispatch due user email jobs in scalable chunks without cross-queue leakage';

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
