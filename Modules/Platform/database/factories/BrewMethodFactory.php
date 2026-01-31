<?php

namespace Modules\Platform\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Platform\Models\BrewMethod;

/**
 * @extends Factory<BrewMethod>
 */
class BrewMethodFactory extends Factory
{
    protected $model = BrewMethod::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['V60', 'Chemex', 'AeroPress', 'French Press', 'Kalita Wave', 'Origami', 'Clever Dripper']),
            'icon' => null,
        ];
    }
}



