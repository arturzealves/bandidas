<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promoter extends User
{
    use HasFactory;

    protected $table = 'users';

    public static function boot()
    {
        parent::boot();
 
        static::addGlobalScope(function ($query) {
            $query->where('user_type', User::TYPE_PROMOTER);
        });
    }
}
