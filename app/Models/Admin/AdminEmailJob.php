<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminEmailJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_key',
        'idempotency_key',
        'to_email',
        'subject',
        'html_body',
        'from_email',
        'from_name',
        'status',
        'node_id',
        'ip_pool_id',
        'error_message',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];
}
