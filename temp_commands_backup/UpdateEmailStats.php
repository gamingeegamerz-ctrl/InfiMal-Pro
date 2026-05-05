<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateEmailStats extends Command
{
    protected $signature = 'email:update-stats';
    protected $description = 'Update email statistics and rates';

    public function handle()
    {
        $this->info('Updating email statistics...');

        DB::table('email_logs')->update([
            'open_rate' => DB::raw('CASE WHEN opened = 1 THEN 100 ELSE 0 END'),
            'click_rate' => DB::raw('CASE WHEN clicked = 1 THEN 100 ELSE 0 END'),
            'updated_at' => now(),
        ]);

        $this->info('Email statistics updated successfully!');

        return self::SUCCESS;
    }
}
