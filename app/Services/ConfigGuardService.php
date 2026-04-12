<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ConfigGuardService
{
    private int $cooldownMinutes = 30;

    private array $safeRanges = [
        'warmup.daily_growth_percent' => [0, 30],
        'throttle.max_send_rate' => [10, 5000],
        'scaling.max_workers' => [1, 50],
    ];

    public function get(string $key, mixed $default = null): mixed
    {
        return Cache::get('guarded_config:'.$key, $default);
    }

    public function apply(string $key, mixed $value, ?int $userId, bool $shadow = false): array
    {
        $this->validateRange($key, $value);
        $this->enforceCooldown($key);

        $old = $this->get($key);
        $impact = $this->simulateImpact($key, $old, $value);

        $status = $shadow ? 'shadow' : 'applied';
        if (! $shadow) {
            Cache::put('guarded_config:'.$key, $value, now()->addDays(30));
            Cache::put('guarded_config:last_change:'.$key, now()->toIso8601String(), now()->addDay());
            Cache::put('guarded_config:rollback:'.$key, $old, now()->addDays(30));
        }

        DB::table('config_change_logs')->insert([
            'user_id' => $userId,
            'config_key' => $key,
            'old_value' => json_encode($old),
            'new_value' => json_encode($value),
            'status' => $status,
            'reason' => $impact['note'],
            'created_at' => now(),
        ]);

        return ['status' => $status, 'impact' => $impact, 'old' => $old, 'new' => $value];
    }

    public function rollback(string $key, string $reason, ?int $userId = null): void
    {
        $rollback = Cache::get('guarded_config:rollback:'.$key);
        Cache::put('guarded_config:'.$key, $rollback, now()->addDays(30));

        DB::table('config_change_logs')->insert([
            'user_id' => $userId,
            'config_key' => $key,
            'old_value' => json_encode($this->get($key)),
            'new_value' => json_encode($rollback),
            'status' => 'rolled_back',
            'reason' => $reason,
            'created_at' => now(),
        ]);
    }

    public function systemLimits(): array
    {
        return [
            'warmup_daily_growth_percent' => $this->get('warmup.daily_growth_percent', 20),
            'max_send_rate' => $this->get('throttle.max_send_rate', 1000),
            'max_workers' => $this->get('scaling.max_workers', 20),
        ];
    }

    private function enforceCooldown(string $key): void
    {
        $last = Cache::get('guarded_config:last_change:'.$key);
        if (! $last) {
            return;
        }

        if (now()->diffInMinutes($last) < $this->cooldownMinutes) {
            throw new InvalidArgumentException('Config change cooldown active for key: '.$key);
        }
    }

    private function validateRange(string $key, mixed $value): void
    {
        if (! isset($this->safeRanges[$key])) {
            throw new InvalidArgumentException('Config key is locked: '.$key);
        }

        [$min, $max] = $this->safeRanges[$key];
        if (! is_numeric($value) || $value < $min || $value > $max) {
            throw new InvalidArgumentException("Unsafe config value for {$key}. Allowed range: {$min}-{$max}");
        }
    }

    private function simulateImpact(string $key, mixed $old, mixed $new): array
    {
        $delta = is_numeric($old) && is_numeric($new) ? ($new - $old) : null;

        return [
            'delta' => $delta,
            'note' => 'Shadow simulation completed for '.$key,
            'risk' => is_numeric($delta) && abs($delta) > 20 ? 'medium' : 'low',
        ];
    }
}
