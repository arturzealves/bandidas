<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserExternalAccount extends Model
{
    const PROVIDER_SPOTIFY = 'spotify';
    
    use HasFactory;

    protected $fillable = [
        'user_id',
        'external_id',
        'provider_name',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
