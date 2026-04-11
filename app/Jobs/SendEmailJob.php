<?php

namespace App\Jobs;

use App\Http\Controllers\TrackingController;
use App\Models\EmailJob;
use App\Models\EmailLog;
use App\Models\SMTPAccount;
use App\Services\SchedulerService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
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

    public function handle(SchedulerService $scheduler): void
    {
        $emailJob = EmailJob::find($this->emailJobId);
        if (! $emailJob || in_array($emailJob->status, ['sent', 'bounced'], true)) {
            return;
        }

        if ($emailJob->scheduled_at && $emailJob->scheduled_at->isFuture()) {
            $this->release($emailJob->scheduled_at->diffInSeconds(now()) + 1);
            return;
        }

        $smtp = SMTPAccount::ownedBy($emailJob->user_id)
            ->where('is_active', true)
            ->userOwned()
            ->orderByDesc('is_default')
            ->first();

        if (! $smtp) {
            $emailJob->update(['status' => 'failed', 'error_message' => 'Active SMTP not configured']);
            return;
        }

        if ((bool) ($smtp->is_admin_pool ?? false)) {
            $emailJob->update(['status' => 'failed', 'error_message' => 'Admin SMTP is isolated from user jobs.']);
            return;
        }

        if (! $scheduler->enforceBeforeSend($emailJob, $smtp)) {
            $this->release(60);
            return;
        }

        $messageId = 'job-'.$emailJob->id.'-'.now()->timestamp;
        $emailLog = EmailLog::create([
            'user_id' => $emailJob->user_id,
            'campaign_id' => $emailJob->campaign_id,
            'smtp_id' => $smtp->id,
            'to_email' => $emailJob->to_email,
            'recipient_email' => $emailJob->to_email,
            'subject' => $emailJob->subject,
            'status' => 'pending',
            'message_id' => $messageId,
        ]);

        $htmlBody = TrackingController::processEmailContent($emailJob->html ?: nl2br(e((string) $emailJob->body)), $emailLog->id);

        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp.host', $smtp->host);
        Config::set('mail.mailers.smtp.port', $smtp->port);
        Config::set('mail.mailers.smtp.encryption', $smtp->encryption === 'none' ? null : $smtp->encryption);
        Config::set('mail.mailers.smtp.username', $smtp->username);
        Config::set('mail.mailers.smtp.password', $smtp->password);
        Config::set('mail.from.address', $smtp->from_address ?: $emailJob->from_email);
        Config::set('mail.from.name', $smtp->from_name ?: 'InfiMal');

        Mail::html($htmlBody, function ($message) use ($emailJob): void {
            $message->to($emailJob->to_email)
                ->subject($emailJob->subject);
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
