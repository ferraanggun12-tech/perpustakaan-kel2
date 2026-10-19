<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;

Route::resource('kategori', KategoriController::class);
Route::resource('buku', BukuController::class);
Route::get('/', function () {
    return view('welcome');
});


Route::resource('kategori', KategoriController::class);
Route::resource('buku', BukuController::class);
