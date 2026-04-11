<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'campaign_id',
        'subscriber_id',
        'to_email',
        'to_name',
        'subject',
        'body',
        'html',
        'from_email',
        'from_name',
        'reply_to',
        'status',
        'scheduled_at',
        'sent_at',
        'failed_at',
        'error_message',
        'retry_count',
        'smtp_id'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'failed_at' => 'datetime',
        'retry_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // =============================================
    // RELATIONSHIPS
    // =============================================

    /**
     * EmailJob belongs to User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * EmailJob belongs to Campaign
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * EmailJob belongs to Subscriber
     */
    public function subscriber(): BelongsTo
    {
        return $this->belongsTo(Subscriber::class);
    }

    /**
     * EmailJob belongs to SMTPAccount
     */
    public function smtp(): BelongsTo
    {
        return $this->belongsTo(SMTPAccount::class, 'smtp_id');
    }

    // =============================================
    // SCOPES
    // =============================================

    /**
     * Scope: Only queued jobs
     */
    public function scopeQueued($query)
    {
        return $query->where('status', 'queued');
    }


    /**
     * Scope: Jobs ready for delivery time
     */
    public function scopeReadyToSend($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('scheduled_at')
                ->orWhere('scheduled_at', '<=', now());
        });
    }

    /**
     * Scope: Only failed jobs
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope: Jobs that need retry
     */
    public function scopeNeedsRetry($query)
    {
        return $query->where('status', 'failed')
            ->where('retry_count', '<', 3);
    }
}
