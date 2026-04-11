<?php

namespace App\Services;

use App\Models\EmailJob;
use App\Models\SMTPAccount;

class EmailDispatcher
{
    public static function dispatch(array $data): void
    {
        $emailJob = EmailJob::create([
            'user_id' => $data['user_id'],
            'campaign_id' => $data['campaign_id'] ?? null,
            'to_email' => $data['to'],
            'subject' => $data['subject'],
            'body' => $data['body'],
            'status' => 'queued',
        ]);

        $smtp = SMTPAccount::ownedBy((int) $data['user_id'])
            ->where('is_active', true)
            ->userOwned()
            ->orderByDesc('is_default')
            ->first();

        if (! $smtp) {
            return;
        }

        app(SchedulerService::class)->scheduleCampaignJobs(collect([$emailJob]), $smtp);
    }
}
