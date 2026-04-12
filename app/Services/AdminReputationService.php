<?php

namespace App\Services;

use App\Models\AdminIpPool;
use App\Models\AdminReputationScore;

class AdminReputationService
{
    public function calculateCompositeScore(AdminIpPool $ip): float
    {
        $lowUsage = $this->lowUsageFactor($ip);

        return (0.4 * $ip->reputation_score)
            + (0.2 * $ip->success_rate)
            + (0.2 * $lowUsage)
            + (0.2 * (1 - $ip->bounce_rate));
    }

    public function snapshot(AdminIpPool $ip, string $provider = 'global'): AdminReputationScore
    {
        $score = $this->calculateCompositeScore($ip);

        return AdminReputationScore::create([
            'admin_ip_pool_id' => $ip->id,
            'sending_domain' => $ip->node?->sending_domain,
            'provider' => $provider,
            'reputation_score' => $ip->reputation_score,
            'success_rate' => $ip->success_rate,
            'bounce_rate' => $ip->bounce_rate,
            'complaint_rate' => 0,
            'low_usage_factor' => $this->lowUsageFactor($ip),
            'composite_score' => $score,
            'recorded_at' => now(),
        ]);
    }

    private function lowUsageFactor(AdminIpPool $ip): float
    {
        $limit = max(1, $ip->daily_limit);
        $usage = min(1, $ip->daily_sent / $limit);

        return 1 - $usage;
    }
}
