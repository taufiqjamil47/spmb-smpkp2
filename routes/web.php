<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GroupingController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\StatistikController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ============================================================================
// HOME
// ============================================================================
Route::get('/', function () {
    return redirect()->route('login');
});

// ============================================================================
// ROUTES YANG MEMERLUKAN AUTENTIKASI
// ============================================================================
Route::middleware(['auth'])->group(function () {

    // ------------------------------------------------------------------------
    // DASHBOARD
    // ------------------------------------------------------------------------
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/api/total-students', [DashboardController::class, 'apiTotalStudents'])->name('dashboard.api.total-students');
    // Tambahkan route ini di dalam route group yang sesuai
    Route::get('/dashboard/chart-data', [DashboardController::class, 'apiChartData'])->name('dashboard.api.chart');
    // Statistik
    Route::get('statistik', [StatistikController::class, 'index'])->name('statistik.index');

    // ------------------------------------------------------------------------
    // PENDAFTARAN (SEMUA USER)
    // ------------------------------------------------------------------------
    Route::prefix('pendaftaran')->name('pendaftaran.')->group(function () {
        // Route tanpa parameter
        Route::get('/create', [PendaftaranController::class, 'create'])->name('create');
        Route::post('/', [PendaftaranController::class, 'store'])->name('store');
        Route::get('/', [PendaftaranController::class, 'index'])->name('index');

        // Route khusus admin (manajemen data)
        Route::middleware(['role:admin'])->group(function () {
            // Soft delete & trash (HARUS SEBELUM /{id} route)
            Route::get('/trash', [PendaftaranController::class, 'trash'])->name('trash');
            Route::post('/restore-all', [PendaftaranController::class, 'restoreAll'])->name('restore-all');
            Route::delete('/empty-trash', [PendaftaranController::class, 'emptyTrash'])->name('empty-trash');

            // Edit & update
            Route::get('/{id}/edit', [PendaftaranController::class, 'edit'])->name('edit');
            Route::put('/{id}', [PendaftaranController::class, 'update'])->name('update');

            // Delete & force delete & restore
            Route::delete('/{id}', [PendaftaranController::class, 'destroy'])->name('destroy');
            Route::delete('/{id}/force-delete', [PendaftaranController::class, 'forceDelete'])->name('force-delete');
            Route::post('/{id}/restore', [PendaftaranController::class, 'restore'])->name('restore');
        });

        // Route dengan parameter spesifik (ID) - HARUS PALING AKHIR
        Route::get('/cetak/{id}', [PendaftaranController::class, 'cetakKartu'])->name('cetak');
        Route::get('/{id}', [PendaftaranController::class, 'show'])->name('show');
    });

    // ------------------------------------------------------------------------
    // ROUTES KHUSUS ADMIN
    // ------------------------------------------------------------------------
    Route::middleware(['role:admin'])->group(function () {

        // Manajemen Tahun Ajaran
        Route::resource('tahun-ajaran', TahunAjaranController::class);

        // Manajemen Users
        Route::resource('users', UserController::class);

        // --------------------------------------------------------------------
        // MANAJEMEN GROUPING
        // --------------------------------------------------------------------
        Route::prefix('groupings')->name('groupings.')->group(function () {
            // Tampilan & form
            Route::get('/', [GroupingController::class, 'index'])->name('index');
            Route::get('/create', [GroupingController::class, 'create'])->name('create');
            Route::post('/', [GroupingController::class, 'store'])->name('store');
            Route::get('/{id}', [GroupingController::class, 'show'])->name('show');

            // Approve & reject
            Route::post('/{id}/approve', [GroupingController::class, 'approve'])->name('approve');
            Route::post('/{id}/reject', [GroupingController::class, 'reject'])->name('reject');
            Route::post('/bulk/approve', [GroupingController::class, 'bulkApprove'])->name('bulk-approve');
            Route::post('/bulk/reject', [GroupingController::class, 'bulkReject'])->name('bulk-reject');

            // Manajemen siswa dalam grouping
            Route::post('/{id}/add-student', [GroupingController::class, 'addStudent'])->name('add-student');
            Route::delete('/{id}/remove-student/{studentId}', [GroupingController::class, 'removeStudent'])->name('remove-student');

            // Delete grouping
            Route::delete('/{id}', [GroupingController::class, 'destroy'])->name('destroy');
        });

        // --------------------------------------------------------------------
        // DASHBOARD API UNTUK MUTUAL REQUESTS
        // --------------------------------------------------------------------
        Route::prefix('dashboard/api')->name('dashboard.')->group(function () {
            Route::get('/mutual-requests', [DashboardController::class, 'apiMutualRequests'])->name('api.mutual');
            Route::post('/create-grouping', [DashboardController::class, 'createGroupingFromMutual'])->name('create-grouping');
        });

        // --------------------------------------------------------------------
        // EXPORT DATA
        // --------------------------------------------------------------------
        Route::prefix('export')->name('export.')->group(function () {
            Route::get('/excel', [PendaftaranController::class, 'exportExcel'])->name('excel');
            Route::get('/csv', [PendaftaranController::class, 'exportCsv'])->name('csv');
            Route::get('/template', [PendaftaranController::class, 'exportTemplate'])->name('template');
        });
    });
});

// ============================================================================
// AUTHENTICATION ROUTES
// ============================================================================
require __DIR__ . '/auth.php';
