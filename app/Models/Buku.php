<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/Buku.php
class Buku extends Model
{
    protected $table = 'buku';
    protected $primaryKey = 'id_buku';
    protected $fillable = ['id_kategori', 'judul', 'penulis', 'jenis', 'deskripsi', 'gambar_sampul', 'path_berkas', 'stok'];

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_buku', 'id_buku');
    }
}
