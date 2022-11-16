<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLocation extends Model
{
    use HasFactory;
    use HasUuid;

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'user_id',
        'latitude',
        'longitude',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
