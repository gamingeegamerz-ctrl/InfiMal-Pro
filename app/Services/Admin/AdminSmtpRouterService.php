<?php

namespace App\Services\Admin;

use App\Models\Admin\AdminIpPool;
use App\Models\Admin\AdminSmtpNode;
use Illuminate\Support\Collection;

class AdminSmtpRouterService
{
    public function __construct(
        private readonly AdminWarmupService $warmupService,
        private readonly AdminReputationService $reputationService,
    ) {
    }

    /**
     * Provider-aware weighted score:
     * 0.3 success + 0.25 low_bounce + 0.2 low_complaint + 0.15 engagement + 0.1 low_usage.
     */
    public function pickBestIpForRecipient(string $sendingDomain, string $recipientEmail): ?AdminIpPool
    {
        $provider = $this->providerFromEmail($recipientEmail);

        $candidates = AdminIpPool::query()
            ->where('status', 'active')
            ->whereHas('node', fn ($q) => $q->where('status', 'active')->where('sending_domain', $sendingDomain))
            ->with('node')
            ->get()
            ->filter(fn (AdminIpPool $ip) => $ip->sent_today < max(1, $ip->daily_limit));

        if ($candidates->isEmpty()) {
            return null;
        }

        $scored = $candidates->map(function (AdminIpPool $ip) use ($provider) {
            $dailyLimit = max(1, $ip->daily_limit);
            $lowUsage = max(0, 1 - ($ip->sent_today / $dailyLimit));
            $lowBounce = max(0, 1 - (float) $ip->bounce_rate);
            $lowComplaint = max(0, 1 - (float) $ip->complaint_rate);
            $successRate = (float) ($ip->success_rate ?? 0.5);
            $engagement = (float) ($ip->engagement_score ?? 0);

            $providerBias = match ($provider) {
                'gmail' => (float) $ip->gmail_score,
                'outlook' => (float) $ip->outlook_score,
                'yahoo' => (float) $ip->yahoo_score,
                default => 0.5,
            };

            $score = (0.3 * $successRate)
                + (0.25 * $lowBounce)
                + (0.2 * $lowComplaint)
                + (0.15 * $engagement)
                + (0.1 * $lowUsage)
                + (0.15 * $providerBias);

            $warmupLimit = $this->warmupService->resolveDailyTarget($ip, $ip->node->sending_domain, $provider);
            if ($ip->sent_today >= $warmupLimit) {
                $score -= 0.5;
            }

            $jitter = mt_rand(-20, 20) / 1000;

            return [
                'ip' => $ip,
                'score' => $score + $jitter,
            ];
        })->sortByDesc('score')->values();

        /** @var AdminIpPool $picked */
        $picked = $scored->first()['ip'];
        $this->reputationService->refresh($picked, $sendingDomain, $provider);

        return $picked;
    }

    public function resolveNodePort(AdminSmtpNode $node, ?AdminIpPool $ipPool = null): int
    {
        $preferred = $node->preferred_port;
        $ports = collect($node->supported_ports ?: [587, 2525, 25]);
        $metrics = collect($ipPool?->port_performance ?: []);

        $ordered = $ports
            ->sortByDesc(function (int $port) use ($metrics) {
                $m = $metrics->get((string) $port, ['attempts' => 0, 'success' => 0]);
                $attempts = max(1, (int) ($m['attempts'] ?? 0));
                return ((int) ($m['success'] ?? 0)) / $attempts;
            })
            ->values();

        $fallbackOrder = collect([587, 2525, 25])->filter(fn (int $p) => $ordered->contains($p));
        $ordered = $fallbackOrder->merge($ordered)->unique()->values();

        if ($preferred && $ordered->contains($preferred)) {
            $ordered = collect([$preferred])->merge($ordered->reject(fn (int $p) => $p === $preferred))->values();
        }

        $selected = (int) $ordered->first();

        if ($selected !== (int) $preferred) {
            $node->forceFill(['preferred_port' => $selected])->save();
        }

        return $selected;
    }

    public function notePortResult(AdminIpPool $ipPool, int $port, bool $success): void
    {
        $performance = $ipPool->port_performance ?? [];
        $current = $performance[(string) $port] ?? ['attempts' => 0, 'success' => 0];

        $current['attempts']++;
        if ($success) {
            $current['success']++;
        }

        $performance[(string) $port] = $current;
        $ipPool->forceFill(['port_performance' => $performance])->save();
    }

    private function providerFromEmail(string $recipientEmail): string
    {
        $domain = strtolower((string) substr(strrchr($recipientEmail, '@'), 1));

        return match (true) {
            in_array($domain, ['gmail.com', 'googlemail.com'], true) => 'gmail',
            in_array($domain, ['outlook.com', 'hotmail.com', 'live.com'], true) => 'outlook',
            in_array($domain, ['yahoo.com', 'ymail.com'], true) => 'yahoo',
            default => 'other',
        };
    }
}
