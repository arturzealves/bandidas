<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapCircle extends Model
{
    use HasFactory;
    use HasUuid;

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
        'budget',
    ];

    public function artists()
    {
        return $this->belongsToMany(Artist::class)->withTimestamps();
    }

    public function promoterMarkers()
    {
        return $this->belongsToMany(MapMarker::class)->withTimestamps();
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
