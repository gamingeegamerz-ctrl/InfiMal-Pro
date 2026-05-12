<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailClick extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'campaign_id', 'sent_email_id', 'message_id', 'recipient_email', 'url', 'ip_address', 'user_agent', 'clicked_at',
    ];

    protected $casts = ['clicked_at' => 'datetime'];
}
