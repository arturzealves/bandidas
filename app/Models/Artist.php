<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Database\Mappers\DatabaseConstants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    use HasFactory;
    use HasUuid;

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'name',
    ];

    public function spotify()
    {
        return $this->hasOne(SpotifyArtist::class);
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class)->using(ArtistGenre::class);
    }

    public function mapCircles()
    {
        return $this->belongsToMany(MapCircle::class)
            ->using(ArtistMapCircle::class)
            ->withTimestamps();
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, DatabaseConstants::TABLE_USER_FOLLOWS_ARTISTS)
            ->using(UserFollowsArtist::class)
            ->withTimestamps();
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, DatabaseConstants::TABLE_EVENT_ARTISTS);
    }
}
