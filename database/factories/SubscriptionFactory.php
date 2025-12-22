<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\Offering\Models\Subscription>
 */
class SubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => fake()->unique()->safeEmail(),
            'frequency' => fake()->randomElement(['instant', 'daily']),
            'filters' => [
                'companies' => [],
                'processes' => [],
                'flavor_notes' => [],
                'varieties' => [],
                'countries' => [],
            ],
            'verification_token' => Str::random(64),
            'verified_at' => null,
            'user_id' => null,
        ];
    }

    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'verified_at' => now(),
            'verification_token' => null,
        ]);
    }
}
