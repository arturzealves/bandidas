<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapCircle extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'latitude',
        'longitude',
        'radius',
        'strokeColor',
        'strokeOpacity',
        'strokeWeight',
        'fillColor',
        'fillOpacity',
    ];

    public function artists()
    {
        return $this->belongsToMany(Artist::class)->withTimestamps();
    }

    public function promoterMarkers()
    {
        return $this->belongsToMany(MapMarker::class)->withTimestamps();
    }
}
