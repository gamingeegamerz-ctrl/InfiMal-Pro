<?php

namespace App\Console\Commands;

use App\Services\ReputationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class EnforceAdminSmtpProtection extends Command
{
    protected $signature = 'infimal:enforce-admin-smtp-protection {--global-max=200000} {--campaign-max=50000}';

    protected $description = 'Apply admin SMTP IP/domain/global limits and anomaly auto-actions';

    public function handle(ReputationService $reputation): int
    {
        $globalMax = (int) $this->option('global-max');
        $campaignMax = (int) $this->option('campaign-max');

        $totalToday = DB::table('email_logs')->whereDate('created_at', today())->count();
        if ($totalToday >= $globalMax) {
            DB::table('smtp_servers')->where('is_active', true)->update([
                'is_paused' => true,
                'is_active' => false,
                'last_skipped_reason' => 'Global daily cap exceeded',
                'updated_at' => now(),
            ]);
        }

        DB::table('email_logs')
            ->select('campaign_id', DB::raw('COUNT(*) as sent_count'))
            ->whereDate('created_at', today())
            ->whereNotNull('campaign_id')
            ->groupBy('campaign_id')
            ->havingRaw('COUNT(*) >= ?', [$campaignMax])
            ->get()
            ->each(function ($row): void {
                DB::table('campaigns')->where('id', $row->campaign_id)->update([
                    'status' => 'paused',
                    'updated_at' => now(),
                ]);
            });
    protected $signature = 'infimal:enforce-admin-smtp-protection';

    protected $description = 'Apply admin SMTP IP/domain limits and anomaly auto-actions';

    public function handle(): int
    {
        $globalMax = (int) config('infimal.admin_smtp.global_max_emails_per_day', 100000);
        $maxPerCampaign = (int) config('infimal.admin_smtp.max_per_campaign', 10000);
        $globalUsage = (int) DB::table('smtp_servers')->sum('emails_today');

        if ($globalUsage >= $globalMax) {
            DB::table('smtp_servers')->update([
                'is_paused' => true,
                'is_active' => false,
                'last_skipped_reason' => 'Global admin SMTP cap reached',
                'updated_at' => now(),
            ]);
            $this->warn('Global admin SMTP cap reached, all admin SMTP paused.');

            return self::SUCCESS;
        }

        $smtps = DB::table('smtp_servers')->get();
        $paused = 0;
        $throttled = 0;
        $recovered = 0;

        foreach ($smtps as $smtp) {
            $dailyLimitHit = ($smtp->emails_today ?? 0) >= ($smtp->max_daily_per_ip ?? 5000);
            $domainLimitHit = ($smtp->emails_today ?? 0) >= ($smtp->max_daily_per_domain ?? 2000);
            $bounceRate = $this->bounceRate($smtp->id);
            $anomaly = ($smtp->hard_bounces_24h ?? 0) > 100 || ($smtp->spam_complaints_24h ?? 0) > 20;

            if ($dailyLimitHit || $domainLimitHit || $anomaly || $reputation->shouldThrottle((int) $smtp->id, $bounceRate)) {
            $campaignOver = DB::table('email_logs')
                ->where('smtp_id', $smtp->id)
                ->whereDate('created_at', today())
                ->select('campaign_id')
                ->selectRaw('COUNT(*) as total')
                ->groupBy('campaign_id')
                ->havingRaw('COUNT(*) >= ?', [$maxPerCampaign])
                ->exists();
            $anomaly = ($smtp->hard_bounces_24h ?? 0) > 100 || ($smtp->spam_complaints_24h ?? 0) > 20;

            if ($dailyLimitHit || $domainLimitHit || $anomaly || $campaignOver) {
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

            if ($smtp->is_paused && $reputation->canRecover((int) $smtp->id, $bounceRate)) {
                DB::table('smtp_servers')->where('id', $smtp->id)->update([
                    'is_paused' => false,
                    'is_active' => true,
                    'hourly_limit' => min((int) (($smtp->hourly_limit ?? 50) + 5), 200),
                    'last_skipped_reason' => null,
                    'updated_at' => now(),
                ]);
                $recovered++;
            }
        }

        $this->info("Admin SMTP protection complete: paused={$paused}, throttled={$throttled}, recovered={$recovered}, totalToday={$totalToday}");

        return self::SUCCESS;
    }

    private function bounceRate(int $smtpId): float
    {
        $total = DB::table('email_logs')->where('smtp_id', $smtpId)->whereDate('created_at', today())->count();
        if ($total === 0) {
            return 0.0;
        }

        $bounced = DB::table('email_logs')->where('smtp_id', $smtpId)->where('status', 'bounced')->whereDate('created_at', today())->count();

        return ($bounced / $total) * 100;
    }
        }

        $this->info("Admin SMTP protection run complete: paused={$paused}, throttled={$throttled}");

        return self::SUCCESS;
    }
}
