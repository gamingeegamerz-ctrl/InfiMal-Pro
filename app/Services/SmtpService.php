<?php

namespace App\Services;

use App\Models\SMTPAccount;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class SmtpService
{
    public function __construct(
        private readonly SmtpConnectionValidatorService $validator,
    ) {
    }

    public function saveForUser(int $userId, array $data, ?SMTPAccount $smtp = null): SMTPAccount
    {
        $smtp ??= new SMTPAccount();

        $validation = $this->validator->validate([
            'host' => $data['host'],
            'port' => (int) $data['port'],
            'username' => $data['username'],
            'password' => $data['password'] ?? $smtp->password,
            'encryption' => $data['encryption'],
            'from_address' => $data['from_address'] ?? $data['username'],
            'from_name' => $data['from_name'] ?? null,
        ], config('infimal.smtp_validation_probe_to'));

        if (! $validation['success']) {
            throw ValidationException::withMessages([
                'host' => 'SMTP validation failed: '.$validation['message'],
            ]);
        }

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
        $smtp->validation_status = $validation['status'];
        $smtp->validation_message = $validation['message'];
        $smtp->last_validated_at = now();

        if (! $smtp->exists && ! SMTPAccount::where('user_id', $userId)->exists()) {
            $smtp->is_default = true;
        }

        if (! empty($data['password'])) {
            $smtp->password = $data['password'];
        }

        $validation = $this->validateSmtpConnection(
            host: $smtp->host,
            port: (int) $smtp->port,
            timeoutSeconds: 5,
        );

        if (! $validation['success']) {
            throw new \RuntimeException('SMTP validation failed: '.$validation['message']);
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
        $result = $this->validator->validateModel($smtp, $toEmail);

        $smtp->forceFill([
            'is_active' => (bool) $result['success'],
            'validation_status' => $result['status'],
            'validation_message' => $result['message'],
            'last_validated_at' => now(),
        ])->save();

        if (! $result['success']) {
            return ['success' => false, 'message' => $result['message'], 'status' => $result['status']];
        }

        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp.host', $smtp->host);
        Config::set('mail.mailers.smtp.port', $smtp->port);
        Config::set('mail.mailers.smtp.encryption', $smtp->encryption === 'none' ? null : $smtp->encryption);
        Config::set('mail.mailers.smtp.username', $smtp->username);
        Config::set('mail.mailers.smtp.password', $smtp->password);
        Config::set('mail.from.address', $smtp->from_address);
        Config::set('mail.from.name', $smtp->from_name ?: 'InfiMal');

        Mail::raw('SMTP connection test from InfiMal.', function ($message) use ($toEmail) {
            $message->to($toEmail)->subject('InfiMal SMTP Test');
        });

        return ['success' => true, 'message' => 'SMTP validated and test email sent.', 'status' => $result['status']];
    }

    public function validateSmtpConnection(string $host, int $port, int $timeoutSeconds = 5): array
    {
        $connection = @fsockopen($host, $port, $errno, $errstr, $timeoutSeconds);

        if (! $connection) {
            return [
                'success' => false,
                'message' => "Could not connect to {$host}:{$port} ({$errno}: {$errstr})",
            ];
        }

        fclose($connection);

        return ['success' => true, 'message' => 'SMTP endpoint reachable'];
    }
}
