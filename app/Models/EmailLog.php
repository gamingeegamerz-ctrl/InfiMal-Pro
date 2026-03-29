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
        'subject',
        'status',
        'opened',
        'clicked',
        'opened_at',
        'clicked_at',
        'bounced_at',
        'message_id',
        'error_message',
        'sent_at',
    ];

    protected $casts = [
        'opened' => 'boolean',
        'clicked' => 'boolean',
        'opened_at' => 'datetime',
        'clicked_at' => 'datetime',
        'bounced_at' => 'datetime',
        'sent_at' => 'datetime',
    ];
}
