<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use Illuminate\Database\Seeder;

class PenggunaSeeder extends Seeder
{
    public function run(): void
    {
        Pengguna::create([
            'nama'       => 'Admin SIBINER',
            'email'      => 'admin@sibiner.test',
            'kata_sandi' => bcrypt('password'),
            'peran'      => 'admin',
        ]);
    }
}