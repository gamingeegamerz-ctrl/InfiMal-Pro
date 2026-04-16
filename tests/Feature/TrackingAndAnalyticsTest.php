<?php

namespace Tests\Feature;

use App\Models\EmailLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TrackingAndAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_bounce_webhook_updates_specific_email_log(): void
    {
        $user = User::factory()->create();
        $log = EmailLog::create([
            'user_id' => $user->id,
            'to_email' => 'recipient@example.com',
            'recipient_email' => 'recipient@example.com',
            'subject' => 'Test',
            'status' => 'sent',
        ]);

        $response = $this->postJson('/api/track/bounce', [
            'email_log_id' => $log->id,
            'type' => 'hard',
            'reason' => 'Mailbox not found',
        ]);

        $response->assertOk()->assertJson(['success' => true]);

        $this->assertDatabaseHas('email_logs', [
            'id' => $log->id,
            'status' => 'bounced',
            'error_message' => 'Mailbox not found',
        ]);
    }

    public function test_api_stats_are_scoped_per_user(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        EmailLog::create([
            'user_id' => $userA->id,
            'to_email' => 'a@example.com',
            'subject' => 'A',
            'status' => 'sent',
            'opened' => true,
            'clicked' => true,
        ]);

        EmailLog::create([
            'user_id' => $userB->id,
            'to_email' => 'b@example.com',
            'subject' => 'B',
            'status' => 'bounced',
            'opened' => false,
            'clicked' => false,
        ]);

        Sanctum::actingAs($userA);

        $response = $this->getJson('/api/stats');

        $response->assertOk();
        $response->assertJsonPath('total_sent', 1);
        $response->assertJsonPath('bounces', 0);
        $response->assertJsonPath('opens', 1);
        $response->assertJsonPath('clicks', 1);
    }
}
