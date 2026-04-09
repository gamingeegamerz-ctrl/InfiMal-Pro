<?php

namespace App\Jobs;

use App\Mail\PaidWelcomeOtpMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendOtpMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;

    public function backoff(): array
    {
        return [30, 60, 120, 300];
    }

    public function __construct(public int $userId, public string $otp)
    {
        $this->onQueue('emails');
    }

    public function handle(): void
    {
        $user = User::find($this->userId);
        if (! $user) {
            return;
        }

        Mail::mailer('otp_smtp')->to($user->email)->send(
            (new PaidWelcomeOtpMail($user, $this->otp))->from(
                config('infimal.otp.from_address', 'noreply@yourdomain.com'),
                config('infimal.otp.from_name', config('app.name', 'InfiMal'))
            )
        Mail::to($user->email)->send(
            (new PaidWelcomeOtpMail($user, $this->otp))->from('noreply@yourdomain.com', config('app.name', 'InfiMal'))
        );
    }

    public function failed(\Throwable $e): void
    {
        Log::channel('security')->error('OTP mail job failed', [
            'user_id' => $this->userId,
            'error' => $e->getMessage(),
        ]);
    }
}
