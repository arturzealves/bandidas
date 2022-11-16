<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EventSession>
 */
class EventSessionFactory extends Factory
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
            'start' => fake()->dateTimeBetween('-1 week', '+1 year'),
            'end' => fake()->dateTimeBetween('-1 week', '+1 year'),
        ];
    }
}
