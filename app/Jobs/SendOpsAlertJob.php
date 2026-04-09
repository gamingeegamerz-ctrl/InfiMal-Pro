<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOpsAlertJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public string $subject, public string $message)
    {
        $this->onQueue('alerts');
    }

    public function handle(): void
    {
        $opsEmail = config('infimal.alerts.ops_email');
        if (! $opsEmail) {
            return;
        }

        Mail::raw($this->message, function ($mail) use ($opsEmail): void {
            $mail->to($opsEmail)->subject($this->subject);
        });
    }
}
