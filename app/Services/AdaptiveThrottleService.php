<?php

namespace App\Services;

class AdaptiveThrottleService
{
    public function recommendedDelayMs(float $bounceRate, float $complaintRate, int $tempFailures): int
    {
        $base = 80;

        if ($bounceRate > 0.05) {
            $base += 300;
        }

        if ($complaintRate > 0.003) {
            $base += 400;
        }

        if ($tempFailures > 10) {
            $base += min(500, $tempFailures * 20);
        }

        return min(2000, max(50, $base));
    }

    public function shouldPause(float $bounceRate, float $complaintRate): bool
    {
        return $bounceRate >= 0.08 || $complaintRate >= 0.005;
    }
}
