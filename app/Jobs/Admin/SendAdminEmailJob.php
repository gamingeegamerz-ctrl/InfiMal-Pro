<?php

namespace App\Jobs\Admin;

use App\Models\Admin\AdminEmailJob;
use App\Services\AdaptiveThrottleService;
use App\Services\Admin\AdminSmtpRouterService;
use App\Services\Admin\AdminWarmupService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendAdminEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public int $adminEmailJobId)
    {
        $this->onQueue(config('infimal.queue.admin_email_queue', 'admin_email_jobs'));
    }

    public function handle(
        AdminSmtpRouterService $router,
        AdminWarmupService $warmup,
        AdaptiveThrottleService $throttle,
    ): void {
        $job = AdminEmailJob::find($this->adminEmailJobId);
        if (! $job || in_array($job->status, ['sent', 'failed'], true)) {
            return;
        }

        if ($job->idempotency_key && AdminEmailJob::where('idempotency_key', $job->idempotency_key)->where('status', 'sent')->exists()) {
            $job->update(['status' => 'sent']);
            return;
        }

        $domain = strtolower((string) substr(strrchr($job->from_email, '@'), 1));
        $ipPool = $router->pickBestIpForRecipient($domain, $job->to_email);

        if (! $ipPool) {
            $job->update(['status' => 'queued', 'error_message' => 'No active admin IP available']);
            $this->release(120);
            return;
        }

        $bounceRate = (float) $ipPool->bounce_rate;
        $complaintRate = (float) $ipPool->complaint_rate;

        if ($throttle->shouldPause($bounceRate, $complaintRate)) {
            $ipPool->update(['status' => 'paused']);
            $job->update(['status' => 'queued', 'error_message' => 'IP paused by anomaly guard']);
            $this->release(300);
            return;
        }

        $node = $ipPool->node;
        $port = $router->resolveNodePort($node, $ipPool);

        $warmupLimit = $warmup->resolveDailyTarget($ipPool, $domain);
        if ($ipPool->sent_today >= $warmupLimit) {
            $job->update(['status' => 'queued', 'error_message' => 'Warmup cap reached']);
            $this->release(180);
            return;
        }

        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.host' => $node->hostname,
            'mail.mailers.smtp.port' => $port,
            'mail.mailers.smtp.encryption' => 'tls',
            'mail.mailers.smtp.username' => env('ADMIN_SMTP_USERNAME'),
            'mail.mailers.smtp.password' => env('ADMIN_SMTP_PASSWORD'),
            'mail.from.address' => $job->from_email,
            'mail.from.name' => $job->from_name,
        ]);

        $delayMs = $throttle->recommendedDelayMs($bounceRate, $complaintRate, 0);
        usleep($delayMs * 1000);

        try {
            Mail::html($job->html_body, function ($message) use ($job): void {
                $message->to($job->to_email)->subject($job->subject);
            });

            $router->notePortResult($ipPool, $port, true);
            $ipPool->increment('sent_today');
            $ipPool->update(['last_used_at' => now()]);

            $job->update([
                'status' => 'sent',
                'node_id' => $node->id,
                'ip_pool_id' => $ipPool->id,
                'sent_at' => now(),
                'error_message' => null,
            ]);
        } catch (\Throwable $e) {
            $router->notePortResult($ipPool, $port, false);
            $job->update([
                'status' => 'queued',
                'error_message' => substr($e->getMessage(), 0, 1000),
            ]);
            $this->release(120);
        }
    }
}
