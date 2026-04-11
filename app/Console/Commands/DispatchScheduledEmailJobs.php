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
        $dispatched = 0;

        EmailJob::query()
            ->queued()
            ->readyToSend()
            ->orderBy('id')
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

        $this->info('Dispatched '.$dispatched.' due queued user jobs in chunks.');

        return self::SUCCESS;
    }
}
