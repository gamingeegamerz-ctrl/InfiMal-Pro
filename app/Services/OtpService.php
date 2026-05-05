<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

class OtpService
{
    public function generateAndSendOtp(User $user): bool
    {
        $otpCode = rand(100000, 999999);
        Cache::put('otp_' . $user->id, $otpCode, now()->addMinutes(10));

        try {
            Mail::mailer('resend')
                ->to($user->email)
                ->send(new OtpMail($otpCode));
            return true;
        } catch (\Exception $e) {
            \Log::error('Resend OTP failed: ' . $e->getMessage());
            return false;
        }
    }

    public function verifyOtp(User $user, string $otpCode): bool
    {
        $cached = Cache::get('otp_' . $user->id);
        if ($cached && $cached == $otpCode) {
            Cache::forget('otp_' . $user->id);
            $user->update(['is_verified' => true]);
            return true;
        }
        return false;
    }
}
