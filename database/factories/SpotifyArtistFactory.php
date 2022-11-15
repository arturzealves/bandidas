<?php

namespace Database\Factories;

use App\Models\Artist;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SpotifyArtist>
 */
class SpotifyArtistFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'artist_uuid' => Artist::factory(),
            'spotify_id' => $this->faker->md5(),
            'name' => $this->faker->name(),
            'uri' => 'spotify:artist:' . $this->faker->md5(),
            'name' => $this->faker->name(),
            'images' => json_encode([
                [
                    "url" => $this->faker->imageUrl(640, 640),
                    "width" => 640,
                    "height" => 640
                ],
                [
                    "url" => $this->faker->imageUrl(320, 320),
                    "width" => 320,
                    "height" => 320
                ],
                [
                    "url" => $this->faker->imageUrl(160, 160),
                    "width" => 160,
                    "height" => 160
                ],
            ]),
            'href' => $this->faker->url(),
            'followers' => $this->faker->randomNumber(),
            'popularity' => $this->faker->numberBetween(0, 100),
            'external_urls' => json_encode(
                [
                    "spotify" => $this->faker->url(),
                ]
            ),
        ];
    }
}
