<?php

namespace Tests\Unit;

use App\Models\Subscriber;
use App\Models\User;
use App\Services\UsageLimitService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UsageLimitServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_campaign_limit_exceeded_when_limit_zero(): void
    {
        config()->set('infimal.limits.campaigns_per_day', 0);
        $user = User::factory()->create();

        $service = app(UsageLimitService::class);
        $this->assertTrue($service->campaignLimitExceeded($user));
    }

    public function test_subscriber_limit_exceeded_returns_true_at_threshold(): void
    {
        config()->set('infimal.limits.subscribers_per_user', 1);
        $user = User::factory()->create();

        Subscriber::factory()->create(['user_id' => $user->id]);

        $service = app(UsageLimitService::class);
        $this->assertTrue($service->subscriberLimitExceeded($user));
    }

    public function test_email_limit_exceeded_returns_true_at_threshold(): void
    {
        config()->set('infimal.limits.emails_per_day', 1);
        $user = User::factory()->create();

        DB::table('email_logs')->insert([
            'user_id' => $user->id,
            'to_email' => 'qa@example.com',
            'subject' => 'QA',
            'status' => 'sent',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $service = app(UsageLimitService::class);
        $this->assertTrue($service->emailLimitExceeded($user));
    }
}
