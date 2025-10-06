<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Home page
Route::get('/', [ActivityController::class, 'home'])->name('home');

// Dashboard - shows user's activities
Route::get('/dashboard', [ActivityController::class, 'myActivities'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // User management
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users/store', [UserController::class, 'store'])->name('users.store');
    Route::get('users/show/{user}', [UserController::class, 'show'])->name('users.show');
    Route::delete('users/delete/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('users/edit/{user}', [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/update/{user}', [UserController::class, 'update'])->name('users.update');
});

// External routes
Route::post('/externals', [\App\Http\Controllers\ExternalController::class, 'store'])->name('externals.store');

// Activity routes
Route::prefix('activities')->name('activities.')->group(function () {
    Route::get('/', [ActivityController::class, 'index'])->name('index');
    
    // Create route MOET voor {activity} staan
    Route::middleware('auth')->group(function () {
        Route::get('/create', [ActivityController::class, 'create'])->name('create');
        Route::post('/', [ActivityController::class, 'store'])->name('store');
    });
    
    // Deze routes komen NA create
    Route::post('/{activity}/join', [ActivityController::class, 'join'])->name('join');
    Route::get('/{activity}', [ActivityController::class, 'show'])->name('show');
    
    Route::middleware('auth')->group(function () {
        Route::get('/{activity}/edit', [ActivityController::class, 'edit'])->name('edit');
        Route::put('/{activity}', [ActivityController::class, 'update'])->name('update');
        Route::delete('/{activity}', [ActivityController::class, 'destroy'])->name('destroy');
        Route::delete('/{activity}/leave', [ActivityController::class, 'leave'])->name('leave');
    });
});

require __DIR__.'/auth.php';