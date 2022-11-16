<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAccessToken extends Model
{
    use HasFactory;
    use HasUuid;

    const TOKENABLE_TYPE_SPOTIFY_ACCESS_TOKEN = 'spotify_access_token';
    const TOKENABLE_ID_SPOTIFY_ACCESS_TOKEN = 1;
    const NAME_SPOTIFY_ACCESS_TOKEN = 'Spotify Access Token';

    protected $primaryKey = 'uuid';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'tokenable_type',
        'tokenable_id',
        'name',
        'token',
        'refresh_token',
        'abilities',
        'expires_in',
        'last_used_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'token',
        'refresh_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'last_used_at' => 'datetime',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
