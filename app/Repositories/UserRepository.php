<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function getCountByUserTypeId($id): int
    {
        return User::where('user_type_id', $id)->count();
    }
}
