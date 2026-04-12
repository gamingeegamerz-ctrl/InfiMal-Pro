<?php

namespace App\Jobs;

use App\Services\AdminSmtpRouterService;
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

    public function __construct(public array $payload)
    {
        $this->onQueue('admin_email_jobs');
    }

    public function handle(AdminSmtpRouterService $router): void
    {
        $ip = $router->pickBestIp();
        if (! $ip) {
            return;
        }

        $router->applySmtpConfig($ip);

        Mail::raw($this->payload['body'], function ($message): void {
            $message->to($this->payload['to'])
                ->subject($this->payload['subject']);
        });

        $ip->increment('daily_sent');

        $delay = $router->providerDelaySeconds($this->payload['to']);
        if ($delay > 1) {
            usleep($delay * 100000);
        }
    }
}
