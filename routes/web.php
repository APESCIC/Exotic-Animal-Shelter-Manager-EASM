<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\AnimalObservationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DietController;
use App\Http\Controllers\InstallerController;
use App\Http\Controllers\LostFoundReportController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\MovementController;
use App\Http\Controllers\PersonController;
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

    Route::get('/animals', [AnimalController::class, 'index'])->name('animals.index');
    Route::get('/animals/shelter', [AnimalController::class, 'shelter'])->name('animals.shelter');
    Route::get('/animals/create', [AnimalController::class, 'create'])->name('animals.create');
    Route::post('/animals', [AnimalController::class, 'store'])->name('animals.store');
    Route::get('/animals/{animal}', [AnimalController::class, 'show'])->name('animals.show');
    Route::get('/animals/{animal}/edit', [AnimalController::class, 'edit'])->name('animals.edit');
    Route::put('/animals/{animal}', [AnimalController::class, 'update'])->name('animals.update');
    Route::get('/animals/{animal}/movements/create', [MovementController::class, 'create'])->name('animals.movements.create');
    Route::post('/animals/{animal}/movements', [MovementController::class, 'store'])->name('animals.movements.store');

    Route::get('/animals/{animal}/medical/create', [MedicalRecordController::class, 'create'])->name('animals.medical.create');
    Route::post('/animals/{animal}/medical', [MedicalRecordController::class, 'store'])->name('animals.medical.store');
    Route::get('/medical/{medicalRecord}/edit', [MedicalRecordController::class, 'edit'])->name('medical.edit');
    Route::put('/medical/{medicalRecord}', [MedicalRecordController::class, 'update'])->name('medical.update');

    Route::get('/animals/{animal}/diets/create', [DietController::class, 'create'])->name('animals.diets.create');
    Route::post('/animals/{animal}/diets', [DietController::class, 'store'])->name('animals.diets.store');
    Route::get('/diets/{diet}/edit', [DietController::class, 'edit'])->name('diets.edit');
    Route::put('/diets/{diet}', [DietController::class, 'update'])->name('diets.update');

    Route::get('/animals/{animal}/observations/create', [AnimalObservationController::class, 'create'])->name('animals.observations.create');
    Route::post('/animals/{animal}/observations', [AnimalObservationController::class, 'store'])->name('animals.observations.store');

    Route::get('/people', [PersonController::class, 'index'])->name('people.index');
    Route::get('/people/create', [PersonController::class, 'create'])->name('people.create');
    Route::post('/people', [PersonController::class, 'store'])->name('people.store');
    Route::get('/people/{person}', [PersonController::class, 'show'])->name('people.show');
    Route::get('/people/{person}/edit', [PersonController::class, 'edit'])->name('people.edit');
    Route::put('/people/{person}', [PersonController::class, 'update'])->name('people.update');

    Route::get('/lost-found', [LostFoundReportController::class, 'index'])->name('lost-found.index');
    Route::get('/lost-found/create', [LostFoundReportController::class, 'create'])->name('lost-found.create');
    Route::post('/lost-found', [LostFoundReportController::class, 'store'])->name('lost-found.store');
    Route::get('/lost-found/{lostFoundReport}', [LostFoundReportController::class, 'show'])->name('lost-found.show');
    Route::get('/lost-found/{lostFoundReport}/edit', [LostFoundReportController::class, 'edit'])->name('lost-found.edit');
    Route::put('/lost-found/{lostFoundReport}', [LostFoundReportController::class, 'update'])->name('lost-found.update');

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function (): void {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    });
});
