<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    public function run(): void
    {
        Pengguna::updateOrCreate(
            ['email' => 'admin@sibiner.test'],
            [
                'nama'       => 'Admin SIBINER',
                'kata_sandi' => Hash::make('password123'),
                'peran'      => 'admin',
            ]
        );

        Pengguna::updateOrCreate(
            ['email' => 'mahasiswa@sibiner.test'],
            [
                'nama'       => 'Mahasiswa Demo',
                'kata_sandi' => Hash::make('password123'),
                'peran'      => 'pengguna',
                'nim'        => '2023000001',
            ]
        );
    }
}
