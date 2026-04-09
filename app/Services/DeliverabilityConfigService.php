<?php

namespace App\Services;

class DeliverabilityConfigService
{
    public function status(): array
    {
        $spf = (string) config('infimal.deliverability.spf', '');
        $dkimSelector = (string) config('infimal.deliverability.dkim_selector', '');
        $dkimDomain = (string) config('infimal.deliverability.dkim_domain', '');
        $dmarc = (string) config('infimal.deliverability.dmarc', '');

        return [
            'spf_configured' => $spf !== '',
            'dkim_configured' => $dkimSelector !== '' && $dkimDomain !== '',
            'dmarc_configured' => $dmarc !== '',
        ];
    }
}
