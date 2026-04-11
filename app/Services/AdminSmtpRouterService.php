<?php

namespace App\Services;

use App\Models\AdminIpPool;
use App\Models\AdminSmtpNode;
use Illuminate\Support\Facades\Config;

class AdminSmtpRouterService
{
    public function __construct(
        private readonly AdminReputationService $reputationService,
        private readonly AdminWarmupService $warmupService,
    ) {
    }

    public function pickBestIp(string $provider = 'global'): ?AdminIpPool
    {
        $candidates = AdminIpPool::query()
            ->where('status', 'active')
            ->whereHas('node', fn ($q) => $q->where('status', 'active')->where('is_active', true))
            ->get();

        if ($candidates->isEmpty()) {
            return null;
        }

        $scored = $candidates->map(function (AdminIpPool $ip) use ($provider) {
            $base = $this->reputationService->calculateCompositeScore($ip);
            $randomized = $base + (mt_rand(-3, 3) / 100);

            $this->reputationService->snapshot($ip, $provider);

            return ['ip' => $ip, 'score' => $randomized];
        })->sortByDesc('score')->values();

        return $scored->first()['ip'];
    }

    public function applySmtpConfig(AdminIpPool $ip): int
    {
        /** @var AdminSmtpNode $node */
        $node = $ip->node;
        $port = $this->resolveWorkingPort($node);

        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp.host', $node->hostname);
        Config::set('mail.mailers.smtp.port', $port);
        Config::set('mail.mailers.smtp.encryption', 'tls');
        Config::set('mail.from.address', 'admin@'.$node->sending_domain);
        Config::set('mail.from.name', 'INFIMAL Admin Engine');

        $node->last_port = $port;
        $node->save();

        return $port;
    }

    public function providerDelaySeconds(string $recipientEmail, float $bounceRate = 0, bool $complaintDetected = false): int
    {
        $domain = strtolower(substr(strrchr($recipientEmail, '@') ?: '', 1));

        $base = match (true) {
            str_contains($domain, 'gmail') => 2,
            str_contains($domain, 'outlook'), str_contains($domain, 'hotmail'), str_contains($domain, 'live') => 4,
            str_contains($domain, 'yahoo') => 3,
            default => 1,
        };

        if ($bounceRate > 0.05 || $complaintDetected) {
            return $base * 3;
        }

        return $base;
    }

    public function allowedVolume(AdminIpPool $ip, float $bounceRate): int
    {
        return $this->warmupService->targetVolume($ip, $bounceRate);
    }

    private function resolveWorkingPort(AdminSmtpNode $node): int
    {
        $supported = $node->supported_ports ?: [587, 2525, 25];
        $priority = [587, 2525, 25];

        foreach ($priority as $port) {
            if (in_array($port, $supported, true)) {
                return $port;
            }
        }

        return (int) ($supported[0] ?? 587);
    }
}
