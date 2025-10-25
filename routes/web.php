<?php

use App\Http\Controllers\PlaygroundController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/playground', [PlaygroundController::class, 'index'])->name('playground.index');
    Route::post('/playground', [PlaygroundController::class, 'store'])->name('playground.store');
});

require __DIR__.'/settings.php';
