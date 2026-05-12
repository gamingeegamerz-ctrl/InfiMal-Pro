<?php

namespace App\Services;

use App\Models\SentEmail;
use Aws\Ses\SesClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SesService
{
    public const CONFIGURATION_SET = 'infimail-tracker';

    public function __construct(private readonly EmailRateLimiter $rateLimiter)
    {
    }

    public function verifyDomain(string $domain): string
    {
        $result = $this->client()->verifyDomainIdentity(['Domain' => $domain]);

        return (string) $result->get('VerificationToken');
    }

    public function checkDomainVerification(string $domain): string
    {
        $result = $this->client()->getIdentityVerificationAttributes(['Identities' => [$domain]]);
        $attributes = $result->get('VerificationAttributes') ?: [];

        return $attributes[$domain]['VerificationStatus'] ?? 'NotStarted';
    }

    public function sendEmail(string $from, string $to, string $subject, string $htmlBody, int $userId, ?int $campaignId = null): SentEmail
    {
        $messageId = sprintf('<%s@%s>', Str::uuid(), parse_url(config('app.url'), PHP_URL_HOST) ?: 'infimail.local');
        $raw = $this->buildRawMessage($from, $to, $subject, $htmlBody, $messageId);

        $result = $this->client()->sendRawEmail([
            'ConfigurationSetName' => self::CONFIGURATION_SET,
            'Source' => $from,
            'Destinations' => [$to],
            'RawMessage' => ['Data' => $raw],
            'Tags' => [
                ['Name' => 'user_id', 'Value' => (string) $userId],
                ['Name' => 'campaign_id', 'Value' => (string) ($campaignId ?? 0)],
            ],
        ]);

        $sesMessageId = (string) ($result->get('MessageId') ?: trim($messageId, '<>'));

        $sentEmail = SentEmail::create([
            'user_id' => $userId,
            'campaign_id' => $campaignId,
            'message_id' => $sesMessageId,
            'recipient_email' => $to,
            'from_email' => $from,
            'subject' => $subject,
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        $user = \App\Models\User::find($userId);
        if ($user) {
            $this->rateLimiter->registerSend($user);
        }

        Log::info('SES email sent', ['user_id' => $userId, 'campaign_id' => $campaignId, 'message_id' => $sesMessageId]);

        return $sentEmail;
    }

    private function client(): SesClient
    {
        return new SesClient([
            'version' => '2010-12-01',
            'region' => config('services.ses.region', env('AWS_DEFAULT_REGION', 'us-east-1')),
            'credentials' => [
                'key' => config('services.ses.key', env('AWS_ACCESS_KEY_ID')),
                'secret' => config('services.ses.secret', env('AWS_SECRET_ACCESS_KEY')),
            ],
        ]);
    }

    private function buildRawMessage(string $from, string $to, string $subject, string $htmlBody, string $messageId): string
    {
        $boundary = 'infimail_'.Str::random(32);
        $encodedSubject = mb_encode_mimeheader($subject, 'UTF-8');

        return implode("\r\n", [
            "From: {$from}",
            "To: {$to}",
            "Subject: {$encodedSubject}",
            "Message-ID: {$messageId}",
            'MIME-Version: 1.0',
            "Content-Type: multipart/alternative; boundary=\"{$boundary}\"",
            'X-SES-CONFIGURATION-SET: '.self::CONFIGURATION_SET,
            '',
            "--{$boundary}",
            'Content-Type: text/plain; charset=UTF-8',
            'Content-Transfer-Encoding: quoted-printable',
            '',
            quoted_printable_encode(trim(strip_tags($htmlBody))),
            "--{$boundary}",
            'Content-Type: text/html; charset=UTF-8',
            'Content-Transfer-Encoding: quoted-printable',
            '',
            quoted_printable_encode($htmlBody),
            "--{$boundary}--",
        ]);
    }
}
