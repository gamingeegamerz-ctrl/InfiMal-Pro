<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailOpen extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'campaign_id', 'sent_email_id', 'message_id', 'recipient_email', 'ip_address', 'user_agent', 'opened_at',
    ];

    protected $casts = ['opened_at' => 'datetime'];
}
