<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocsGenerationController;
use App\Http\Controllers\IntegrationController;
use App\Http\Controllers\UserGithubController;
use App\Http\Controllers\AnalysisRepoController;

Route::post('/webhook/docs-generated', [IntegrationController::class, 'handleWebhook'])
    ->name('api.webhook.docs_generated');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::post('/user/github_access_token', [UserGithubController::class, 'setGithubAccess']);
    Route::post('/user/auth_token', [AuthController::class, 'verifyAuthToken']);
    Route::post('/generate', [IntegrationController::class, 'startGeneration']);
    Route::get('/generate/status/{id}', [IntegrationController::class, 'checkStatus']);
    Route::post('/repository/analysis', [AnalysisRepoController::class, 'storeAnalysis']);
    Route::get('/repository/analysis', [AnalysisRepoController::class, 'index']);
    Route::get('/repository/analysis/{id}', [AnalysisRepoController::class, 'show']);
    Route::put('/repository/analysis/{id}', [AnalysisRepoController::class, 'update']);
  
});

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