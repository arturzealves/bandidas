<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function getCountByUserType($type): int
    {
        return User::where('user_type', $type)->count();
    }
}
