<?php

namespace App\Services;

use App\Models\SMTPAccount;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class SmtpService
{
    public function saveForUser(int $userId, array $data, ?SMTPAccount $smtp = null): SMTPAccount
    {
        $smtp ??= new SMTPAccount();
        $smtp->user_id = $userId;
        $smtp->name = $data['name'] ?? $smtp->name ?? 'SMTP';
        $smtp->host = $data['host'];
        $smtp->port = (int) $data['port'];
        $smtp->username = $data['username'];
        $smtp->encryption = $data['encryption'];
        $smtp->from_address = $data['from_address'] ?? $data['username'];
        $smtp->from_name = $data['from_name'] ?? null;
        $smtp->daily_limit = (int) ($data['daily_limit'] ?? 500);
        $smtp->per_minute_limit = (int) ($data['per_minute_limit'] ?? 30);
        $smtp->warmup_enabled = (bool) ($data['warmup_enabled'] ?? true);
        $smtp->is_active = true;
        $smtp->is_admin_pool = false;
        if (! $smtp->exists && ! SMTPAccount::where('user_id', $userId)->exists()) {
            $smtp->is_default = true;
        }

        if (! empty($data['password'])) {
            $smtp->password = $data['password'];
        }

        $smtp->save();

        return $smtp;
    }

    public function setDefault(SMTPAccount $smtp): void
    {
        SMTPAccount::where('user_id', $smtp->user_id)->update(['is_default' => false]);
        $smtp->update(['is_default' => true]);
    }

    public function testConnection(SMTPAccount $smtp, string $toEmail): array
    {
        $probeKey = 'smtp_probe_next_'.$smtp->id;
        $nextAllowed = Cache::get($probeKey);

        if ($nextAllowed && now()->lt($nextAllowed)) {
            return ['success' => false, 'message' => 'Probe cooldown active. Try again later to avoid repeated probing patterns.'];
        }

        $target = $this->rotateProbeInbox($smtp, $toEmail);

        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp.host', $smtp->host);
        Config::set('mail.mailers.smtp.port', $smtp->port);
        Config::set('mail.mailers.smtp.encryption', $smtp->encryption === 'none' ? null : $smtp->encryption);
        Config::set('mail.mailers.smtp.username', $smtp->username);
        Config::set('mail.mailers.smtp.password', $smtp->password);
        Config::set('mail.from.address', $smtp->from_address);
        Config::set('mail.from.name', $smtp->from_name ?: 'InfiMal');

        try {
            Mail::raw('SMTP connection test from InfiMal.', function ($message) use ($target) {
                $message->to($target)->subject('InfiMal SMTP Test');
            });

            Cache::put($probeKey, now()->addMinutes(15), now()->addMinutes(15));

            return ['success' => true, 'message' => 'SMTP test email sent successfully to rotated probe inbox.'];
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => $this->sanitizeError($e->getMessage(), $smtp)];
        }
    }

    private function sanitizeError(string $message, SMTPAccount $smtp): string
    {
        $masked = str_replace([(string) $smtp->username, (string) $smtp->password], ['[masked-user]', '[masked-pass]'], $message);

        return substr($masked, 0, 300);
    }

    private function rotateProbeInbox(SMTPAccount $smtp, string $fallback): string
    {
        $pool = array_values(array_unique(array_filter([
            $fallback,
            config('mail.from.address'),
            'deliverability-check+1@infimal.local',
            'deliverability-check+2@infimal.local',
        ])));

        $indexKey = 'smtp_probe_inbox_index_'.$smtp->id;
        $index = (int) Cache::get($indexKey, 0);
        $target = $pool[$index % count($pool)] ?? $fallback;
        Cache::put($indexKey, ($index + 1) % max(1, count($pool)), now()->addDay());

        return $target;
    }
}
