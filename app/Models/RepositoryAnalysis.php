<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepositoryAnalysis extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'repository_id',
        'repo_name',
        'summary',
        'architecture_diagram',
        'readme',
        'files',
    ];

    protected $casts = [
        'files' => 'array',
    ];
}