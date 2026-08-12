<?php

use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicPageController::class, 'home'])->name('home');
Route::get('/tentang-kami', [PublicPageController::class, 'about'])->name('tentang-kami');
Route::get('/wahana', [PublicPageController::class, 'rides'])->name('wahana');
Route::get('/galeri-event', [PublicPageController::class, 'events'])->name('galeri-event');

Route::get('/coming-soon', fn () => inertia('ComingSoon'))
    ->middleware('auth')
    ->name('coming-soon');

// Alias route name retained for Laravel Breeze's default verification flow.
// It resolves to the Phase 1 Coming Soon page; no dashboard is implemented.
Route::get('/coming-soon', fn () => inertia('ComingSoon'))
    ->middleware('auth')
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
