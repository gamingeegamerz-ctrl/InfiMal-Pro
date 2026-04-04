<?php

namespace Tests\Feature\Security;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WebhookSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_webhook_with_invalid_signature_is_rejected(): void
    {
        config()->set('services.paypal.webhook_id', 'WH-TEST-ID');

        Http::fake([
            '*/v1/oauth2/token' => Http::response(['access_token' => 'token'], 200),
            '*/v1/notifications/verify-webhook-signature' => Http::response(['verification_status' => 'FAILURE'], 200),
        ]);

        $payload = [
            'event_type' => 'PAYMENT.CAPTURE.COMPLETED',
            'resource' => ['id' => 'CAPTURE-1', 'supplementary_data' => ['related_ids' => ['order_id' => 'ORDER-1']]],
        ];

        $this->postJson(route('payment.webhook.paypal'), $payload, [
            'PAYPAL-AUTH-ALGO' => 'algo',
            'PAYPAL-CERT-URL' => 'https://example.com',
            'PAYPAL-TRANSMISSION-ID' => 'transmission',
            'PAYPAL-TRANSMISSION-SIG' => 'sig',
            'PAYPAL-TRANSMISSION-TIME' => now()->toIso8601String(),
        ])->assertStatus(401);
    }
}
