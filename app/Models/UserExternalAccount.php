<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserExternalAccount extends Model
{   
    use HasFactory;
    use HasUuid;

    const PROVIDER_SPOTIFY = 'spotify';

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'user_uuid',
        'external_id',
        'provider_name',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'uuid', 'user_uuid');
    }
}
