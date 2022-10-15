<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class EventPromoter extends Pivot
{
    protected $fillable = [
        'event_uuid',
        'promoter_uuid',
    ];
}
