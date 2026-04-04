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
        'google_id',
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
        'is_paid' => 'boolean',
        'is_admin' => 'boolean',
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
    
    /**
     * Check if user has paid (Admin always returns true)
     */
    public function hasPaid(): bool
    {
        // Admin always has paid access
        if ($this->is_admin) {
            return true;
        }
        
        // Normal user payment check
        return (bool) ($this->is_paid || $this->payment_status === 'paid' || !is_null($this->paid_at));
    }

    /**
     * Check if user has active license (Admin always returns true)
     */
    public function hasActiveLicense(): bool
    {
        // Admin always has active license
        if ($this->is_admin) {
            return true;
        }
        
        // Check via license_key column
        if (!empty($this->license_key)) {
            return $this->licenses()
                ->where('license_key', $this->license_key)
                ->where('status', 'active')
                ->where(function($query) {
                    $query->whereNull('expires_at')->orWhere('expires_at', '>', now());
                })
                ->exists();
        }

        // Check via activeLicense relationship
        return $this->activeLicense()
            ->where(function($query) {
                $query->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
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
     * Check if user has full paid access (Admin always returns true)
     */
    public function hasPaidAccess(): bool
    {
        // Admin always has full access
        if ($this->is_admin) {
            return true;
        }
        
        // Normal user checks
        return $this->hasPaid()
            && $this->hasActiveLicense()
            && (!$this->otpRequired() || !is_null($this->otp_verified_at));
    }
}
