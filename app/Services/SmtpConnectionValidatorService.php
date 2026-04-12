<?php

namespace App\Services;

use App\Models\SMTPAccount;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;

class SmtpConnectionValidatorService
{
    public function validate(array $config, ?string $probeRecipient = null): array
    {
        $host = (string) ($config['host'] ?? '');
        $port = (int) ($config['port'] ?? 587);
        $encryption = (string) ($config['encryption'] ?? 'tls');
        $username = $config['username'] ?? null;
        $password = $config['password'] ?? null;

        try {
            $transport = new EsmtpTransport($host, $port, $encryption === 'none' ? null : $encryption);

            if ($username) {
                $transport->setUsername((string) $username);
                $transport->setPassword((string) $password);
            }

            // Real SMTP check: open transport (EHLO + AUTH when creds are set).
            $transport->start();
            $transport->stop();

            $status = 'valid';
            $message = 'SMTP handshake successful.';

            if ($probeRecipient) {
                config([
                    'mail.default' => 'smtp',
                    'mail.mailers.smtp.host' => $host,
                    'mail.mailers.smtp.port' => $port,
                    'mail.mailers.smtp.encryption' => $encryption === 'none' ? null : $encryption,
                    'mail.mailers.smtp.username' => $username,
                    'mail.mailers.smtp.password' => $password,
                    'mail.from.address' => $config['from_address'] ?? $username,
                    'mail.from.name' => $config['from_name'] ?? 'InfiMal Validator',
                ]);

                Mail::raw('InfiMal SMTP probe', function ($mail) use ($probeRecipient): void {
                    $mail->to($probeRecipient)->subject('SMTP Probe');
                });

                $message = 'SMTP handshake + probe send successful.';
            }

            if ($port === 25 || $encryption === 'none') {
                $status = 'risky';
                $message .= ' Configuration is deliverability-risky (port 25 or no TLS).';
            }

            return ['success' => true, 'status' => $status, 'message' => $message];
        } catch (\Throwable $e) {
            return ['success' => false, 'status' => 'invalid', 'message' => substr($e->getMessage(), 0, 1000)];
        }
    }

    public function validateModel(SMTPAccount $smtp, ?string $probeRecipient = null): array
    {
        return $this->validate([
            'host' => $smtp->host,
            'port' => $smtp->port,
            'encryption' => $smtp->encryption,
            'username' => $smtp->username,
            'password' => $smtp->password,
            'from_address' => $smtp->from_address,
            'from_name' => $smtp->from_name,
        ], $probeRecipient);
    }
}
