<?php

namespace App\Services;

use App\Models\SenderDomain;
use Illuminate\Support\Str;

class DomainVerificationService
{
    public function createDomainPayload(string $domain): array
    {
        $token = Str::lower(Str::random(32));

        return [
            'verification_token' => $token,
            'dns_records' => [
                'spf' => [
                    'type' => 'TXT',
                    'host' => $domain,
                    'value' => 'v=spf1 include:_spf.infimal.com ~all',
                ],
                'dkim' => [
                    'type' => 'TXT',
                    'host' => 'infimal._domainkey.'.$domain,
                    'value' => 'v=DKIM1; k=rsa; p='.config('infimal.deliverability.dkim_private_key', 'replace_with_public_key'),
                ],
                'dmarc' => [
                    'type' => 'TXT',
                    'host' => '_dmarc.'.$domain,
                    'value' => 'v=DMARC1; p=none; rua=mailto:dmarc@'.$domain,
                ],
                'verification' => [
                    'type' => 'TXT',
                    'host' => '_infimal-verification.'.$domain,
                    'value' => 'infimal-verification='.$token,
                ],
            ],
        ];
    }

    public function verify(SenderDomain $domain): bool
    {
        $txtRecords = @dns_get_record('_infimal-verification.'.$domain->domain, DNS_TXT);

        if (! is_array($txtRecords)) {
            return false;
        }

        $expected = 'infimal-verification='.$domain->verification_token;
        foreach ($txtRecords as $record) {
            if (($record['txt'] ?? null) === $expected) {
                $domain->forceFill([
                    'is_verified' => true,
                    'verified_at' => now(),
                ])->save();

                return true;
            }
        }

        return false;
    }
}
