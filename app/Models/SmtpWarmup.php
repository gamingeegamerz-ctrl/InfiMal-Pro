<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmtpWarmup extends Model
{
    use HasFactory;

    protected $table = 'smtp_warmup';

    protected $fillable = [
        'smtp_id',
        'warmup_day',
        'daily_limit',
    ];
}
