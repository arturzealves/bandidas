<?php

namespace App\Repositories;

use App\Models\UserAccessToken;

class UserAccessTokenRepository
{
    public function getOneByuserIdAndTokenableId($userId, $tokenableId): ?UserAccessToken
    {
        return UserAccessToken::where('user_id', $userId)
            ->where('tokenable_id', $tokenableId)
            ->first();
    }

    public function updateOrCreate(
        $userId,
        $tokenableId,
        $tokenableType,
        $name,
        $token,
        $refreshToken,
        array $abilities,
        $expiresIn
    ) {
        UserAccessToken::updateOrCreate(
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
