<?php

namespace Tests\Feature;

use App\Models\FilterSubscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilterSubscriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_filter_subscription(): void
    {
        $response = $this->post('/filter-subscriptions', [
            'email' => 'coffee@example.com',
            'frequency' => 'instant',
            'filters' => [
                'companies' => [1, 2],
                'processes' => [3],
                'flavor_notes' => [],
                'varieties' => [],
                'countries' => [5],
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('filter_subscriptions', [
            'email' => 'coffee@example.com',
            'frequency' => 'instant',
        ]);

        $subscription = FilterSubscription::where('email', 'coffee@example.com')->first();
        $this->assertNotNull($subscription->verification_token);
        $this->assertNull($subscription->verified_at);
    }

    public function test_authenticated_user_subscription_is_linked_to_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/filter-subscriptions', [
                'email' => $user->email,
                'frequency' => 'daily',
                'filters' => [
                    'companies' => [],
                    'processes' => [1],
                    'flavor_notes' => [],
                    'varieties' => [],
                    'countries' => [],
                ],
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('filter_subscriptions', [
            'email' => $user->email,
            'user_id' => $user->id,
            'frequency' => 'daily',
        ]);
    }

    public function test_subscription_can_be_verified(): void
    {
        $subscription = FilterSubscription::factory()->create([
            'verification_token' => 'test-token-123',
            'verified_at' => null,
        ]);

        $response = $this->get('/filter-subscriptions/verify/test-token-123');

        $response->assertRedirect(route('offerings.index'));
        $response->assertSessionHas('success');

        $subscription->refresh();
        $this->assertNotNull($subscription->verified_at);
        $this->assertNull($subscription->verification_token);
    }

    public function test_invalid_verification_token_returns_404(): void
    {
        $response = $this->get('/filter-subscriptions/verify/invalid-token');

        $response->assertNotFound();
    }

    public function test_subscription_can_be_unsubscribed(): void
    {
        $subscription = FilterSubscription::factory()->verified()->create();

        $response = $this->delete("/filter-subscriptions/{$subscription->id}");

        $response->assertRedirect(route('offerings.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('filter_subscriptions', [
            'id' => $subscription->id,
        ]);
    }

    public function test_subscription_requires_valid_email(): void
    {
        $response = $this->post('/filter-subscriptions', [
            'email' => 'not-an-email',
            'frequency' => 'instant',
            'filters' => [
                'companies' => [],
                'processes' => [],
                'flavor_notes' => [],
                'varieties' => [],
                'countries' => [],
            ],
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_subscription_requires_valid_frequency(): void
    {
        $response = $this->post('/filter-subscriptions', [
            'email' => 'test@example.com',
            'frequency' => 'invalid',
            'filters' => [
                'companies' => [],
                'processes' => [],
                'flavor_notes' => [],
                'varieties' => [],
                'countries' => [],
            ],
        ]);

        $response->assertSessionHasErrors('frequency');
    }

    public function test_subscription_requires_filters(): void
    {
        $response = $this->post('/filter-subscriptions', [
            'email' => 'test@example.com',
            'frequency' => 'instant',
        ]);

        $response->assertSessionHasErrors('filters');
    }
}

