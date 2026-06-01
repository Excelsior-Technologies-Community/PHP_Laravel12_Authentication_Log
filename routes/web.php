<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthLogController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/auth-log', [AuthLogController::class, 'index'])->name('auth-log');
    Route::get('/auth-log/export', [AuthLogController::class, 'export'])->name('auth-log.export');
    Route::get('/auth-log/export-pdf', [AuthLogController::class, 'exportPdf'])->name('auth-log.export-pdf');
    Route::get('/auth-log/statistics', [AuthLogController::class, 'statistics'])->name('auth-log.statistics');
    Route::get('/auth-log/{id}/delete', [AuthLogController::class, 'destroy'])->name('auth-log.delete');
    Route::get('/auth-log/clear-all', [AuthLogController::class, 'clearAll'])->name('auth-log.clear');

    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/chart-data', [AdminController::class, 'chartData'])->name('admin.chartData');

    Route::get('/sessions', [SessionController::class, 'index'])->name('sessions.index');
    Route::delete('/sessions/{id}', [SessionController::class, 'destroy'])->name('sessions.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

require __DIR__.'/auth.php';