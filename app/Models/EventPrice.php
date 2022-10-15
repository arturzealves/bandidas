<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventPrice extends Model
{
    use HasFactory;
    use HasUuid;

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'event_uuid',
        'price',
        'date',
        'age',
        'description',
    ];

    public function event()
    {
        return $this->hasOne(Event::class);
    }
}
