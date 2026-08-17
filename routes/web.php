<?php

use App\Http\Controllers\Admin\AbsensiController;
use App\Http\Controllers\Admin\EventPromoController;
use App\Http\Controllers\Admin\MediaBeritaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Employee\DepartemenController;
use App\Http\Controllers\Employee\EmployeeAccountController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Employee\EmployeeMasterController;
use App\Http\Controllers\Employee\JabatanController;
use App\Http\Controllers\PublicPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicPageController::class, 'home'])->name('home');
Route::get('/tentang-kami', [PublicPageController::class, 'about'])->name('tentang-kami');
Route::get('/wahana', [PublicPageController::class, 'rides'])->name('wahana');
Route::get('/galeri-event', [PublicPageController::class, 'events'])->name('galeri-event');
Route::get('/media-berita', [PublicPageController::class, 'news'])->name('berita');

Route::get('/coming-soon', fn () => inertia('ComingSoon'))
    ->middleware('auth')
    ->name('coming-soon');

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'active'])
    ->name('dashboard');

Route::redirect('/admin', '/dashboard')
    ->middleware(['auth', 'active'])
    ->name('admin.dashboard');

Route::prefix('dashboard')
    ->name('dashboard.')
    ->middleware(['auth', 'active'])
    ->group(function (): void {
        Route::get('karyawan', [EmployeeController::class, 'index'])->name('karyawan.index');
        Route::get('karyawan/create', [EmployeeController::class, 'create'])
            ->middleware('super_admin')
            ->name('karyawan.create');
        Route::get('karyawan/{karyawan}/edit', [EmployeeController::class, 'edit'])
            ->middleware('super_admin')
            ->name('karyawan.edit');
        Route::get('karyawan/{karyawan}', [EmployeeController::class, 'show'])->name('karyawan.show');

        Route::middleware('super_admin')->group(function (): void {
            Route::post('karyawan', [EmployeeController::class, 'store'])->name('karyawan.store');
            Route::put('karyawan/{karyawan}', [EmployeeController::class, 'update'])->name('karyawan.update');
            Route::delete('karyawan/{karyawan}', [EmployeeController::class, 'destroy'])->name('karyawan.destroy');
            Route::patch('karyawan/{karyawan}/deactivate', [EmployeeController::class, 'deactivate'])->name('karyawan.deactivate');
            Route::patch('karyawan/{karyawan}/exit', [EmployeeController::class, 'processExit'])->name('karyawan.exit');
            Route::get('karyawan/{karyawan}/foto-ktp', [EmployeeController::class, 'photo'])->name('karyawan.photo');
            Route::post('karyawan/{karyawan}/account', [EmployeeAccountController::class, 'store'])->name('karyawan.account.store');
            Route::patch('karyawan/{karyawan}/account/status', [EmployeeAccountController::class, 'updateStatus'])->name('karyawan.account.status');

            Route::get('jabatan-departemen', EmployeeMasterController::class)->name('employee-masters.index');
            Route::post('jabatan', [JabatanController::class, 'store'])->name('jabatan.store');
            Route::put('jabatan/{jabatan}', [JabatanController::class, 'update'])->name('jabatan.update');
            Route::delete('jabatan/{jabatan}', [JabatanController::class, 'destroy'])->name('jabatan.destroy');
            Route::post('departemen', [DepartemenController::class, 'store'])->name('departemen.store');
            Route::put('departemen/{departemen}', [DepartemenController::class, 'update'])->name('departemen.update');
            Route::delete('departemen/{departemen}', [DepartemenController::class, 'destroy'])->name('departemen.destroy');
        });
    });

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function (): void {
        Route::resource('media-berita', MediaBeritaController::class)
            ->parameters(['media-berita' => 'mediaBerita'])
            ->except('show');
        Route::resource('event-promo', EventPromoController::class)
            ->parameters(['event-promo' => 'eventPromo'])
            ->except('show');
        Route::middleware('super_admin')->group(function (): void {
            Route::get('absensi', [AbsensiController::class, 'index'])->name('absensi.index');
            Route::put('absensi', [AbsensiController::class, 'store'])->name('absensi.store');
        });
    });

require __DIR__.'/auth.php';
