<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Home page - shows activities using activityList.blade.php
Route::get('/', [ActivityController::class, 'home'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Activity routes
Route::prefix('activities')->name('activities.')->group(function () {
    // Public index route - uses activity.index view
    Route::get('/', [ActivityController::class, 'index'])->name('index');
    
    // Protected routes (auth required)
    Route::middleware('auth')->group(function () {
        Route::get('/create', [ActivityController::class, 'create'])->name('create');
        Route::post('/', [ActivityController::class, 'store'])->name('store');
        Route::get('/{activity}/edit', [ActivityController::class, 'edit'])->name('edit');
        Route::patch('/{activity}', [ActivityController::class, 'update'])->name('update');
        Route::delete('/{activity}', [ActivityController::class, 'destroy'])->name('destroy');
    });
    
    // Show route comes last (dynamic parameter)
    Route::get('/{activity}', [ActivityController::class, 'show'])->name('show');
});

require __DIR__.'/auth.php';