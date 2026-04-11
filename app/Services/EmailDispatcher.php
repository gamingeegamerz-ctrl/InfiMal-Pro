<?php

namespace App\Services;

use App\Jobs\SendEmailJob;
use App\Models\EmailJob;

class EmailDispatcher
{
    public static function dispatch(array $data): void
    {
        // 1. Save email to database
        $emailJob = EmailJob::create([
            'user_id'     => $data['user_id'],
            'campaign_id' => $data['campaign_id'] ?? null,
            'to_email'    => $data['to'],
            'subject'     => $data['subject'],
            'body'        => $data['body'],
            'status'      => 'queued',
            'idempotency_key' => hash('sha256', implode('|', [
                $data['campaign_id'] ?? 0,
                strtolower((string) $data['to']),
                sha1((string) ($data['subject'] ?? '').(string) ($data['body'] ?? '')),
            ])),
        ]);

        // 2. Push to queue
        SendEmailJob::dispatch($emailJob->id)
            ->onQueue(config('infimal.queue.user_email_queue', 'user_email_jobs'));
    }
}
