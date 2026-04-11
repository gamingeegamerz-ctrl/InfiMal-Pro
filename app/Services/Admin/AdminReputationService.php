<?php

namespace App\Services\Admin;

use App\Models\Admin\AdminIpPool;
use App\Models\Admin\AdminReputationScore;

class AdminReputationService
{
    public function refresh(AdminIpPool $ipPool, string $domain, string $provider = 'other'): AdminReputationScore
    {
        $success = $this->clamp((float) ($ipPool->success_rate ?? 0.5));
        $lowBounce = 1 - $this->clamp((float) ($ipPool->bounce_rate ?? 0));
        $lowComplaint = 1 - $this->clamp((float) ($ipPool->complaint_rate ?? 0));
        $engagement = $this->clamp((float) ($ipPool->engagement_score ?? 0));
        $lowUsage = 1 - $this->clamp($ipPool->daily_limit > 0 ? ($ipPool->sent_today / $ipPool->daily_limit) : 0);

        $deliverabilityScore = $this->clamp(
            (0.3 * $success) +
            (0.25 * $lowBounce) +
            (0.2 * $lowComplaint) +
            (0.15 * $engagement) +
            (0.1 * $lowUsage)
        );

        $record = AdminReputationScore::create([
            'ip_pool_id' => $ipPool->id,
            'sending_domain' => $domain,
            'reputation_score' => $deliverabilityScore,
            'success_rate' => $success,
            'bounce_rate' => 1 - $lowBounce,
            'complaint_rate' => 1 - $lowComplaint,
            'deliverability_score' => $deliverabilityScore,
            'calculated_at' => now(),
        ]);

        $providerColumn = match ($provider) {
            'gmail' => 'gmail_score',
            'outlook' => 'outlook_score',
            'yahoo' => 'yahoo_score',
            default => null,
        };

        $updates = ['success_rate' => $success];
        if ($providerColumn) {
            $updates[$providerColumn] = $deliverabilityScore;
        }

        $ipPool->forceFill($updates)->save();

        return $record;
    }

    private function clamp(float $value): float
    {
        return max(0, min(1, $value));
    }
}
