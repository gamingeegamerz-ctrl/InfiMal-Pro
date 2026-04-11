<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class EnforceAdminSmtpProtection extends Command
{
    protected $signature = 'infimal:enforce-admin-smtp-protection';

    protected $description = 'Apply admin SMTP IP/domain limits and anomaly auto-actions';

    public function handle(): int
    {
        $smtps = DB::table('smtp_servers')->get();
        $paused = 0;
        $throttled = 0;

        foreach ($smtps as $smtp) {
            $dailyLimitHit = ($smtp->emails_today ?? 0) >= ($smtp->max_daily_per_ip ?? 5000);
            $domainLimitHit = ($smtp->emails_today ?? 0) >= ($smtp->max_daily_per_domain ?? 2000);
            $anomaly = ($smtp->hard_bounces_24h ?? 0) > 100 || ($smtp->spam_complaints_24h ?? 0) > 20;

            if ($dailyLimitHit || $domainLimitHit || $anomaly) {
                DB::table('smtp_servers')->where('id', $smtp->id)->update([
                    'is_paused' => true,
                    'is_active' => false,
                    'last_skipped_reason' => 'Auto-paused by protection policy',
                    'updated_at' => now(),
                ]);
                $paused++;
                continue;
            }

            if (($smtp->spam_complaints_24h ?? 0) > 0) {
                DB::table('smtp_servers')->where('id', $smtp->id)->update([
                    'hourly_limit' => max(10, (int) floor(($smtp->hourly_limit ?? 50) * 0.8)),
                    'updated_at' => now(),
                ]);
                $throttled++;
            }
        }

        $this->info("Admin SMTP protection run complete: paused={$paused}, throttled={$throttled}");

        return self::SUCCESS;
    }
}
