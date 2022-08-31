<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ArtistMapCircle extends Pivot
{
    protected $fillable = [
        'map_circle_id',
        'artist_id',
        'budget',
    ];
}
