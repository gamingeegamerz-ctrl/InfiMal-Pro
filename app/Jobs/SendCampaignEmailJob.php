<?php

namespace App\Jobs;

use App\Http\Controllers\TrackingController;
use App\Models\Campaign;
use App\Models\EmailJob;
use App\Models\SMTPAccount;
use App\Services\SendEngineService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendCampaignEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $emailJobId;
    public int $tries = 3;

    public function __construct(int $emailJobId)
    {
        $this->emailJobId = $emailJobId;
    }

    public function handle(SendEngineService $engine): void
    {
        $emailJob = EmailJob::find($this->emailJobId);
        if (! $emailJob || in_array($emailJob->status, ['sent', 'bounced'], true)) {
            return;
        }

        $emailJob->update(['status' => 'processing']);

        $smtp = SMTPAccount::ownedBy($emailJob->user_id)
            ->where('is_active', true)
            ->orderByDesc('is_default')
            ->first();

        if (! $smtp) {
            $emailJob->update(['status' => 'failed', 'error_message' => 'No active SMTP configured']);
            return;
        }

        $quotaCheck = $engine->canSendNow($smtp);
        if (! $quotaCheck['allowed']) {
            $emailJob->update(['status' => 'queued']);
            $this->release($quotaCheck['delay']);
            return;
        }

        $log = $engine->createLog([
            'user_id' => $emailJob->user_id,
            'campaign_id' => $emailJob->campaign_id,
            'smtp_id' => $smtp->id,
            'recipient_email' => $emailJob->to_email,
            'to_email' => $emailJob->to_email,
            'status' => 'pending',
            'message_id' => $emailJob->id . '-' . now()->timestamp,
        ]);

        $htmlContent = TrackingController::processEmailContent($emailJob->html ?? $emailJob->body, $log->id);

        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp.host', $smtp->host);
        Config::set('mail.mailers.smtp.port', $smtp->port);
        Config::set('mail.mailers.smtp.encryption', $smtp->encryption === 'none' ? null : $smtp->encryption);
        Config::set('mail.mailers.smtp.username', $smtp->username);
        Config::set('mail.mailers.smtp.password', $smtp->password);
        Config::set('mail.from.address', $smtp->from_address ?: $emailJob->from_email);
        Config::set('mail.from.name', $smtp->from_name ?: $emailJob->from_name);

        try {
            Mail::html($htmlContent, function ($message) use ($emailJob) {
                $message->to($emailJob->to_email, $emailJob->to_name)->subject($emailJob->subject);
                if ($emailJob->reply_to) {
                    $message->replyTo($emailJob->reply_to);
                }
            });

            $emailJob->update([
                'status' => 'sent',
                'smtp_id' => $smtp->id,
                'sent_at' => now(),
            ]);

            $log->update(['status' => 'sent']);

            if ($emailJob->campaign_id) {
                Campaign::where('id', $emailJob->campaign_id)
                    ->where('user_id', $emailJob->user_id)
                    ->increment('total_sent');
            }
        } catch (Throwable $e) {
            $emailJob->increment('retry_count');
            $status = $emailJob->retry_count >= $this->tries ? 'bounced' : 'queued';
            $emailJob->update([
                'status' => $status,
                'failed_at' => now(),
                'error_message' => substr($e->getMessage(), 0, 1000),
            ]);

            $log->update([
                'status' => $status === 'bounced' ? 'bounced' : 'failed',
                'error_message' => substr($e->getMessage(), 0, 1000),
            ]);

            if ($emailJob->retry_count < $this->tries) {
                $this->release(60);
            }
        }
    }
}
