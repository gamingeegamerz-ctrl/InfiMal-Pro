<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'campaign_id',
        'smtp_id',
        'recipient_email',
        'to_email',
        'status',
        'opened',
        'clicked',
        'message_id',
        'error_message',
    ];

    protected $casts = [
        'opened' => 'boolean',
        'clicked' => 'boolean',
    ];
}
