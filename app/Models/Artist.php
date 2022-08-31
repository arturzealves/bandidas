<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function spotify()
    {
        return $this->hasOne(SpotifyArtist::class);
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    public function mapCircles()
    {
        return $this->belongsToMany(MapCircle::class)->withTimestamps();
    }
}
