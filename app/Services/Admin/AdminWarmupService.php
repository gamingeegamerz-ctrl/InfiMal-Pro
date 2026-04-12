<?php

namespace App\Services\Admin;

use App\Models\Admin\AdminIpPool;
use App\Models\Admin\AdminWarmupLog;

class AdminWarmupService
{
    public function resolveDailyTarget(AdminIpPool $ipPool, string $domain, string $provider = 'other'): int
    {
        $days = max(1, (int) $ipPool->created_at?->startOfDay()->diffInDays(now()->startOfDay()) + 1);

        // 2-6 week controlled warmup curve.
        $week = (int) ceil($days / 7);
        $base = match (true) {
            $week <= 1 => 50,
            $week === 2 => 150,
            $week === 3 => 400,
            $week === 4 => 1000,
            $week === 5 => 2500,
            default => 5000,
        };

        $latest = AdminWarmupLog::where('ip_pool_id', $ipPool->id)
            ->where('sending_domain', $domain)
            ->latest('id')
            ->first();

        if (! $latest) {
            return min($base, max(1, $ipPool->daily_limit));
        }

        $engagement = max(0, min(1, (float) (1 - (($latest->bounce_rate * 4) + ($latest->complaint_rate * 10)))));
        $growthFactor = 1 + (0.05 + (0.15 * $engagement)); // max +20% daily change.

        if ((float) $latest->bounce_rate > 0.04 || (float) $latest->complaint_rate > 0.0025) {
            return max(50, (int) floor($latest->target_volume * 0.7));
        }

        // Provider-specific caution.
        $providerCap = match ($provider) {
            'outlook' => 0.8,
            'gmail' => 0.9,
            default => 1,
        };

        $next = (int) floor($latest->target_volume * $growthFactor * $providerCap);

        return min(max(50, $next), max(100, $ipPool->daily_limit));
    }

    public function log(AdminIpPool $ipPool, string $domain, int $targetVolume, int $actualSent, float $bounceRate, float $complaintRate): AdminWarmupLog
    {
        return AdminWarmupLog::create([
            'ip_pool_id' => $ipPool->id,
            'sending_domain' => $domain,
            'warmup_day' => max(1, (int) $ipPool->created_at?->startOfDay()->diffInDays(now()->startOfDay()) + 1),
            'target_volume' => $targetVolume,
            'actual_sent' => $actualSent,
            'bounce_rate' => $bounceRate,
            'complaint_rate' => $complaintRate,
            'status' => $actualSent <= $targetVolume ? 'within_limit' : 'over_target',
            'notes' => 'Adaptive warmup window active (2-6 weeks).',
        ]);
    }
}
