<?php

namespace App\Services;

use App\Services\ProductionSafetyService;
use App\Models\EmailJob;
use App\Models\EmailLog;
use App\Models\SMTPAccount;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class SchedulerService
{
    public function __construct(private readonly ProductionSafetyService $safety)
    {
    }
    private int $maxJobsPerRun = 5000;
    private int $windowMinutes = 10;
    private int $maxDelayHours = 24;

    public function scheduleCampaignJobs(Collection $jobs, SMTPAccount $smtp): void
    {
        if ($jobs->isEmpty() || $this->safety->isGlobalPause()) {
            return;
        }

        $userId = (int) $jobs->first()->user_id;
        $warmup = new WarmupManager($userId);
        $dailyLimit = max(20, min($warmup->getTodayWarmupLimit(), $smtp->daily_limit ?: PHP_INT_MAX));
        $dailyLimit = (int) floor($dailyLimit * $this->safety->safeModeRateFactor());
        if ($this->safety->isSafeMode()) {
            $dailyLimit = (int) floor($dailyLimit * 0.8);
        }
        $todaySent = $this->todaySentCount($userId);
        $remainingToday = max(0, $dailyLimit - $todaySent);

        $queuedBacklog = EmailJob::query()->where('user_id', $userId)->where('status', 'queued')->count();
        if ($queuedBacklog > 50000) {
            $remainingToday = max(20, (int) floor($remainingToday * 0.7));
        }

        $intervalSeconds = $this->paceIntervalSeconds($dailyLimit);
        $slot = now();
        $windowEnd = now()->copy()->addMinutes($this->windowMinutes);
        $scheduledToday = 0;
        $scheduledThisRun = 0;

        foreach ($jobs as $job) {
            $job->priority = $this->resolvePriority($job);
            $job->expires_at = now()->addHours($this->maxDelayHours);

            if ($scheduledToday >= $remainingToday) {
                $slot = $this->nextDayStartSlot($slot);
                $scheduledToday = 0;
            }

            if ($scheduledThisRun >= $this->maxJobsPerRun || $slot->greaterThan($windowEnd)) {
                $job->scheduled_at = $windowEnd->copy()->addMinutes(1)->addSeconds(random_int(5, 30));
                $job->status = 'queued';
                $job->save();
                continue;
            }

            $job->scheduled_at = $slot->copy()->addSeconds(random_int(5, 30));
            $job->status = 'queued';
            $job->save();

            $scheduledToday++;
            $scheduledThisRun++;
            $slot->addSeconds($intervalSeconds);
        }
    }

    public function enforceBeforeSend(EmailJob $job, SMTPAccount $smtp): bool
    {
        if ($this->safety->isGlobalPause()) {
            return false;
        }

        if ($job->scheduled_at && $job->scheduled_at->isFuture()) {
            return false;
        }

        if ($job->expires_at && $job->expires_at->isPast()) {
            $job->update([
                'status' => 'failed',
                'error_message' => 'Job expired before delivery window',
                'failed_at' => now(),
            ]);

            return false;
        }

        $warmup = new WarmupManager($job->user_id);
        $dailyLimit = max(20, min($warmup->getTodayWarmupLimit(), $smtp->daily_limit ?: PHP_INT_MAX));
        $dailyLimit = (int) floor($dailyLimit * $this->safety->safeModeRateFactor());

        if ($job->scheduled_at && $job->scheduled_at->lt(now()->subHours($this->maxDelayHours))) {
            $dailyLimit = (int) ceil($dailyLimit * 1.2);
        }

        $recoveryKey = 'warmup:recovery:'.(int) $job->user_id;
        $recoveryFactor = (float) Cache::get($recoveryKey, 1.0);
        $dailyLimit = (int) floor($dailyLimit * $recoveryFactor);

        $todaySent = $this->todaySentCount($job->user_id);
        if ($todaySent >= $dailyLimit) {
            Cache::put($recoveryKey, max(0.6, $recoveryFactor * 0.9), now()->addMinutes(30));

            $job->forceFill([
                'retry_at' => $this->nextAvailableSlot($dailyLimit),
                'status' => 'queued',
            ])->save();

            return false;
        }

        Cache::put($recoveryKey, min(1.0, $recoveryFactor + 0.05), now()->addMinutes(30));

        return true;
    }

    public function dueQueuedJobs(int $limit = 500): Collection
    {
        return EmailJob::query()
            ->queued()
            ->readyToSend()
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->orderBy('priority')
            ->orderBy('scheduled_at')
            ->limit($limit)
            ->get();
    }

    private function resolvePriority(EmailJob $job): int
    {
        if (is_null($job->campaign_id)) {
            return 1;
        }

        if ($job->created_at && $job->created_at->gte(now()->subHours(2))) {
            return 2;
        }

        return 3;
    }

    private function todaySentCount(int $userId): int
    {
        return EmailLog::query()
            ->where('user_id', $userId)
            ->whereDate('created_at', today())
            ->whereIn('status', ['sent', 'delivered'])
            ->count();
    }

    private function nextAvailableSlot(int $dailyLimit): Carbon
    {
        $intervalSeconds = $this->paceIntervalSeconds($dailyLimit);

        return now()->addSeconds(max(60, $intervalSeconds));
    }

    private function nextDayStartSlot(Carbon $currentSlot): Carbon
    {
        return $currentSlot->copy()->addDay()->startOfDay()->addHours(8);
    }

    private function paceIntervalSeconds(int $dailyLimit): int
    {
        return match (true) {
            $dailyLimit <= 50 => 180,
            $dailyLimit <= 100 => 120,
            $dailyLimit <= 500 => 30,
            default => 5,
        };
    }
}
