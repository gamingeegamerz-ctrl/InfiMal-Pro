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
        $max = max($chunk, (int) $this->option('max'));

        EmailJob::query()
            ->where('status', 'queued')
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

        return self::SUCCESS;
    }
}
