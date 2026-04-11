<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSmtpReputation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'smtp_id',
        'success_count',
        'bounce_count',
        'complaint_count',
        'score',
        'last_event_at',
    ];

    protected $casts = [
        'success_count' => 'integer',
        'bounce_count' => 'integer',
        'complaint_count' => 'integer',
        'score' => 'float',
        'last_event_at' => 'datetime',
    ];
}
