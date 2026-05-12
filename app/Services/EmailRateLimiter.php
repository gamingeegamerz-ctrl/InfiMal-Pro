<?php

namespace App\Services;

use App\Jobs\SendSesEmailJob;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class EmailRateLimiter
{
    public const PER_MINUTE = 10;
    public const PER_HOUR = 200;

    public function canSendNow(User $user, int $count = 1): bool
    {
        if ($user->suspended) {
            return false;
        }

        if (($user->today_sent + $count) > $user->daily_limit) {
            return false;
        }

        return $this->currentCount($this->minuteKey($user->id)) + $count <= self::PER_MINUTE
            && $this->currentCount($this->hourKey($user->id)) + $count <= self::PER_HOUR;
    }

    public function registerSend(User $user, int $count = 1): void
    {
        $this->incrementWindow($this->minuteKey($user->id), $count, now()->addMinute());
        $this->incrementWindow($this->hourKey($user->id), $count, now()->addHour());

        DB::table('users')->where('id', $user->id)->increment('today_sent', $count);
    }

    public function queueForLater(array $payload, int $delaySeconds = 300): void
    {
        SendSesEmailJob::dispatch($payload)->delay(now()->addSeconds($delaySeconds));
    }

    private function currentCount(string $key): int
    {
        return (int) Cache::get($key, 0);
    }

    private function incrementWindow(string $key, int $count, \DateTimeInterface $expiresAt): void
    {
        if (! Cache::has($key)) {
            Cache::put($key, 0, $expiresAt);
        }

        Cache::increment($key, $count);
    }

    private function minuteKey(int $userId): string
    {
        return 'ses-send-minute:'.$userId.':'.now()->format('YmdHi');
    }

    private function hourKey(int $userId): string
    {
        return 'ses-send-hour:'.$userId.':'.now()->format('YmdH');
    }
}
