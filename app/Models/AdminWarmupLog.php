<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminWarmupLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_ip_pool_id',
        'sending_domain',
        'warmup_day',
        'target_volume',
        'actual_volume',
        'bounce_rate',
        'complaint_rate',
        'status',
        'notes',
        'logged_on',
    ];

    protected $casts = [
        'target_volume' => 'integer',
        'actual_volume' => 'integer',
        'bounce_rate' => 'float',
        'complaint_rate' => 'float',
        'logged_on' => 'date',
    ];

    public function ip(): BelongsTo
    {
        return $this->belongsTo(AdminIpPool::class, 'admin_ip_pool_id');
    }
}
