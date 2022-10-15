<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EventPrice>
 */
class EventPriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'event_uuid' => Event::factory(),
            'price' => fake()->randomFloat(2, 5, 300),
            'date' => fake()->dateTime(),
            'age' => fake()->randomNumber(2, 18),
            'description' => fake()->sentence(),
        ];
    }
}
