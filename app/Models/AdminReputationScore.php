<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminReputationScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_ip_pool_id',
        'sending_domain',
        'provider',
        'reputation_score',
        'success_rate',
        'bounce_rate',
        'complaint_rate',
        'low_usage_factor',
        'composite_score',
        'recorded_at',
    ];

    protected $casts = [
        'reputation_score' => 'float',
        'success_rate' => 'float',
        'bounce_rate' => 'float',
        'complaint_rate' => 'float',
        'low_usage_factor' => 'float',
        'composite_score' => 'float',
        'recorded_at' => 'datetime',
    ];

    public function ip(): BelongsTo
    {
        return $this->belongsTo(AdminIpPool::class, 'admin_ip_pool_id');
    }
}
