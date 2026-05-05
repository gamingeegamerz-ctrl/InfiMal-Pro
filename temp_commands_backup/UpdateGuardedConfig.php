<?php

namespace App\Console\Commands;

use App\Services\ConfigGuardService;
use App\Services\ProductionSafetyService;
use Illuminate\Console\Command;

class UpdateGuardedConfig extends Command
{
    protected $signature = 'infimal:update-guarded-config {key} {value} {--user-id=} {--shadow}';

    protected $description = 'Apply guarded production config with cooldown/range validation';

    public function handle(ConfigGuardService $guard, ProductionSafetyService $safety): int
    {
        $key = (string) $this->argument('key');
        $value = (float) $this->argument('value');
        $userId = $this->option('user-id') ? (int) $this->option('user-id') : null;
        $shadow = (bool) $this->option('shadow');

        try {
            $result = $guard->apply($key, $value, $userId, $shadow);
            $severity = $shadow ? 'low' : 'medium';
            $safety->alert('Guarded config change '.($shadow ? 'shadow' : 'applied'), [
                'key' => $key,
                'result' => $result,
                'user_id' => $userId,
            ], $severity);
            $this->info(json_encode($result));
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $safety->alert('Guarded config rejected', ['key' => $key, 'error' => $e->getMessage(), 'user_id' => $userId], 'high');
            $this->error($e->getMessage());
            return self::FAILURE;
        }
    }
}
