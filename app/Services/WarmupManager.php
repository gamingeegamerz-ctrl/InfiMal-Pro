<?php

namespace App\Services;

use App\Models\EmailUsage;
use Carbon\Carbon;

class WarmupManager
{
    protected int $userId;

    /**
     * Warm-up limits per day (backend-only)
     * Day count is based on first sending activity
     */
    protected array $warmupSchedule = [
        1  => 500,
        2  => 1000,
        3  => 2000,
        4  => 4000,
        5  => 7000,
        7  => 10000,
        10 => 20000,
        14 => 50000,
        21 => 100000, // max unlock (still controlled by TrustManager)
    ];

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * Check if user can send email right now (warm-up safe)
     */
    public function canSendNow(): bool
    {
        $todayUsage = $this->getTodayUsage();
        $allowed    = $this->getTodayWarmupLimit();

        return $todayUsage < $allowed;
    }

    /**
     * Get today's warm-up limit based on account age
     */
    public function getTodayWarmupLimit(): int
    {
        $daysSinceFirstSend = $this->getDaysSinceFirstSend();

        foreach ($this->warmupSchedule as $day => $limit) {
            if ($daysSinceFirstSend <= $day) {
                return $limit;
            }
        }

        // After warm-up period ends
        return end($this->warmupSchedule);
    }

    /**
     * Get how many emails user sent today
     */
    public function getTodayUsage(): int
    {
        $today = Carbon::today()->toDateString();

        return (int) EmailUsage::where('user_id', $this->userId)
            ->where('date', $today)
            ->value('sent_count');
    }

    /**
     * Calculate days since user started sending emails
     */
    protected function getDaysSinceFirstSend(): int
    {
        $first = EmailUsage::where('user_id', $this->userId)
            ->orderBy('date', 'asc')
            ->value('date');

        if (!$first) {
            return 1; // New user
        }

        return Carbon::parse($first)->diffInDays(Carbon::today()) + 1;
    }
}
