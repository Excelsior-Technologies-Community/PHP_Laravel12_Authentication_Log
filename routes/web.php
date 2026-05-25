<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthLogController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Authenticated routes
Route::middleware(['auth'])->group(function () {

    // Authentication Log routes
    Route::get('/auth-log', [AuthLogController::class, 'index'])->name('auth-log');
    Route::get('/auth-log/export', [AuthLogController::class, 'export'])->name('auth-log.export');
    Route::get('/auth-log/export-pdf', [AuthLogController::class, 'exportPdf'])->name('auth-log.export-pdf');
    Route::get('/auth-log/statistics', [AuthLogController::class, 'statistics'])->name('auth-log.statistics');
    Route::get('/auth-log/{id}/delete', [AuthLogController::class, 'destroy'])->name('auth-log.delete');
    Route::get('/auth-log/clear-all', [AuthLogController::class, 'clearAll'])->name('auth-log.clear');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

require __DIR__.'/auth.php';