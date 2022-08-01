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
}
