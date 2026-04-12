<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdminSmtpNode extends Model
{
    use HasFactory;

    protected $fillable = [
        'server_ip',
        'hostname',
        'supported_ports',
        'sending_domain',
        'reputation_score',
        'status',
        'daily_limit',
        'daily_sent',
        'last_port',
        'last_health_check_at',
        'is_active',
    ];

    protected $casts = [
        'supported_ports' => 'array',
        'reputation_score' => 'float',
        'daily_limit' => 'integer',
        'daily_sent' => 'integer',
        'last_health_check_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function ips(): HasMany
    {
        return $this->hasMany(AdminIpPool::class, 'node_id');
    }
}
