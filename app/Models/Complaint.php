<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'campaign_id', 'sent_email_id', 'message_id', 'recipient_email', 'complaint_feedback_type', 'user_agent', 'complained_at',
    ];

    protected $casts = ['complained_at' => 'datetime'];
}
