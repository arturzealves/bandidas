<?php

namespace App\Repositories;

use App\Interfaces\UserTypeRepositoryInterface;
use App\Models\UserType;
use Illuminate\Database\Eloquent\Collection;

class UserTypeRepository implements UserTypeRepositoryInterface 
{
    public function getAll(): Collection
    {
        return UserType::all();
    }

    public function getByName($name): UserType
    {
        return UserType::where('name', $name)->first();
    }
}
