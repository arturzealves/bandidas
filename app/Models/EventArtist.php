<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class EventArtist extends Pivot
{
    protected $fillable = [
        'event_uuid',
        'artist_uuid',
    ];
}
