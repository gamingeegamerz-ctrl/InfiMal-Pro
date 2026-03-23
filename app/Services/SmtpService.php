<?php

namespace App\Services;

use App\Models\SMTPAccount;
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
        if (! $smtp->exists && ! SMTPAccount::where('user_id', $userId)->exists()) {
            $smtp->is_default = true;
        }

        if (!empty($data['password'])) {
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
        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp.host', $smtp->host);
        Config::set('mail.mailers.smtp.port', $smtp->port);
        Config::set('mail.mailers.smtp.encryption', $smtp->encryption === 'none' ? null : $smtp->encryption);
        Config::set('mail.mailers.smtp.username', $smtp->username);
        Config::set('mail.mailers.smtp.password', $smtp->password);
        Config::set('mail.from.address', $smtp->from_address);
        Config::set('mail.from.name', $smtp->from_name ?: 'InfiMal');

        try {
            Mail::raw('SMTP connection test from InfiMal.', function ($message) use ($toEmail) {
                $message->to($toEmail)->subject('InfiMal SMTP Test');
            });

            return ['success' => true, 'message' => 'SMTP test email sent successfully.'];
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
