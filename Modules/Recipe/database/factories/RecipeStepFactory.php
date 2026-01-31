<?php

namespace Modules\Recipe\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Recipe\Models\Recipe;
use Modules\Recipe\Models\RecipeStep;

/**
 * @extends Factory<RecipeStep>
 */
class RecipeStepFactory extends Factory
{
    protected $model = RecipeStep::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'recipe_id' => Recipe::factory(),
            'order' => $this->faker->numberBetween(1, 10),
            'title' => $this->faker->randomElement(['Bloom', 'First Pour', 'Second Pour', 'Third Pour', 'Final Pour', 'Draw Down', 'Wait']),
            'description' => $this->faker->sentence(),
            'duration' => $this->faker->randomElement(['0:00 - 0:45', '0:45 - 1:30', '1:30 - 2:15', '2:15 - 3:00']),
            'water_amount' => $this->faker->randomElement(['50ml', '75ml', '100ml', '150ml']),
        ];
    }
}



