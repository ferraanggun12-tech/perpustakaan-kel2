<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $table = 'buku';
    protected $primaryKey = 'id_buku';
    protected $fillable = [
        'id_kategori',
        'judul',
        'penulis',
        'jenis',
        'deskripsi',
        'gambar_sampul',
        'path_berkas',
        'stok',
    ];

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

    public function getSampulUrlAttribute()
    {
        if ($this->gambar_sampul && file_exists(public_path($this->gambar_sampul))) {
            return asset($this->gambar_sampul);
        }

        return asset('img/sampul-default.svg');
    }

    public static function simpanSampul($file)
    {
        $nama = uniqid('sampul_') . '.' . $file->extension();
        $file->move(public_path('sampul'), $nama);

        return 'sampul/' . $nama;
    }

    public static function hapusSampul($path)
    {
        if ($path && file_exists(public_path($path))) {
            @unlink(public_path($path));
        }
    }
}
