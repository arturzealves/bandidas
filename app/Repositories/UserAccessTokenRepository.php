<?php

namespace App\Repositories;

use App\Models\UserAccessToken;

class UserAccessTokenRepository
{
    public function getOneByUserIdAndTokenableId($userId, $tokenableId): ?UserAccessToken
    {
        return UserAccessToken::where('user_id', $userId)
            ->where('tokenable_id', $tokenableId)
            ->first();
    }

    public function firstOrCreate(
        $userId,
        $tokenableId,
        $tokenableType,
        $name,
        $token,
        $refreshToken,
        array $abilities,
        $expiresIn
    ) {
        UserAccessToken::firstOrCreate(
            [
                'user_id' => $userId,
                'tokenable_id' => $tokenableId
            ],
            [
                'user_id' => $userId,
                'tokenable_type' => $tokenableType,
                'tokenable_id' => $tokenableId,
                'name' => $name,
                'token' => $token,
                'refresh_token' => $refreshToken,
                'abilities' => json_encode($abilities),
                'expires_in' => $expiresIn,
            ]
        );
    }
}
