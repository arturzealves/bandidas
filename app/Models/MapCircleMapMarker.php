<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class MapCircleMapMarker extends Pivot
{
    protected $fillable = [
        'map_circle_id',
        'map_marker_id',
        'distance',
    ];
}
