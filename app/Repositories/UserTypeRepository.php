<?php

namespace App\Repositories;

use App\Interfaces\UserTypeRepositoryInterface;
use App\Models\UserType;

class UserTypeRepository implements UserTypeRepositoryInterface 
{
    public function getAll()
    {
        return UserType::all();
    }
}
