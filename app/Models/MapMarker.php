<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapMarker extends Model
{
    use HasFactory;
    use HasUuid;

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'user_uuid',
        'latitude',
        'longitude',
    ];

    public function userCircles()
    {
        return $this->belongsToMany(MapCircle::class)
            ->using(MapCircleMapMarker::class)
            ->withTimestamps();
    }
}
