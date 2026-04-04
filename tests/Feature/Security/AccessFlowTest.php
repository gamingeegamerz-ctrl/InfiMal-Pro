<?php

namespace Tests\Feature\Security;

use App\Models\License;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_unpaid_user_cannot_access_dashboard(): void
    {
        $user = User::factory()->create(['is_paid' => false, 'payment_status' => 'unpaid']);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertRedirect(route('payment'));
    }

    public function test_paid_but_unverified_user_cannot_access_dashboard(): void
    {
        $user = User::factory()->create([
            'is_paid' => true,
            'payment_status' => 'paid',
            'otp_verified_at' => null,
        ]);

        License::create([
            'user_id' => $user->id,
            'license_key' => 'INFIMAL-TEST-KEY-123456',
            'plan_type' => 'InfiMal Pro Lifetime',
            'price' => 299,
            'duration_days' => 0,
            'status' => 'active',
            'is_active' => true,
            'is_lifetime' => true,
        ]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertRedirect(route('otp.verify.form'));
    }
}
