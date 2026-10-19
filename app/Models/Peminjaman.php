<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/Peminjaman.php
class Peminjaman extends Model
{
    protected $table = 'peminjaman';
    protected $primaryKey = 'id_peminjaman';
    protected $fillable = ['id_pengguna', 'id_buku', 'diverifikasi_oleh', 'status', 'diajukan_pada', 'batas_waktu', 'dikembalikan_pada', 'catatan'];

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku', 'id_buku');
    }

    public function verifikator()
    {
        return $this->belongsTo(Pengguna::class, 'diverifikasi_oleh', 'id_pengguna');
    }
}
