<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Database\Seeder;

class BukuSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['Pemrograman', 'Belajar Laravel Dasar', 'Tim SIBINER', 'ebook', null],
            ['Pemrograman', 'Pemrograman Web Lanjut', 'Rahmat Hidayat', 'fisik', 5],
            ['Pemrograman', 'Dasar-Dasar PHP', 'Dewi Lestari', 'ebook', null],
            ['Pemrograman', 'Basis Data MySQL', 'Agus Santoso', 'fisik', 4],
            ['Pemrograman', 'Algoritma dan Struktur Data', 'Budi Prasetyo', 'fisik', 6],
            ['Pemrograman', 'Pengantar JavaScript', 'Nadia Putri', 'ebook', null],
            ['Pemrograman', 'Rekayasa Perangkat Lunak', 'Fahmi Setiawan', 'fisik', 3],
            ['Manajemen', 'Manajemen Organisasi Mahasiswa', 'Siti Aminah', 'fisik', 5],
            ['Manajemen', 'Dasar-Dasar Manajemen', 'Hendra Wijaya', 'ebook', null],
            ['Manajemen', 'Kepemimpinan dan Kerja Tim', 'Rizky Ananda', 'ebook', null],
            ['Manajemen', 'Manajemen Proyek Sistem Informasi', 'Laila Rahma', 'fisik', 2],
            ['Manajemen', 'Kewirausahaan Digital', 'Tim SIBINER', 'ebook', null],
            ['Bahasa', 'Bahasa Indonesia untuk Perguruan Tinggi', 'Maya Sari', 'fisik', 8],
            ['Bahasa', 'English for Information Systems', 'Andi Pratama', 'ebook', null],
            ['Bahasa', 'Penulisan Karya Ilmiah', 'Yuni Kartika', 'fisik', 4],
            ['Bahasa', 'Tata Bahasa Inggris Praktis', 'Dian Permata', 'ebook', null],
            ['Bahasa', 'Public Speaking untuk Mahasiswa', 'Rina Marlina', 'fisik', 3],
            ['Matematika', 'Matematika Diskrit', 'Joko Susilo', 'fisik', 5],
            ['Matematika', 'Statistika Terapan', 'Nur Aini', 'fisik', 4],
            ['Matematika', 'Kalkulus Dasar', 'Eko Nugroho', 'ebook', null],
            ['Matematika', 'Aljabar Linear', 'Fitri Handayani', 'ebook', null],
            ['Matematika', 'Logika Matematika', 'Surya Dharma', 'fisik', 2],
            ['Umum', 'Panduan Mahasiswa Baru', 'Tim SIBINER', 'ebook', null],
            ['Umum', 'Etika Profesi Teknologi Informasi', 'Wulan Sari', 'fisik', 6],
            ['Umum', 'Pengantar Sistem Informasi', 'Bayu Aji', 'fisik', 7],
            ['Umum', 'Literasi Digital', 'Citra Dewi', 'ebook', null],
            ['Umum', 'Keamanan Informasi Dasar', 'Imam Fauzi', 'ebook', null],
            ['Umum', 'Pengantar Ilmu Komputer', 'Lutfi Hakim', 'fisik', 5],
        ];

        foreach ($data as [$namaKategori, $judul, $penulis, $jenis, $stok]) {
            $kategori = Kategori::firstOrCreate(['nama' => $namaKategori]);

            Buku::create([
                'id_kategori' => $kategori->id_kategori,
                'judul'       => $judul,
                'penulis'     => $penulis,
                'jenis'       => $jenis,
                'deskripsi'   => 'Koleksi ' . $judul . ' di perpustakaan digital SIBINER.',
                'stok'        => $stok,
            ]);
        }
    }
}
