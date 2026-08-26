<?php

use App\Http\Controllers\Admin\AbsensiController;
use App\Http\Controllers\Admin\EventPromoController;
use App\Http\Controllers\Admin\GaleriEventController;
use App\Http\Controllers\Admin\HomeHeroController;
use App\Http\Controllers\Admin\MediaBeritaController;
use App\Http\Controllers\Admin\WahanaController;
use App\Http\Controllers\ClosingEvent\ClosingEventController;
use App\Http\Controllers\ClosingEvent\ClosingEventMasterController;
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
        Route::get('karyawan/export', [EmployeeController::class, 'export'])
            ->middleware('super_admin')
            ->name('karyawan.export');
        Route::get('karyawan/create', [EmployeeController::class, 'create'])
            ->middleware('super_admin')
            ->name('karyawan.create');
        Route::get('karyawan/{karyawan}/edit', [EmployeeController::class, 'edit'])
            ->middleware('super_admin')
            ->name('karyawan.edit');
        Route::get('karyawan/{karyawan}', [EmployeeController::class, 'show'])->name('karyawan.show');

        Route::get('closing-event/export', [ClosingEventController::class, 'export'])
            ->name('closing-event.export');
        Route::prefix('closing-event/master')
            ->name('closing-event.master.')
            ->group(function (): void {
                Route::get('/', [ClosingEventMasterController::class, 'index'])->name('index');
                Route::post('pic', [ClosingEventMasterController::class, 'storePic'])->name('pic.store');
                Route::put('pic/{pic}', [ClosingEventMasterController::class, 'updatePic'])->name('pic.update');
                Route::delete('pic/{pic}', [ClosingEventMasterController::class, 'destroyPic'])->name('pic.destroy');
                Route::post('jenis-event', [ClosingEventMasterController::class, 'storeJenisEvent'])->name('jenis-event.store');
                Route::put('jenis-event/{jenisEvent}', [ClosingEventMasterController::class, 'updateJenisEvent'])->name('jenis-event.update');
                Route::delete('jenis-event/{jenisEvent}', [ClosingEventMasterController::class, 'destroyJenisEvent'])->name('jenis-event.destroy');
                Route::post('lokasi', [ClosingEventMasterController::class, 'storeLokasi'])->name('lokasi.store');
                Route::put('lokasi/{lokasi}', [ClosingEventMasterController::class, 'updateLokasi'])->name('lokasi.update');
                Route::delete('lokasi/{lokasi}', [ClosingEventMasterController::class, 'destroyLokasi'])->name('lokasi.destroy');
            });
        Route::resource('closing-event', ClosingEventController::class)
            ->parameters(['closing-event' => 'closingEvent']);

        Route::prefix('cms')
            ->name('cms.')
            ->middleware('admin')
            ->group(function (): void {
                Route::get('beranda', [EventPromoController::class, 'home'])->name('home');
                Route::patch('beranda/hero', [HomeHeroController::class, 'update'])->name('home.hero.update');
                Route::post('beranda/promo', [EventPromoController::class, 'store'])->name('home.promo.store');
                Route::patch('beranda/promo/{eventPromo}', [EventPromoController::class, 'update'])->name('home.promo.update');
                Route::patch('beranda/promo/{eventPromo}/status', [EventPromoController::class, 'toggleStatus'])->name('home.promo.status');
                Route::delete('beranda/promo/{eventPromo}', [EventPromoController::class, 'destroy'])->name('home.promo.destroy');

                Route::get('wahana', [WahanaController::class, 'index'])->name('wahana.index');
                Route::post('wahana', [WahanaController::class, 'store'])->name('wahana.store');
                Route::patch('wahana/{wahana}', [WahanaController::class, 'update'])->name('wahana.update');
                Route::patch('wahana/{wahana}/status', [WahanaController::class, 'toggleStatus'])->name('wahana.status');
                Route::delete('wahana/{wahana}', [WahanaController::class, 'destroy'])->name('wahana.destroy');

                Route::get('galeri-event', [GaleriEventController::class, 'index'])->name('gallery.index');
                Route::post('galeri-event', [GaleriEventController::class, 'store'])->name('gallery.store');
                Route::patch('galeri-event/{galeriEvent}', [GaleriEventController::class, 'update'])->name('gallery.update');
                Route::delete('galeri-event/{galeriEvent}', [GaleriEventController::class, 'destroy'])->name('gallery.destroy');
            });

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
    ->middleware(['auth', 'active'])
    ->group(function (): void {
        Route::get('absensi/export', [AbsensiController::class, 'export'])->name('absensi.export');
        Route::get('absensi', [AbsensiController::class, 'index'])->name('absensi.index');
        Route::put('absensi', [AbsensiController::class, 'store'])->name('absensi.store');
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
    });

require __DIR__.'/auth.php';
