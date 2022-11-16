<?php

namespace Database\Factories;

use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Promoter>
 */
class PromoterFactory extends UserFactory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return array_merge(parent::definition(), [
            'user_type' => User::TYPE_PROMOTER,
        ]);
    }
}
