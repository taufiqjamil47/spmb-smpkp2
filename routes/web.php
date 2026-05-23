<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\GroupingController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StatistikController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
        // Route::get('/{id}/edit', [PendaftaranController::class, 'edit'])->name('edit');
        // Route::put('/{id}', [PendaftaranController::class, 'update'])->name('update');
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

        // Tambahkan route edit untuk pendaftaran
        Route::get('pendaftaran/{id}/edit', [PendaftaranController::class, 'edit'])->name('pendaftaran.edit');
        Route::put('pendaftaran/{id}', [PendaftaranController::class, 'update'])->name('pendaftaran.update');
        Route::get('statistik', [StatistikController::class, 'index'])->name('statistik.index');
    });

    // Di web.php, tambahkan dalam group route admin
    Route::middleware(['role:admin'])->prefix('groupings')->name('groupings.')->group(function () {
        Route::get('/', [GroupingController::class, 'index'])->name('index');
        Route::get('/create', [GroupingController::class, 'create'])->name('create');
        Route::post('/', [GroupingController::class, 'store'])->name('store');
        Route::get('/{id}', [GroupingController::class, 'show'])->name('show');
        Route::post('/{id}/approve', [GroupingController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [GroupingController::class, 'reject'])->name('reject');
        Route::delete('/{id}', [GroupingController::class, 'destroy'])->name('destroy');

        // Bulk actions
        Route::post('/bulk/approve', [GroupingController::class, 'bulkApprove'])->name('bulk-approve');
        Route::post('/bulk/reject', [GroupingController::class, 'bulkReject'])->name('bulk-reject');

        // Manage students in grouping
        Route::post('/{id}/add-student', [GroupingController::class, 'addStudent'])->name('add-student');
        Route::delete('/{id}/remove-student/{studentId}', [GroupingController::class, 'removeStudent'])->name('remove-student');
    });

    // Di web.php, tambahkan di dalam group route admin

    Route::middleware(['role:admin'])->group(function () {
        // ... routes yang sudah ada ...

        // Dashboard API untuk mutual requests
        Route::get('/dashboard/api/mutual-requests', [DashboardController::class, 'apiMutualRequests'])->name('dashboard.api.mutual');
        Route::post('/dashboard/api/create-grouping', [DashboardController::class, 'createGroupingFromMutual'])->name('dashboard.create-grouping');
    });

    // Routes untuk export (khusus admin)
    Route::middleware(['role:admin'])->prefix('export')->name('export.')->group(function () {
        Route::get('/excel', [PendaftaranController::class, 'exportExcel'])->name('excel');
        Route::get('/csv', [PendaftaranController::class, 'exportCsv'])->name('csv');
        Route::get('/template', [PendaftaranController::class, 'exportTemplate'])->name('template');
    });
});

require __DIR__ . '/auth.php';
