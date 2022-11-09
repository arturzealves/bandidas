<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Database\Mappers\DatabaseConstants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    use HasUuid;

    const TYPE_MUSIC = 'music';

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'name',
        'type',
        'images',
        'description',
        'latitude',
        'longitude',
        'min_age',
    ];

    public function address()
    {
        return $this->hasOne(Address::class, 'uuid', 'address_uuid');
    }

    public function sessions()
    {
        return $this->hasMany(EventSession::class);
    }

    public function prices()
    {
        return $this->hasMany(EventPrice::class);
    }

    public function artists()
    {
        return $this->belongsToMany(Artist::class, DatabaseConstants::TABLE_EVENT_ARTISTS);
    }

    public function promoters()
    {
        return $this->belongsToMany(
            User::class,
            DatabaseConstants::TABLE_EVENT_PROMOTERS,
            'event_uuid',
            'promoter_uuid'
        );
    }
}
