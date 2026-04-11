<?php

namespace App\Console\Commands;

use App\Jobs\SendCampaignEmailJob;
use App\Services\SchedulerService;
use Illuminate\Console\Command;

class DispatchScheduledEmailJobs extends Command
{
    protected $signature = 'infimal:dispatch-scheduled-emails {--limit=500}';

    protected $description = 'Dispatch only due email jobs (scheduled_at <= now)';

    public function handle(SchedulerService $scheduler): int
    {
        $jobs = $scheduler->dueQueuedJobs((int) $this->option('limit'));

        foreach ($jobs as $job) {
            SendCampaignEmailJob::dispatch($job->id)->onQueue('emails');
        }

        $this->info('Dispatched '.$jobs->count().' due queued jobs.');

        return self::SUCCESS;
    }
}
