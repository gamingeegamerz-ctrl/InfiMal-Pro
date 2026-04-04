<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\EmailLog;
use App\Models\Subscriber;
use App\Models\User;

class UsageLimitService
{
    public function campaignLimitExceeded(User $user): bool
    {
        $limit = (int) config('infimal.limits.campaigns_per_day', 10);

        return Campaign::where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->count() >= $limit;
    }

    public function emailLimitExceeded(User $user): bool
    {
        $limit = (int) config('infimal.limits.emails_per_day', 5000);

        return EmailLog::where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->count() >= $limit;
    }

    public function subscriberLimitExceeded(User $user): bool
    {
        $limit = (int) config('infimal.limits.subscribers_per_user', 50000);

        return Subscriber::where('user_id', $user->id)->count() >= $limit;
    }
}
