<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGithub extends Model
{
    use HasFactory;

    protected $table = 'users_github_credentials';

    protected $fillable = [
        'user_id',       
        'access_token',
    ];

    protected $hidden = [
        'access_token',
    ];

    protected $casts = [
        'access_token' => 'encrypted', 
    ];
    
    public function user() {
        return $this->belongsTo(User::class);
    }
}