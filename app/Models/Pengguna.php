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

    public function hasRole(string ...$roles): bool
    {
        return in_array($this->peran, $roles, true);
    }

    public function isAdmin(): bool
    {
        return $this->peran === 'admin';
    }

    // Admin dan petugas mengelola data perpustakaan
    public function isStaf(): bool
    {
        return in_array($this->peran, ['admin', 'petugas'], true);
    }

    public function getLabelPeranAttribute(): string
    {
        return [
            'admin'    => 'Admin',
            'petugas'  => 'Petugas Perpustakaan',
            'pengguna' => 'Anggota',
        ][$this->peran] ?? ucfirst($this->peran);
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
