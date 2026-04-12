<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class SMTPAccount extends Model
{
    protected $table = 'smtps';

    protected $fillable = [
        'user_id',
        'smtp_host',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'encryption',
        'from_email',
        'from_name',
        'daily_limit',
        'per_minute_limit',
        'warmup_enabled',
        'is_default',
        'is_active',
        'is_admin_pool',
        // compatibility aliases
        'host',
        'port',
        'username',
        'password',
        'from_address',
        'yahoo_score',
        'outlook_score',
        'gmail_score',
        'engagement_score',
        'complaint_rate',
        'bounce_rate',
        'success_rate',
        'last_validated_at',
        'validation_message',
        'validation_status',
    ];

    protected $hidden = ['smtp_password', 'password_encrypted'];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'warmup_enabled' => 'boolean',
        'is_admin_pool' => 'boolean',
        'daily_limit' => 'integer',
        'per_minute_limit' => 'integer',
        'smtp_port' => 'integer',
    ];

    public function setSmtpPasswordAttribute(?string $password): void
    {
        if ($password === null || $password === '') {
            return;
        }

        $this->attributes['smtp_password'] = Crypt::encryptString($password);
    }

    public function getSmtpPasswordAttribute($value): ?string
    {
        if (!$value) {
            return null;
        }

        try {
            return Crypt::decryptString($value);
        } catch (\Throwable) {
            return $value;
        }
    }

    // compatibility aliases used by controllers/services
    public function setHostAttribute(?string $value): void
    {
        $this->attributes['smtp_host'] = $value;
    }

    public function getHostAttribute(): ?string
    {
        return $this->attributes['smtp_host'] ?? null;
    }

    public function setPortAttribute($value): void
    {
        $this->attributes['smtp_port'] = $value;
    }

    public function getPortAttribute(): ?int
    {
        return isset($this->attributes['smtp_port']) ? (int) $this->attributes['smtp_port'] : null;
    }

    public function setUsernameAttribute(?string $value): void
    {
        $this->attributes['smtp_username'] = $value;
    }

    public function getUsernameAttribute(): ?string
    {
        return $this->attributes['smtp_username'] ?? null;
    }

    public function setPasswordAttribute(?string $value): void
    {
        $this->setSmtpPasswordAttribute($value);
    }

    public function getPasswordAttribute(): ?string
    {
        return $this->smtp_password;
    }

    public function setFromAddressAttribute(?string $value): void
    {
        $this->attributes['from_email'] = $value;
    }

    public function getFromAddressAttribute(): ?string
    {
        return $this->attributes['from_email'] ?? null;
    }

    public function scopeOwnedBy($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function warmupRules()
    {
        return $this->hasMany(SmtpWarmup::class, 'smtp_id');
    }


    public function scopeUserOwned($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('is_admin_pool')->orWhere('is_admin_pool', false);
        });
    }
}
