<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleMapsUserCirclesHasArtist extends Model
{
    use HasFactory;

    protected $fillable = [
        'google_maps_user_circle_id',
        'artist_id',
        'budget',
    ];

    public function artist()
    {
        return $this->hasOne(Artist::class);
    }

    public function googleMapsUserCircle()
    {
        return $this->hasOne(GoogleMapsUserCircle::class);
    }
}
