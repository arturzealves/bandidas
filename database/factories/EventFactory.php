<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'uuid' => fake()->uuid(),
            'name' => fake()->words(3, true),
            'type' => Event::TYPE_MUSIC,
            // 'type' => fake()->randomElement([
            //     Event::TYPE_MUSIC,
            // ]),
            'images' => json_encode([
                'square64' => fake()->imageUrl(64, 64, 'artist', true),
                'square128' => fake()->imageUrl(128, 128, 'artist', true),
                'square256' => fake()->imageUrl(256, 256, 'artist', true),
                'square512' => fake()->imageUrl(512, 512, 'artist', true),
                'square1024' => fake()->imageUrl(1024, 1024, 'artist', true),
            ]),
            'description' => fake()->paragraph(),
            'latitude' => fake()->randomFloat(6, -90, 90),
            'longitude' => fake()->randomFloat(6, -180, 180),
            'address_uuid' => Address::factory(),
            'min_age' => fake()->numberBetween(1, 18),
        ];
    }
}
