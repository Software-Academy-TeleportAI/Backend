<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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