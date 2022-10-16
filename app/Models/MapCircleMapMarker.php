<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Relations\Pivot;

class MapCircleMapMarker extends Pivot
{
    use HasUuid;

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'map_circle_uuid',
        'map_marker_uuid',
        'distance',
    ];
}
