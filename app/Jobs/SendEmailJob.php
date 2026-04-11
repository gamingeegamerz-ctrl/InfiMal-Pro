<?php

namespace App\Jobs;

use App\Http\Controllers\TrackingController;
use App\Models\EmailJob;
use App\Models\EmailLog;
use App\Models\SMTPAccount;
use App\Models\SenderDomain;
use App\Services\AdaptiveThrottleService;
use App\Services\EmailFailureClassifier;
use App\Services\EmailReputationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;

    public function backoff(): array
    {
        return [30, 60, 120, 300];
    }

    public function __construct(public int $emailJobId)
    {
        $this->onQueue(config('infimal.queue.user_email_queue', 'user_email_jobs'));
    }

    public function handle(AdaptiveThrottleService $throttle, EmailReputationService $reputation): void
    {
        $emailJob = EmailJob::find($this->emailJobId);
        if (! $emailJob || in_array($emailJob->status, ['sent', 'bounced'], true)) {
            return;
        }

        $smtp = SMTPAccount::ownedBy($emailJob->user_id)
            ->where('is_active', true)
            ->where('validation_status', '!=', 'invalid')
            ->orderByDesc('is_default')
            ->first();

        if (! $smtp) {
            $emailJob->update(['status' => 'failed', 'error_message' => 'Active validated user SMTP not configured']);
            return;
        }

        $fromDomain = strtolower((string) substr(strrchr((string) $smtp->from_address, '@'), 1));
        if ($fromDomain !== '') {
            $domainVerified = SenderDomain::where('user_id', $emailJob->user_id)
                ->where('domain', $fromDomain)
                ->where('is_verified', true)
                ->exists();

            if (! $domainVerified) {
                $emailJob->update(['status' => 'failed', 'error_message' => 'Sender domain is not verified.']);
                return;
            }
        }

        $idempotencyKey = $emailJob->idempotency_key ?: hash('sha256', implode('|', [
            $emailJob->campaign_id ?: 0,
            strtolower((string) $emailJob->to_email),
            sha1((string) $emailJob->subject.(string) $emailJob->body),
        ]));

        if ($emailJob->idempotency_key !== $idempotencyKey) {
            $emailJob->update(['idempotency_key' => $idempotencyKey]);
        }

        $existingDelivered = EmailLog::where('idempotency_key', $idempotencyKey)
            ->whereIn('status', ['sent', 'delivered'])
            ->exists();

        if ($existingDelivered) {
            $emailJob->update(['status' => 'sent', 'sent_at' => now(), 'smtp_id' => $smtp->id]);
            return;
        }

        $messageId = 'user-'.$emailJob->id.'-'.Str::uuid();

        $provider = strtolower((string) substr(strrchr((string) $emailJob->to_email, '@'), 1));

        $emailLog = EmailLog::create([
            'user_id' => $emailJob->user_id,
            'campaign_id' => $emailJob->campaign_id,
            'smtp_id' => $smtp->id,
            'to_email' => $emailJob->to_email,
            'recipient_email' => $emailJob->to_email,
            'subject' => $emailJob->subject,
            'status' => 'pending',
            'provider' => $provider,
            'message_id' => $messageId,
            'idempotency_key' => $idempotencyKey,
        ]);

        $delayMs = $throttle->recommendedDelayMs((float) $smtp->bounce_rate, (float) $smtp->complaint_rate, 0);
        usleep($delayMs * 1000);

        $htmlBody = TrackingController::processEmailContent($emailJob->html ?: nl2br(e($emailJob->body)), $emailLog->id);

        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.host' => $smtp->host,
            'mail.mailers.smtp.port' => $smtp->port,
            'mail.mailers.smtp.encryption' => $smtp->encryption === 'none' ? null : $smtp->encryption,
            'mail.mailers.smtp.username' => $smtp->username,
            'mail.mailers.smtp.password' => $smtp->password,
            'mail.from.address' => $smtp->from_address,
            'mail.from.name' => $smtp->from_name ?: 'InfiMal',
        ]);

        try {
            Mail::html($htmlBody, function ($message) use ($emailJob, $messageId): void {
                $message->to($emailJob->to_email)
                    ->subject($emailJob->subject)
                    ->getHeaders()
                    ->addTextHeader('Message-ID', $messageId);
            });

            $emailJob->update(['status' => 'sent', 'sent_at' => now(), 'smtp_id' => $smtp->id]);
            $emailLog->update(['status' => 'sent', 'sent_at' => now()]);
            $reputation->recordEvent($emailLog->id, 'sent');
        } catch (\Throwable $e) {
            $type = EmailFailureClassifier::classify($e->getMessage());

            if ($type === 'temporary') {
                $emailLog->update(['status' => 'failed', 'error_message' => substr($e->getMessage(), 0, 1000)]);
                $reputation->recordEvent($emailLog->id, 'soft_bounce', $e->getMessage());
                $this->release($this->backoff()[min($this->attempts() - 1, 3)]);
                return;
            }

            if ($type === 'hard_bounce') {
                $emailJob->update(['status' => 'bounced', 'failed_at' => now(), 'error_message' => substr($e->getMessage(), 0, 1000)]);
                $emailLog->update(['status' => 'bounced', 'bounced_at' => now(), 'error_message' => substr($e->getMessage(), 0, 1000)]);
                $reputation->recordEvent($emailLog->id, 'hard_bounce', $e->getMessage());
                return;
            }

            if ($type === 'spam') {
                $emailJob->update(['status' => 'failed', 'failed_at' => now(), 'error_message' => substr($e->getMessage(), 0, 1000)]);
                $emailLog->update(['status' => 'failed', 'complained_at' => now(), 'error_message' => substr($e->getMessage(), 0, 1000)]);
                $reputation->recordEvent($emailLog->id, 'complaint', $e->getMessage());
                return;
            }

            throw $e;
        }
    }

    public function failed(\Throwable $e): void
    {
        Log::channel('security')->error('Queued email permanently failed', [
            'email_job_id' => $this->emailJobId,
            'error' => $e->getMessage(),
        ]);

        EmailJob::where('id', $this->emailJobId)->update([
            'status' => 'failed',
            'error_message' => substr($e->getMessage(), 0, 1000),
            'failed_at' => now(),
        ]);
    }
}
