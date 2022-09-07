<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserFollowsArtist extends Pivot
{
    protected $fillable = [
        'user_id',
        'artist_id',
    ];
}
