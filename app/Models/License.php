<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class License extends Model
{
    use HasFactory;

    protected $fillable = [
        'license_key',
        'user_id',
        'plan_type',
        'price',
        'duration_days',
        'status',
        'is_active',
        'is_lifetime',
        'expires_at',
        'features',
        'notes',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'is_lifetime' => 'boolean',
        'features' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function generateLicenseKey(): string
    {
        do {
            $key = 'INFIMAL-' . strtoupper(Str::random(24));
        } while (self::where('license_key', $key)->exists());

        return $key;
    }
}
