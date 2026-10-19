<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $daftar = ['Pemrograman', 'Manajemen', 'Bahasa', 'Matematika', 'Umum'];

        foreach ($daftar as $nama) {
            Kategori::create(['nama' => $nama]);
        }
    }
}