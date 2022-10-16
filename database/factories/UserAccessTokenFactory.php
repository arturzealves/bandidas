<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserAccessToken;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserAccessToken>
 */
class UserAccessTokenFactory extends Factory
{

    protected $model = UserAccessToken::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_uuid' => User::factory(),
            'tokenable_type' => UserAccessToken::TOKENABLE_TYPE_SPOTIFY_ACCESS_TOKEN,
            'tokenable_id' => UserAccessToken::TOKENABLE_ID_SPOTIFY_ACCESS_TOKEN,
            'name' => UserAccessToken::NAME_SPOTIFY_ACCESS_TOKEN,
            'token' => fake()->md5(),
            'refresh_token' => fake()->md5(),
            'abilities' => json_encode(explode(' ', fake()->catchPhrase())),
            'expires_in' => fake()->unixTime(),
            'last_used_at' => Carbon::now(),
        ];
    }
}
