<?php

namespace App\Services;

use App\Models\EmailLog;
use App\Models\SMTPAccount;
use Carbon\Carbon;

class SendEngineService
{
    public function canSendNow(SMTPAccount $smtp): array
    {
        $todayCount = EmailLog::where('smtp_id', $smtp->id)
            ->whereDate('created_at', Carbon::today())
            ->count();

        $effectiveDaily = $this->effectiveDailyLimit($smtp);
        if ($todayCount >= $effectiveDaily) {
            return ['allowed' => false, 'delay' => 300, 'reason' => 'daily_limit_reached'];
        }

        $minuteCount = EmailLog::where('smtp_id', $smtp->id)
            ->where('created_at', '>=', now()->subSeconds(60))
            ->count();

        if ($minuteCount >= $smtp->per_minute_limit) {
            return ['allowed' => false, 'delay' => 60, 'reason' => 'per_minute_limit_reached'];
        }

        return ['allowed' => true, 'delay' => 0, 'reason' => null];
    }

    public function effectiveDailyLimit(SMTPAccount $smtp): int
    {
        if (!$smtp->warmup_enabled) {
            return $smtp->daily_limit;
        }

        $days = max(1, Carbon::parse($smtp->created_at)->startOfDay()->diffInDays(now()->startOfDay()) + 1);
        $warmup = $smtp->warmupRules()->where('warmup_day', $days)->value('daily_limit');

        return min($smtp->daily_limit, $warmup ?: $smtp->daily_limit);
    }

    public function createLog(array $data): EmailLog
    {
        return EmailLog::create($data);
    }
}
