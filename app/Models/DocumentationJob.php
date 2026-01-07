<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentationJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'repository_url',
        'repository_name',
        'status',
        'result'
    ];

    protected $casts = [
        'result' => 'array',
    ];
}