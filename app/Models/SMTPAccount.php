<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class SMTPAccount extends Model
{
    protected $table = 'smtps';

    protected $fillable = [
        'user_id',
        'host',
        'port',
        'username',
        'password_encrypted',
        'encryption',
        'from_email',
        'from_name',
        'daily_limit',
        'per_minute_limit',
        'warmup_enabled',
        'is_default',
        'is_active',
    ];

    protected $hidden = ['password_encrypted'];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'warmup_enabled' => 'boolean',
        'daily_limit' => 'integer',
        'per_minute_limit' => 'integer',
    ];

    public function setPasswordAttribute(string $password): void
    {
        $this->attributes['password_encrypted'] = Crypt::encryptString($password);
    }

    public function getPasswordAttribute(): ?string
    {
        if (!$this->password_encrypted) {
            return null;
        }

        try {
            return Crypt::decryptString($this->password_encrypted);
        } catch (\Throwable) {
            return null;
        }
    }

    public function scopeOwnedBy($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function warmupRules()
    {
        return $this->hasMany(SmtpWarmup::class, 'smtp_id');
    }
}
