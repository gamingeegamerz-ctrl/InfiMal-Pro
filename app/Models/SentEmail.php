<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SentEmail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'campaign_id', 'message_id', 'recipient_email', 'from_email', 'subject', 'status', 'sent_at',
    ];

    protected $casts = ['sent_at' => 'datetime'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function campaign(): BelongsTo { return $this->belongsTo(Campaign::class); }
    public function opens(): HasMany { return $this->hasMany(EmailOpen::class); }
    public function clicks(): HasMany { return $this->hasMany(EmailClick::class); }
}
