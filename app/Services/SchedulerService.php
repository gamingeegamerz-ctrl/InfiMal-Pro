<?php

namespace App\Services;

use App\Jobs\SendCampaignEmailJob;
use App\Models\EmailJob;
use App\Models\EmailLog;
use App\Models\SMTPAccount;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class SchedulerService
{
    public function scheduleCampaignJobs(Collection $jobs, SMTPAccount $smtp): void
    {
        if ($jobs->isEmpty()) {
            return;
        }

        $userId = (int) $jobs->first()->user_id;
        $warmup = new WarmupManager($userId);
        $dailyLimit = max(20, min($warmup->getTodayWarmupLimit(), $smtp->daily_limit ?: PHP_INT_MAX));
        $todaySent = $this->todaySentCount($userId);
        $remainingToday = max(0, $dailyLimit - $todaySent);

        $intervalSeconds = $this->paceIntervalSeconds($dailyLimit);
        $slot = now();
        $scheduledToday = 0;

        foreach ($jobs as $job) {
            if ($scheduledToday >= $remainingToday) {
                $slot = $this->nextDayStartSlot($slot);
                $scheduledToday = 0;
            }

            $job->forceFill([
                'scheduled_at' => $slot->copy()->addSeconds(random_int(5, 30)),
                'status' => 'queued',
            ])->save();

            SendCampaignEmailJob::dispatch($job->id)->onQueue('emails');

            $scheduledToday++;
            $slot->addSeconds($intervalSeconds);
        }
    }

    public function enforceBeforeSend(EmailJob $job, SMTPAccount $smtp): bool
    {
        if ($job->scheduled_at && $job->scheduled_at->isFuture()) {
            return false;
        }

        $warmup = new WarmupManager($job->user_id);
        $dailyLimit = max(20, min($warmup->getTodayWarmupLimit(), $smtp->daily_limit ?: PHP_INT_MAX));
        $todaySent = $this->todaySentCount($job->user_id);

        if ($todaySent >= $dailyLimit) {
            $job->forceFill([
                'scheduled_at' => $this->nextAvailableSlot($dailyLimit),
                'retry_at' => null,
                'status' => 'queued',
            ])->save();

            return false;
        }

        return true;
    }

    public function dueQueuedJobs(int $limit = 500): Collection
    {
        return EmailJob::query()
            ->queued()
            ->readyToSend()
            ->orderBy('scheduled_at')
            ->limit($limit)
            ->get();
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
