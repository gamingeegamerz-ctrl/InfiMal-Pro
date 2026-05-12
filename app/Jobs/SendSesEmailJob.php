<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\EmailRateLimiter;
use App\Services\SesService;
use App\Services\SpamKeywordChecker;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use RuntimeException;

class SendSesEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public array $payload)
    {
    }

    public function handle(SesService $sesService, EmailRateLimiter $rateLimiter, SpamKeywordChecker $spamChecker): void
    {
        $user = User::findOrFail($this->payload['user_id']);

        if ($user->suspended) {
            throw new RuntimeException('User is suspended: '.$user->suspension_reason);
        }

        if ($spamChecker->containsSuspiciousKeyword($this->payload['subject'], $this->payload['html_body'])) {
            throw new RuntimeException('Content contains suspicious keywords.');
        }

        if (! $rateLimiter->canSendNow($user)) {
            $this->release(300);
            return;
        }

        $sesService->sendEmail(
            $this->payload['from'],
            $this->payload['to'],
            $this->payload['subject'],
            $this->payload['html_body'],
            $user->id,
            $this->payload['campaign_id'] ?? null,
        );
    }
}
