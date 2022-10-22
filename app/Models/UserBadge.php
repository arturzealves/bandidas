<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserBadge extends Pivot
{
    protected $fillable = [
        'user_id',
        'badge_id',
    ];
}
