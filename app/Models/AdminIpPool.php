<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminIpPool extends Model
{
    use HasFactory;

    protected $table = 'admin_ip_pool';

    protected $fillable = [
        'node_id',
        'ip_address',
        'reputation_score',
        'success_rate',
        'bounce_rate',
        'daily_limit',
        'daily_sent',
        'warmup_day',
        'status',
        'last_port',
    ];

    protected $casts = [
        'reputation_score' => 'float',
        'success_rate' => 'float',
        'bounce_rate' => 'float',
        'daily_limit' => 'integer',
        'daily_sent' => 'integer',
        'warmup_day' => 'integer',
    ];

    public function node(): BelongsTo
    {
        return $this->belongsTo(AdminSmtpNode::class, 'node_id');
    }
}
