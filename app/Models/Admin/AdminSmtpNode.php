<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdminSmtpNode extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'server_ip',
        'hostname',
        'supported_ports',
        'preferred_port',
        'sending_domain',
        'reputation_score',
        'status',
        'daily_limit',
    ];

    protected $casts = [
        'supported_ports' => 'array',
        'reputation_score' => 'decimal:4',
        'daily_limit' => 'integer',
        'preferred_port' => 'integer',
    ];

    public function ipPool(): HasMany
    {
        return $this->hasMany(AdminIpPool::class, 'node_id');
    }
}
