<?php

namespace App\Services;

use App\Models\UserSmtpReputation;

class UserReputationService
{
    public function record(int $userId, int $smtpId, string $event): UserSmtpReputation
    {
        $rep = UserSmtpReputation::firstOrCreate(
            ['user_id' => $userId, 'smtp_id' => $smtpId],
            ['score' => 100, 'success_count' => 0, 'bounce_count' => 0, 'complaint_count' => 0]
        );

        match ($event) {
            'sent', 'delivered' => $rep->success_count++,
            'bounced' => $rep->bounce_count++,
            'complaint' => $rep->complaint_count++,
            default => null,
        };

        $rep->score = max(0, 100 - ($rep->bounce_count * 2) - ($rep->complaint_count * 5));
        $rep->last_event_at = now();
        $rep->save();

        return $rep;
    }
}
