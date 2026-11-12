<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Middleware\HanyaAdmin;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Boleh diakses semua pengguna yang sudah login
    Route::resource('buku', BukuController::class)->only(['index']);
    Route::resource('peminjaman', PeminjamanController::class)->only(['index', 'create', 'store']);

    // Hanya admin
    Route::middleware(HanyaAdmin::class)->group(function () {
        Route::resource('kategori', KategoriController::class);
        Route::resource('buku', BukuController::class)->except(['index']);
        Route::resource('peminjaman', PeminjamanController::class)->except(['index', 'create', 'store']);
    });
});
