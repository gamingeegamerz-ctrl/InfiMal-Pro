<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SMTPAccount;

class ResetDailySmtpUsage extends Command
{
    protected $signature = 'smtp:reset-daily';
    protected $description = 'Reset daily SMTP usage counters';

    public function handle()
    {
        SMTPAccount::query()->update([
            'sent_today' => 0,
        ]);

        $this->info('SMTP daily counters reset');
    }
}
