<?php

namespace App\Models;

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
        'avatar',
        'timezone',
        'phone',
        'bio',
        'preferences',
        'payment_status',
        'is_paid',
        'plan_name',
        'paid_at',
        'payment_date',
        'payment_amount',
        'transaction_id',
        'license_key',
        'license_status',
        'license_expires_at',
        'is_admin',
        'google_id',
        'otp_code',
        'otp_expires_at',
        'otp_verified_at',
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
        'plan_expiry_date' => 'datetime',
        'license_expires_at' => 'datetime',
        'is_paid' => 'boolean',
        'is_admin' => 'boolean',
    ];

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class, 'user_id');
    }

    public function lists(): HasMany
    {
        return $this->hasMany(MailingList::class, 'user_id');
    }

    public function mailingLists(): HasMany
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
        return $this->hasOne(License::class, 'user_id')->where('status', 'active');
        return $this->hasOne(License::class, 'user_id')->where(function($query) {
            $query->where('status', 'active')->orWhere('is_active', true);
        });
    }

    public function hasPaid(): bool
    {
        return $this->is_paid || (string) $this->payment_status === 'paid' || ! is_null($this->paid_at);
        return (bool) ($this->is_paid || (string) $this->payment_status === 'paid' || !is_null($this->paid_at));
    }

    public function hasActiveLicense(): bool
    {
        return $this->activeLicense()->exists() || (! empty($this->license_key) && ($this->license_status === 'active' || $this->license_status === null));
        return $this->activeLicense()->exists()
            || ((string) $this->license_status === 'active' && !empty($this->license_key));
    }

    public function otpRequired(): bool
    {
        return !is_null($this->otp_code) || !is_null($this->otp_expires_at);
    }

    public function hasPaidAccess(): bool
    {
        return $this->hasPaid()
            && $this->hasActiveLicense()
            && (!$this->otpRequired() || !is_null($this->otp_verified_at));
    }
}
