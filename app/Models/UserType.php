<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    use HasFactory;

    const TYPE_USER_ID = 1;
    const TYPE_USER = 'user';
    const TYPE_PROMOTER_ID = 2;
    const TYPE_PROMOTER = 'promoter';
    const TYPE_ARTIST_ID = 3;
    const TYPE_ARTIST = 'artist';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
    ];

    public function getUsers()
    {
        return $this->hasMany(User::class, 'user_type_id', 'id');
    }
}
