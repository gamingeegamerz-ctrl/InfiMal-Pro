<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'payment_status',
        'is_paid',
        'paid_at',
        'license_key',
        'license_status',
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
        'paid_at' => 'datetime',
        'payment_date' => 'datetime',
        'plan_expiry_date' => 'datetime',
        'otp_expires_at' => 'datetime',
        'otp_verified_at' => 'datetime',
        'is_paid' => 'boolean',
    ];

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

    public function licenses(): HasMany
    {
        return $this->hasMany(License::class);
    }

    public function activeLicense(): HasOne
    {
        return $this->hasOne(License::class)->where('is_active', true);
    }

    public function hasPaid(): bool
    {
        return (bool) ($this->is_paid || (string) $this->payment_status === 'paid' || !is_null($this->paid_at));
    }

    public function hasActiveLicense(): bool
    {
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
