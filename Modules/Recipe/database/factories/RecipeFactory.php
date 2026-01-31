<?php

namespace Modules\Recipe\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Platform\Models\BrewMethod;
use Modules\Recipe\Models\Recipe;

/**
 * @extends Factory<Recipe>
 */
class RecipeFactory extends Factory
{
    protected $model = Recipe::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->words(3, true) . ' Recipe';

        return [
            'user_id' => User::factory(),
            'brew_method_id' => BrewMethod::factory(),
            'name' => $name,
            'slug' => Str::slug($name) . '-' . Str::random(6),
            'description' => $this->faker->paragraph(),
            'coffee_dose' => $this->faker->randomElement(['15g', '18g', '20g', '22g']),
            'water_amount' => $this->faker->randomElement(['250ml', '300ml', '350ml', '400ml']),
            'water_temperature' => $this->faker->randomElement(['92°C', '93°C', '94°C', '96°C']),
            'grind_size' => $this->faker->randomElement(['Fine', 'Medium-fine', 'Medium', 'Medium-coarse', 'Coarse']),
            'total_brew_time' => $this->faker->randomElement(['2:30', '3:00', '3:30', '4:00']),
            'yield' => $this->faker->randomElement(['~200ml', '~250ml', '~300ml']),
            'is_public' => true,
            'views_count' => $this->faker->numberBetween(0, 1000),
        ];
    }

    /**
     * Indicate that the recipe is private.
     */
    public function private(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_public' => false,
        ]);
    }

    /**
     * Indicate that the recipe is public.
     */
    public function public(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_public' => true,
        ]);
    }
}



