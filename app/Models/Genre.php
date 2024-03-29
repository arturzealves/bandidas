<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;
    use HasUuid;

    protected $primaryKey = 'uuid';

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    public function artists()
    {
        return $this->belongsToMany(Artist::class)->using(ArtistGenre::class);
    }
}
