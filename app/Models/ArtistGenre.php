<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ArtistGenre extends Pivot
{
    use HasUuid;
    
    // public $timestamps = false;
    
    protected $primaryKey = 'uuid';

    protected $fillable = [
        'artist_uuid',
        'genre_id',
    ];
}
