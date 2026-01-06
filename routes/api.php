<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocsGenerationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/mock', function () {
    return [
        'status' => 'success',
        'message' => 'This is mock data',
        'data' => [
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ]
    ];
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/generate/docs', [DocsGenerationController::class, 'generate']);