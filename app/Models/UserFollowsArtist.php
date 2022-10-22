<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserFollowsArtist extends Pivot
{
    use HasUuid;

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'user_id',
        'artist_uuid',
    ];
}
