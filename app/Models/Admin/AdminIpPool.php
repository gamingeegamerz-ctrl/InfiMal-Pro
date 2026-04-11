<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdminIpPool extends Model
{
    use HasFactory;

    protected $table = 'admin_ip_pool';

    protected $fillable = [
        'node_id',
        'ip_address',
        'status',
        'daily_limit',
        'sent_today',
        'success_rate',
        'bounce_rate',
        'port_performance',
        'yahoo_score',
        'outlook_score',
        'gmail_score',
        'engagement_score',
        'complaint_rate',
        'last_used_at',
    ];

    protected $casts = [
        'daily_limit' => 'integer',
        'sent_today' => 'integer',
        'success_rate' => 'decimal:4',
        'bounce_rate' => 'decimal:4',
        'complaint_rate' => 'decimal:4',
        'engagement_score' => 'decimal:4',
        'gmail_score' => 'decimal:4',
        'outlook_score' => 'decimal:4',
        'yahoo_score' => 'decimal:4',
        'port_performance' => 'array',
        'last_used_at' => 'datetime',
    ];

    public function node(): BelongsTo
    {
        return $this->belongsTo(AdminSmtpNode::class, 'node_id');
    }

    public function reputationScores(): HasMany
    {
        return $this->hasMany(AdminReputationScore::class, 'ip_pool_id');
    }
}
