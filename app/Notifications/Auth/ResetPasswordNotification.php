<?php

namespace App\Notifications\Auth;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPassword
{
    use Queueable;

    /**
     * Build the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        $appName = config('app.name', 'InfiMal');
        $expiryMinutes = (int) config('auth.passwords.'.config('auth.defaults.passwords').'.expire', 60);

        return (new MailMessage)
            ->subject("{$appName} Password Reset Request")
            ->greeting('Hi '.($notifiable->name ?: 'there').',')
            ->line('We received a request to reset your password.')
            ->line('Click the button below to set a new password securely.')
            ->action('Reset Password', $resetUrl)
            ->line("This reset link will expire in {$expiryMinutes} minutes.")
            ->line('If you did not request this, you can safely ignore this email.')
            ->salutation("Regards,\n{$appName} Security Team");
        return (new MailMessage)
            ->subject('Reset your InfiMal password')
            ->greeting('Hello!')
            ->line('We received a request to reset your InfiMal account password.')
            ->action('Reset Password', $resetUrl)
            ->line('This password reset link will expire in 60 minutes.')
            ->line('If you did not request a password reset, no further action is required.')
            ->salutation('Regards, InfiMal Security Team');
    }
}
