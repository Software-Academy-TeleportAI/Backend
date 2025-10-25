<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserGithubCredentials extends Model
{
    protected $fillable = [
        'user_id',
        'access_token',
    ];
}
