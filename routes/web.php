<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
//Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
//Activities
Route::middleware('auth')->group(function () {
    Route::resource('activities', ActivityController::class);
});

Route::get('/activity', function () {
    return view('activity');
})->name('activity');

Route::get('/', [App\Http\Controllers\ActivityController::class, 'index']);
Route::get('/activities', [App\Http\Controllers\ActivityController::class, 'index'])->name('activities.index');
Route::get('/activities/{activity}', [App\Http\Controllers\ActivityController::class, 'show'])->name('activities.show');

require __DIR__.'/auth.php';
