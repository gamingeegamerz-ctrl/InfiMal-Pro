<?php

namespace App\Services;

use App\Models\EmailLog;
use App\Models\SMTPAccount;
use Illuminate\Support\Facades\DB;

class EmailReputationService
{
    public function updateForSmtp(SMTPAccount $smtp): void
    {
        $query = EmailLog::query()->where('smtp_id', $smtp->id);
        $total = max(1, $query->count());

        $success = (clone $query)->whereIn('status', ['sent', 'delivered'])->count() / $total;
        $bounce = (clone $query)->where('status', 'bounced')->count() / $total;
        $complaint = (clone $query)->whereNotNull('complained_at')->count() / $total;
        $clicks = (clone $query)->where('clicked', true)->count();
        $replies = (clone $query)->whereNotNull('replied_at')->count();
        $engagement = min(1, (($clicks * 1.2) + ($replies * 2.0)) / $total);
        $usage = min(1, $smtp->daily_limit > 0 ? ($smtp->sent_today / $smtp->daily_limit) : 0);
        $lowUsage = 1 - $usage;

        $score = (0.3 * $success)
            + (0.25 * (1 - min(1, $bounce)))
            + (0.2 * (1 - min(1, $complaint)))
            + (0.15 * $engagement)
            + (0.1 * $lowUsage);

        $providerScores = $this->providerScores($smtp->id);

        $smtp->forceFill([
            'success_rate' => $success,
            'bounce_rate' => $bounce,
            'complaint_rate' => $complaint,
            'engagement_score' => $engagement,
            'reputation_score' => (int) round($score * 100),
            'gmail_score' => $providerScores['gmail'],
            'outlook_score' => $providerScores['outlook'],
            'yahoo_score' => $providerScores['yahoo'],
        ])->save();
    }

    private function providerScores(int $smtpId): array
    {
        $domains = [
            'gmail' => ['gmail.com', 'googlemail.com'],
            'outlook' => ['outlook.com', 'hotmail.com', 'live.com'],
            'yahoo' => ['yahoo.com', 'ymail.com'],
        ];

        $scores = [];

        foreach ($domains as $provider => $providerDomains) {
            $base = EmailLog::query()->where('smtp_id', $smtpId)
                ->where(function ($q) use ($providerDomains): void {
                    foreach ($providerDomains as $d) {
                        $q->orWhere('recipient_email', 'like', '%@'.$d);
                    }
                });

            $count = $base->count();
            if ($count === 0) {
                $scores[$provider] = 0.5;
                continue;
            }

            $success = (clone $base)->whereIn('status', ['sent', 'delivered'])->count() / $count;
            $bounce = (clone $base)->where('status', 'bounced')->count() / $count;
            $complaint = (clone $base)->whereNotNull('complained_at')->count() / $count;

            $scores[$provider] = max(0, min(1, (0.5 * $success) + (0.3 * (1 - $bounce)) + (0.2 * (1 - $complaint))));
        }

        return $scores;
    }

    public function recordEvent(int $emailLogId, string $eventType, ?string $reason = null): void
    {
        $log = EmailLog::find($emailLogId);
        if (! $log) {
            return;
        }

        DB::table('email_events')->insert([
            'campaign_id' => $log->campaign_id,
            'user_id' => $log->user_id,
            'email' => $log->recipient_email ?? $log->to_email,
            'event_type' => $eventType,
            'smtp' => (string) $log->smtp_id,
            'reason' => $reason,
            'payload' => json_encode(['email_log_id' => $log->id]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($log->smtp_id) {
            $smtp = SMTPAccount::find($log->smtp_id);
            if ($smtp) {
                $this->updateForSmtp($smtp);
            }
        }
    }
}
