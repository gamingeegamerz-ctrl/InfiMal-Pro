<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminReputationScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_pool_id',
        'sending_domain',
        'reputation_score',
        'success_rate',
        'bounce_rate',
        'complaint_rate',
        'deliverability_score',
        'calculated_at',
    ];

    protected $casts = [
        'reputation_score' => 'decimal:4',
        'success_rate' => 'decimal:4',
        'bounce_rate' => 'decimal:4',
        'complaint_rate' => 'decimal:4',
        'deliverability_score' => 'decimal:4',
        'calculated_at' => 'datetime',
    ];

    public function ipPool(): BelongsTo
    {
        return $this->belongsTo(AdminIpPool::class, 'ip_pool_id');
    }
}
