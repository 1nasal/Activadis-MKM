<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TempUploadController;
use App\Http\Controllers\JobTitleController;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', [ActivityController::class, 'home'])->name('home');
Route::post('/uploads/temp', [TempUploadController::class, 'store'])
    ->middleware('auth')
    ->name('uploads.temp');

// Dashboard (ingelogd + verified)
Route::get('/dashboard', [ActivityController::class, 'myActivities'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Profiel (ingelogd)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User management
});

// Gebruikers beheren (alleen admin)
Route::middleware(['auth', 'can:manage-users'])->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users/store', [UserController::class, 'store'])->name('users.store');
    Route::get('users/show/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('users/edit/{user}', [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/update/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/delete/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Job titles API
    Route::get('/job-titles',  [JobTitleController::class, 'index'])->name('job-titles.index');
    Route::post('/job-titles', [JobTitleController::class, 'store'])->name('job-titles.store');
    Route::delete('/job-titles/{jobTitle}', [JobTitleController::class, 'destroy'])->name('job-titles.destroy');
    Route::patch('/job-titles/{jobTitle}', [JobTitleController::class, 'update'])->name('job-titles.update');
});

// Externals
Route::post('/externals', [\App\Http\Controllers\ExternalController::class, 'store'])->name('externals.store');

// ACTIVITEITEN
Route::prefix('activities')->name('activities.')->group(function () {
    // Open routes
    Route::get('/', [ActivityController::class, 'index'])->name('index');
    Route::get('/activity/confirm/{token}', [ActivityController::class, 'confirm'])->name('confirm');
    Route::get('/activity/leave/{token}', [ActivityController::class, 'leaveExternal'])->name('leave.external');

    // Authenticated users
    Route::middleware('auth')->group(function () {
        Route::get('/create', [ActivityController::class, 'create'])->name('create');
        Route::post('/', [ActivityController::class, 'store'])->name('store');

        Route::post('/{activity}/join', [ActivityController::class, 'join'])->name('join')->whereNumber('activity');
        Route::delete('/{activity}/leave', [ActivityController::class, 'leave'])->name('leave')->whereNumber('activity');

        // Auth-only edit/update/destroy
        Route::get('/{activity}/edit', [ActivityController::class, 'edit'])->name('edit')->whereNumber('activity');
        Route::put('/{activity}', [ActivityController::class, 'update'])->name('update')->whereNumber('activity');
        Route::delete('/{activity}', [ActivityController::class, 'destroy'])->name('destroy')->whereNumber('activity');
    });

    // Admin-only routes
    Route::middleware(['auth', 'can:manage-activities'])->group(function () {
        Route::get('/{activity}/duplicate', [ActivityController::class, 'duplicate'])
            ->name('duplicate')
            ->whereNumber('activity');
    });

    // Generic show route LAST
    Route::get('/{activity}', [ActivityController::class, 'show'])->name('show')->whereNumber('activity');
});

require __DIR__.'/auth.php';
