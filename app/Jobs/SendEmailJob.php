<?php

namespace App\Jobs;

use App\Http\Controllers\TrackingController;
use App\Models\EmailJob;
use App\Models\EmailLog;
use App\Models\SMTPAccount;
use App\Models\SenderDomain;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
        $this->onQueue('emails');
    }

    public function handle(): void
    {
        $emailJob = EmailJob::find($this->emailJobId);
        if (! $emailJob || in_array($emailJob->status, ['sent', 'bounced'], true)) {
            return;
        }

        $smtp = SMTPAccount::ownedBy($emailJob->user_id)
            ->where('is_active', true)
            ->orderByDesc('is_default')
            ->first();

        if (! $smtp) {
            $emailJob->update(['status' => 'failed', 'error_message' => 'Active SMTP not configured']);
            return;
        }

        $messageId = 'job-'.$emailJob->id;

        $existingDelivered = EmailLog::where('message_id', $messageId)->whereIn('status', ['sent', 'delivered'])->exists();
        if ($existingDelivered) {
            $emailJob->update(['status' => 'sent', 'sent_at' => now(), 'smtp_id' => $smtp->id]);
            return;
        }

        $emailLog = EmailLog::updateOrCreate(
            ['message_id' => $messageId],
            [
        $emailLog = EmailLog::updateOrCreate(
            ['message_id' => $messageId],
            [
        $messageId = 'job-'.$emailJob->id.'-'.Str::uuid();

        $emailLog = EmailLog::create([
            'user_id' => $emailJob->user_id,
            'campaign_id' => $emailJob->campaign_id,
            'smtp_id' => $smtp->id,
            'to_email' => $emailJob->to_email,
            'recipient_email' => $emailJob->to_email,
            'subject' => $emailJob->subject,
            'status' => 'pending',
            'message_id' => $messageId,
            ]
        ]
        );
            'message_id' => $messageId,
        ]);

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

        Mail::html($htmlBody, function ($message) use ($emailJob, $messageId): void {
            $message->to($emailJob->to_email)
                ->subject($emailJob->subject)
                ->getHeaders()
                ->addTextHeader('Message-ID', $messageId);
        });

        $emailJob->update(['status' => 'sent', 'sent_at' => now(), 'smtp_id' => $smtp->id]);
        $emailLog->update(['status' => 'sent', 'sent_at' => now()]);
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
