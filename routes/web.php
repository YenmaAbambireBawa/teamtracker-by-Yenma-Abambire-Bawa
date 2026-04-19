<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;

// ── Auth ──────────────────────────────────────────────────────────
Route::get('/',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',  [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ── Authenticated routes ──────────────────────────────────────────
Route::middleware('auth.session')->group(function () {

    // Dashboard (daily view)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Activity logs
    Route::get('/logs/daily',              [ActivityLogController::class, 'daily'])->name('logs.daily');
    Route::post('/logs',                   [ActivityLogController::class, 'store'])->name('logs.store');
    Route::get('/activities/{id}/update',  [ActivityLogController::class, 'updateForm'])->name('logs.update-form');
    Route::get('/activities/{id}/history', [ActivityLogController::class, 'history'])->name('logs.history');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');

    // Admin-only routes
    Route::middleware('admin')->group(function () {
        Route::resource('activities', ActivityController::class)->except(['show']);
        Route::resource('users', UserController::class)->except(['show', 'destroy']);
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});

Route::get('/', function () {
    return 'alive and not ghosting railway';
});
