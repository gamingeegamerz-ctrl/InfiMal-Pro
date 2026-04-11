<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ReputationService
{
    private float $slowDownThreshold = 5.0;
    private float $resumeThreshold = 2.0;
    private int $stabilityMinutes = 10;

    public function applyBouncePenalty(?int $smtpId): void
    {
        $this->applyEventImpact($smtpId, 70.0);
    }

    public function applyComplaintPenalty(?int $smtpId): void
    {
        $this->applyEventImpact($smtpId, 40.0);
    }

    public function shouldThrottle(int $smtpId, float $bounceRate): bool
    {
        if ($bounceRate < $this->slowDownThreshold) {
            return false;
        }

        Cache::put("smtp:{$smtpId}:throttled_at", now(), now()->addHours(2));

        return true;
    }

    public function canRecover(int $smtpId, float $bounceRate): bool
    {
        if ($bounceRate > $this->resumeThreshold) {
            Cache::forget("smtp:{$smtpId}:stable_since");
            return false;
        }

        $stableSince = Cache::get("smtp:{$smtpId}:stable_since");
        if (! $stableSince) {
            Cache::put("smtp:{$smtpId}:stable_since", now(), now()->addHours(2));
            return false;
        }

        return now()->diffInMinutes($stableSince) >= $this->stabilityMinutes;
    }

    public function triggerFailsafeIfSpike(int $userId, ?int $campaignId, ?int $smtpId): void
    {
        $lastHourBounces = DB::table('email_logs')
            ->where('user_id', $userId)
            ->where('status', 'bounced')
            ->where('updated_at', '>=', now()->subHour())
            ->count();

        if ($lastHourBounces < 50) {
            return;
        }

        if ($smtpId) {
            DB::table('smtps')->where('id', $smtpId)->update(['is_active' => false, 'updated_at' => now()]);
        }

        if ($campaignId) {
            DB::table('campaigns')->where('id', $campaignId)->update(['status' => 'paused', 'updated_at' => now()]);
        }
    }

    private function applyEventImpact(?int $smtpId, float $eventScore): void
    {
        if (! $smtpId) {
            return;
        }

        $old = (float) DB::table('smtps')->where('id', $smtpId)->value('reputation_score');
        if ($old <= 0) {
            $old = 100.0;
        }

        $new = ($old * 0.8) + ($eventScore * 0.2);

        DB::table('smtps')->where('id', $smtpId)->update([
            'reputation_score' => round(max(0, min(100, $new)), 2),
            'updated_at' => now(),
        ]);
    }
}
