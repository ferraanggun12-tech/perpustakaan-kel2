<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengguna extends Model
{
    protected $table = 'pengguna';
    protected $primaryKey = 'id_pengguna';
    protected $fillable = ['nama', 'email', 'kata_sandi', 'peran', 'nim'];

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_pengguna', 'id_pengguna');
    }

    public function peminjamanDiverifikasi()
    {
        return $this->hasMany(Peminjaman::class, 'diverifikasi_oleh', 'id_pengguna');
    }
}
