<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/Pengguna.php
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
}
