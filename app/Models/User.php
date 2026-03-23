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
    ];

    protected $hidden = [
        'password',
        'remember_token',
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
        'password' => 'hashed',
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
        return $this->lists();
    }

    public function subscriberLists(): HasMany
    {
        return $this->lists();
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
    }

    public function hasPaid(): bool
    {
        return $this->is_paid || (string) $this->payment_status === 'paid' || ! is_null($this->paid_at);
    }

    public function hasActiveLicense(): bool
    {
        return $this->activeLicense()->exists() || (! empty($this->license_key) && ($this->license_status === 'active' || $this->license_status === null));
    }
}
