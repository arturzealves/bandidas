<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpotifyArtist extends Model
{
    use HasFactory;

    protected $fillable = [
        'artist_id',
        'spotify_id',
        'name',
        'uri',
        'images',
        'href',
        'followers',
        'popularity',
        'external_urls',
    ];
}
