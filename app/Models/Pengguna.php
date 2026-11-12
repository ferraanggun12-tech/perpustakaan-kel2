<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Pengguna extends Authenticatable
{
    protected $table = 'pengguna';
    protected $primaryKey = 'id_pengguna';
    protected $fillable = ['nama', 'email', 'kata_sandi', 'peran', 'nim'];
    protected $hidden = ['kata_sandi'];

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    public function getAuthPassword()
    {
        return $this->kata_sandi;
    }

    public function getAuthPasswordName()
    {
        return 'kata_sandi';
    }

    public function getRememberTokenName()
    {
        return '';
    }

    public function isAdmin(): bool
    {
        return $this->peran === 'admin';
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_pengguna', 'id_pengguna');
    }

    public function peminjamanDiverifikasi()
    {
        return $this->hasMany(Peminjaman::class, 'diverifikasi_oleh', 'id_pengguna');
    }
}
