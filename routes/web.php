<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExportController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // PENTING: ROUTES KHUSUS (SPESIFIK) DULETAKKAN DI ATAS
    Route::prefix('pendaftaran')->name('pendaftaran.')->group(function () {

        // 1. Route SPESIFIK (tanpa parameter) - Bisa diakses semua user
        Route::get('/create', [PendaftaranController::class, 'create'])->name('create');
        Route::post('/', [PendaftaranController::class, 'store'])->name('store');
        Route::get('/', [PendaftaranController::class, 'index'])->name('index');

        // 2. Route spesifik untuk admin (tanpa parameter)
        Route::middleware(['role:admin'])->group(function () {
            Route::get('/trash', [PendaftaranController::class, 'trash'])->name('trash');
            Route::post('/restore-all', [PendaftaranController::class, 'restoreAll'])->name('restore-all');
            Route::delete('/empty-trash', [PendaftaranController::class, 'emptyTrash'])->name('empty-trash');
        });

        // 3. Route dengan parameter TETAP
        Route::get('/cetak/{id}', [PendaftaranController::class, 'cetakKartu'])->name('cetak');

        // 4. Route dengan parameter DINAMIS (paling akhir)
        Route::get('/{id}/edit', [PendaftaranController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PendaftaranController::class, 'update'])->name('update');
        Route::get('/{id}', [PendaftaranController::class, 'show'])->name('show');

        // 5. Route DELETE khusus admin (paling akhir)
        Route::middleware(['role:admin'])->group(function () {
            Route::delete('/{id}', [PendaftaranController::class, 'destroy'])->name('destroy');
            Route::delete('/{id}/force-delete', [PendaftaranController::class, 'forceDelete'])->name('force-delete');
            Route::post('/{id}/restore', [PendaftaranController::class, 'restore'])->name('restore');
        });
    });

    // HAPUS semua Route::resource untuk pendaftaran - gunakan route manual di atas

    // Route khusus admin lainnya
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('tahun-ajaran', TahunAjaranController::class);
        Route::resource('users', UserController::class);
    });

    // Routes untuk export (khusus admin)
    Route::middleware(['role:admin'])->prefix('export')->name('export.')->group(function () {
        Route::get('/excel', [PendaftaranController::class, 'exportExcel'])->name('excel');
        Route::get('/csv', [PendaftaranController::class, 'exportCsv'])->name('csv');
        Route::get('/template', [PendaftaranController::class, 'exportTemplate'])->name('template');
    });
});

require __DIR__ . '/auth.php';
