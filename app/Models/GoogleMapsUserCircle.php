<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleMapsUserCircle extends Model
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
}
