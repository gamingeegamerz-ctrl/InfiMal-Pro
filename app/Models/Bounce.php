<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bounce extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'campaign_id', 'sent_email_id', 'message_id', 'recipient_email', 'bounce_type', 'bounce_sub_type', 'diagnostic_code', 'bounced_at',
    ];

    protected $casts = ['bounced_at' => 'datetime'];
}
