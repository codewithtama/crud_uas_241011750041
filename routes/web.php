<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FilmController;
use App\Http\Controllers\AuthController;

// Public route
Route::get('/', [FilmController::class, 'publicIndex'])->name('home');

// Guest routes for login
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [FilmController::class, 'index'])->name('index');
        Route::get('/create', [FilmController::class, 'create'])->name('create');
        Route::post('/store', [FilmController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [FilmController::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [FilmController::class, 'update'])->name('update');
        Route::delete('/{id}/destroy', [FilmController::class, 'destroy'])->name('destroy');
        Route::get('/export-pdf', [FilmController::class, 'exportPdf'])->name('export-pdf');
    });
});
