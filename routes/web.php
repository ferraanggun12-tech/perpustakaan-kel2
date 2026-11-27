<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PeminjamanController;

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

    // Semua role yang sudah login: melihat daftar buku dan daftar peminjaman
    Route::resource('buku', BukuController::class)->only(['index']);
    Route::resource('peminjaman', PeminjamanController::class)->only(['index']);

    // Kelompok 1: hanya pengguna (anggota) - mengajukan dan mengembalikan e-book
    Route::middleware('role:pengguna')->group(function () {
        Route::resource('peminjaman', PeminjamanController::class)->only(['create', 'store']);
        Route::post('/peminjaman/{peminjaman}/kembalikan', [PeminjamanController::class, 'kembalikan'])
            ->name('peminjaman.kembalikan');
    });

    // Kelompok 2: hanya admin - data master kategori
    Route::middleware('role:admin')->group(function () {
        Route::resource('kategori', KategoriController::class);
    });

    // Kelompok 3: admin dan petugas - kelola buku dan verifikasi peminjaman
    Route::middleware('role:admin,petugas')->group(function () {
        Route::resource('buku', BukuController::class)->except(['index']);
        Route::resource('peminjaman', PeminjamanController::class)->except(['index', 'create', 'store']);
    });
});
