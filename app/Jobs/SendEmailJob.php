<?php

namespace App\Jobs;

use App\Models\EmailJob;
use App\Models\SMTPAccount;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendCampaignEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $emailJobId;

    public $tries   = 5;
    public $timeout = 120;

    public function __construct(int $emailJobId)
    {
        $this->emailJobId = $emailJobId;
    }

    public function handle(): void
    {
        $emailJob = EmailJob::find($this->emailJobId);

        // Record hi nahi ? silently exit
        if (!$emailJob) {
            return;
        }

        // Already processed
        if ($emailJob->status === 'sent') {
            return;
        }

        try {
            /*
            |--------------------------------------------------------------------------
            | 1?? GLOBAL DAILY LIMIT (LICENSE / USER)
            |--------------------------------------------------------------------------
            */
            $dailyLimit = DB::table('licenses')
                ->where('user_id', $emailJob->user_id)
                ->value('daily_limit');

            if ($dailyLimit) {
                $sentToday = DB::table('email_logs')
                    ->where('user_id', $emailJob->user_id)
                    ->whereDate('created_at', today())
                    ->count();

                if ($sentToday >= $dailyLimit) {
                    // Hard stop for today
                    $this->release(120);
                    return;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | 2?? PICK SMTP (ROTATION + REPUTATION)
            |--------------------------------------------------------------------------
            */
            $smtp = SMTPAccount::pickForSending();

            if (!$smtp) {
                // No SMTP available ? wait
                $this->release(120);
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | 3?? WARMUP CHECK (HOURLY LIMIT)
            |--------------------------------------------------------------------------
            */
            if (!$smtp->canSendNow()) {
                $this->release(60);
                return;
            }

            // Mark usage early (fair rotation)
            $smtp->markUsed();

            /*
            |--------------------------------------------------------------------------
            | 4?? DYNAMIC SMTP CONFIG
            |--------------------------------------------------------------------------
            */
            config([
                'mail.default'                 => 'smtp',
                'mail.mailers.smtp.host'       => $smtp->smtp_host,
                'mail.mailers.smtp.port'       => $smtp->smtp_port,
                'mail.mailers.smtp.encryption' => null,
                'mail.mailers.smtp.username'   => $smtp->smtp_username,
                'mail.mailers.smtp.password'   => $smtp->smtp_password,
                'mail.from.address'            => $emailJob->from_email,
                'mail.from.name'               => $emailJob->from_name,
            ]);

            /*
            |--------------------------------------------------------------------------
            | 5?? SEND EMAIL
            |--------------------------------------------------------------------------
            */
            Mail::html($emailJob->body, function ($message) use ($emailJob) {
                $message->to($emailJob->to_email)
                    ->subject($emailJob->subject);
            });

            /*
            |--------------------------------------------------------------------------
            | 6?? SUCCESS HANDLING
            |--------------------------------------------------------------------------
            */
            $emailJob->status = 'sent';
            $emailJob->sent_at = now();
            $emailJob->save();

            $smtp->markHourlySend();

            DB::table('email_logs')->insert([
                'user_id'     => $emailJob->user_id,
                'campaign_id' => $emailJob->campaign_id,
                'smtp_id'     => $smtp->id,
                'to_email'    => $emailJob->to_email,
                'status'      => 'sent',
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

        } catch (Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | 7?? FAILURE HANDLING (SAFE)
            |--------------------------------------------------------------------------
            */
            Log::error('SendCampaignEmailJob failed', [
                'email_job_id' => $this->emailJobId,
                'error'        => $e->getMessage(),
            ]);

            DB::table('email_logs')->insert([
                'user_id'     => $emailJob->user_id,
                'campaign_id' => $emailJob->campaign_id,
                'smtp_id'     => $smtp->id ?? null,
                'to_email'    => $emailJob->to_email,
                'status'      => 'failed',
                'error'       => $e->getMessage(),
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            // Soft penalty (temporary failures)
            if (isset($smtp)) {
                $smtp->reduceReputation(2);
            }

            // Retry later
            $this->release(180);
        }
    }
}
