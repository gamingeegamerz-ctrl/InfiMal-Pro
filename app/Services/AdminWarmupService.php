<?php

namespace App\Services;

use App\Models\AdminIpPool;
use App\Models\AdminWarmupLog;

class AdminWarmupService
{
    public function targetVolume(AdminIpPool $ip, float $bounceRate = 0): int
    {
        $day = max(1, $ip->warmup_day);
        $base = match ($day) {
            1 => 50,
            2 => 150,
            3 => 400,
            4 => 1000,
            default => min($ip->daily_limit, (int) round($ip->daily_limit * 0.85)),
        };

        if ($bounceRate > 0.05) {
            return (int) max(25, floor($base * 0.5));
        }

        if ($bounceRate < 0.02 && $day >= 5) {
            return (int) min($ip->daily_limit, floor($base * 1.2));
        }

        return min($base, $ip->daily_limit);
    }

    public function log(AdminIpPool $ip, int $actualVolume, float $bounceRate, float $complaintRate = 0): AdminWarmupLog
    {
        $target = $this->targetVolume($ip, $bounceRate);

        $log = AdminWarmupLog::create([
            'admin_ip_pool_id' => $ip->id,
            'sending_domain' => (string) $ip->node?->sending_domain,
            'warmup_day' => $ip->warmup_day,
            'target_volume' => $target,
            'actual_volume' => $actualVolume,
            'bounce_rate' => $bounceRate,
            'complaint_rate' => $complaintRate,
            'status' => $actualVolume <= $target ? 'within_limit' : 'throttled',
            'notes' => $bounceRate > 0.05 ? 'Bounce spike detected, warm-up slowed.' : 'Warm-up steady.',
            'logged_on' => now()->toDateString(),
        ]);

        $ip->warmup_day = $bounceRate > 0.05 ? max(1, $ip->warmup_day - 1) : $ip->warmup_day + 1;
        $ip->save();

        return $log;
    }
}
