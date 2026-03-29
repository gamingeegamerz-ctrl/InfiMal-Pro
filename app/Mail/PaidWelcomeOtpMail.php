<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaidWelcomeOtpMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public string $otp)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Welcome to InfiMal Pro - Verify Your Account');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.paid-welcome-otp');
    }
}
