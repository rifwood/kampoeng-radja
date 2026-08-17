<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\FirstPinChangeController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::middleware('active')->group(function (): void {
        Route::get('ganti-pin', [FirstPinChangeController::class, 'edit'])->name('pin.change');
        Route::put('ganti-pin', [FirstPinChangeController::class, 'update'])->name('pin.update');
    });

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
