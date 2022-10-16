<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpotifyArtist extends Model
{
    use HasFactory;
    use HasUuid;

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'artist_uuid',
        'spotify_id',
        'name',
        'uri',
        'images',
        'href',
        'followers',
        'popularity',
        'external_urls',
    ];

    public function artist()
    {
        return $this->hasOne(Artist::class);
    }

    public function getBiggerImage()
    {
        return json_decode($this->images)[0];
    }

    public function getMediumImage()
    {
        return json_decode($this->images)[1];
    }
    
    public function getSmallerImage()
    {
        return json_decode($this->images)[2];
    }
}
