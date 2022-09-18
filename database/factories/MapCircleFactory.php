<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MapCircle>
 */
class MapCircleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->word(),
            'latitude' => fake()->randomFloat(6, -90, 90),
            'longitude' => fake()->randomFloat(6, -180, 180),
            'radius' => fake()->numberBetween(1, 8388607),
            'strokeColor' => fake()->hexColor(),
            'strokeOpacity' => fake()->randomFloat(2, 0, 1),
            'strokeWeight' => fake()->randomFloat(2, 0, 1),
            'fillColor' => fake()->hexColor(),
            'fillOpacity' => fake()->randomFloat(2, 0, 1),
            'budget' => fake()->randomFloat(2, 0, 100),
        ];
    }
}
