<?php

namespace App\Repositories;

use App\Models\UserAccessToken;

class UserAccessTokenRepository
{
    public function getOneByUserUuidAndTokenableId($userUuid, $tokenableId): ?UserAccessToken
    {
        return UserAccessToken::where('user_uuid', $userUuid)
            ->where('tokenable_id', $tokenableId)
            ->first();
    }

    public function updateOrCreate(
        $userUuid,
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
                'user_uuid' => $userUuid,
                'tokenable_id' => $tokenableId
            ],
            [
                'user_uuid' => $userUuid,
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
