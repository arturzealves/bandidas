<?php

namespace App\Models;

use Database\Mappers\DatabaseConstants;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use QCod\Gamify\Gamify;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use Gamify;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function type()
    {
        return $this->hasOne(UserType::class, 'id', 'user_type_id');
    }

    public function userAccessTokens()
    {
        return $this->hasMany(UserAccessToken::class, 'user_id', 'id');
    }
    
    public function location()
    {
        return $this->hasOne(UserLocation::class);
    }

    public function userExternalAccounts()
    {
        return $this->hasMany(UserExternalAccount::class, 'user_id', 'id');
    }

    public function mapCircles()
    {
        return $this->hasMany(MapCircle::class);
    }

    public function followedArtists()
    {
        return $this->belongsToMany(Artist::class, DatabaseConstants::TABLE_USER_FOLLOWS_ARTISTS)->withTimestamps();;
    }

    public function isPromoter()
    {
        return $this->user_type_id === UserType::TYPE_PROMOTER_ID;
    }
}
