<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminWarmupLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_pool_id',
        'sending_domain',
        'warmup_day',
        'target_volume',
        'actual_sent',
        'bounce_rate',
        'complaint_rate',
        'status',
        'notes',
    ];

    protected $casts = [
        'warmup_day' => 'integer',
        'target_volume' => 'integer',
        'actual_sent' => 'integer',
        'bounce_rate' => 'decimal:4',
        'complaint_rate' => 'decimal:4',
    ];
}
