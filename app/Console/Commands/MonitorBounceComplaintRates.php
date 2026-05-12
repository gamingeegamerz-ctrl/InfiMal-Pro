<?php

namespace App\Console\Commands;

use App\Models\Bounce;
use App\Models\Complaint;
use App\Models\SentEmail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class MonitorBounceComplaintRates extends Command
{
    protected $signature = 'infimail:monitor-reputation';
    protected $description = 'Suspend users whose 7-day bounce or complaint rates exceed safety thresholds.';

    public function handle(): int
    {
        $since = now()->subDays(7);

        User::query()->where('suspended', false)->chunkById(250, function ($users) use ($since): void {
            foreach ($users as $user) {
                $sent = SentEmail::where('user_id', $user->id)->where('sent_at', '>=', $since)->count();
                if ($sent === 0) {
                    continue;
                }

                $bounces = Bounce::where('user_id', $user->id)->where('bounced_at', '>=', $since)->count();
                $complaints = Complaint::where('user_id', $user->id)->where('complained_at', '>=', $since)->count();
                $bounceRate = ($bounces / $sent) * 100;
                $complaintRate = ($complaints / $sent) * 100;

                if ($bounceRate > 5 || $complaintRate > 0.5) {
                    $reason = sprintf('Your account was suspended because your last 7-day bounce rate is %.2f%% and complaint rate is %.2f%%. Please clean your list before sending again.', $bounceRate, $complaintRate);
                    $user->forceFill(['suspended' => true, 'suspension_reason' => $reason])->save();

                    Mail::raw($reason, function ($message) use ($user): void {
                        $message->to($user->email)->subject('Infimail account sending paused');
                    });

                    $this->warn('Suspended user '.$user->id.': '.$reason);
                }
            }
        });

        return self::SUCCESS;
    }
}
