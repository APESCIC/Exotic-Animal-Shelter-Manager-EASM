<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\InstallerController;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Route;

Route::get('/install', [InstallerController::class, 'show'])->name('install.show');
Route::post('/install', [InstallerController::class, 'store'])->name('install.store');

Route::middleware(RedirectIfAuthenticated::class)->group(function (): void {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function (): void {
    Route::get('/', function () {
        return view('home');
    })->name('home');

    Route::get('/admin', DashboardController::class)
        ->middleware('role:admin')
        ->name('admin.dashboard');
});
