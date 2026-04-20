<?php

namespace App\Models;

use App\Notifications\Auth\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'google_password_set',
        'payment_status',
        'is_paid',
        'paid_at',
        'license_key',
        'license_status',
        'avatar',
        'timezone',
        'phone',
        'bio',
        'preferences',
        'plan_name',
        'payment_date',
        'payment_amount',
        'transaction_id',
        'license_expires_at',
        'is_admin',
        'otp_code',
        'otp_expires_at',
        'otp_verified_at',
        'accepted_terms_at',
        'last_login_at',
        'campaign_count',
        'email_sent',
        'otp_last_sent_at',
        'otp_locked_until',
        'otp_failed_attempts',
        'onboarding_step',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp_code',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'preferences' => 'array',
        'paid_at' => 'datetime',
        'payment_date' => 'datetime',
        'license_expires_at' => 'datetime',
        'otp_expires_at' => 'datetime',
        'otp_verified_at' => 'datetime',
        'accepted_terms_at' => 'datetime',
        'last_login_at' => 'datetime',
        'otp_locked_until' => 'datetime',
        'otp_last_sent_at' => 'datetime',
        'is_paid' => 'boolean',
        'is_admin' => 'boolean',
        'google_password_set' => 'boolean',
        'otp_failed_attempts' => 'integer',
    ];

    // =================== RELATIONSHIPS ===================
    
    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class, 'user_id');
    }

    public function subscriberLists(): HasMany
    {
        return $this->hasMany(MailingList::class, 'user_id');
    }

    public function lists(): HasMany
    {
        return $this->subscriberLists();
    }

    public function mailingLists(): HasMany
    {
        return $this->subscriberLists();
    }

    public function subscribers(): HasMany
    {
        return $this->hasMany(Subscriber::class, 'user_id');
    }


    public function senderDomains(): HasMany
    {
        return $this->hasMany(SenderDomain::class, 'user_id');
    }

    public function smtpAccounts(): HasMany
    {
        return $this->hasMany(SMTPAccount::class, 'user_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'user_id');
    }

    public function licenses(): HasMany
    {
        return $this->hasMany(License::class, 'user_id');
    }

    public function activeLicense(): HasOne
    {
        return $this->hasOne(License::class, 'user_id')
            ->where(function($query) {
                $query->where('status', 'active')
                    ->orWhere('is_active', true);
            });
    }

    // =================== PAYMENT & ACCESS METHODS ===================

    public function getAccessStateAttribute(): string
    {
        if (! $this->exists) {
            return 'NOT_REGISTERED';
        }

        if (! $this->hasPaid()) {
            return 'REGISTERED_NOT_PAID';
        }

        if (! $this->otp_verified_at) {
            return 'PAID_NOT_VERIFIED';
        }

        return 'ACTIVE_USER';
    }

    public function isInactive(int $days = 14): bool
    {
        return $this->last_login_at ? $this->last_login_at->lt(now()->subDays($days)) : true;
    }

    
    /**
     * Check if user has paid
     */
    public function hasPaid(): bool
    {
        return (bool) ($this->is_paid || $this->payment_status === 'paid' || ! is_null($this->paid_at));
    }

    /**
     * Check if user has active license
     */
    public function hasActiveLicense(): bool
    {
        return $this->licenses()
            ->where('status', 'active')
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Check if OTP is required
     */
    public function otpRequired(): bool
    {
        return !is_null($this->otp_code) || !is_null($this->otp_expires_at);
    }

    /**
     * Alias for otpRequired() for backward compatibility
     */
    public function isOtpRequired(): bool
    {
        return $this->otpRequired();
    }

    /**
     * Send the password reset notification using branded SaaS email content.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function hasPaidAccess(): bool
    {
        if ($this->is_admin) {
            return true;
        }

        return $this->hasPaid()
            && $this->hasActiveLicense()
            && ! is_null($this->otp_verified_at);
    }


}

