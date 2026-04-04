<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\EmailLog;
use App\Models\User;

class UserActivityService
{
    public function sync(User $user): void
    {
        $campaignCount = Campaign::where('user_id', $user->id)->count();
        $emailsSent = EmailLog::where('user_id', $user->id)->count();

        $user->forceFill([
            'campaign_count' => $campaignCount,
            'email_sent' => $emailsSent,
        ])->save();
    }

    public function onboardingState(User $user): string
    {
        return $user->access_state;
    }

    public function isInactive(User $user, int $days = 14): bool
    {
        return $user->isInactive($days);
    }
}
