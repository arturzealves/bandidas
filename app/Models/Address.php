<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    use HasUuid;

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'line1',
        'line2',
        'line3',
        'line4',
        'city',
        'region',
        'postal_code',
        'country',
    ];

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
