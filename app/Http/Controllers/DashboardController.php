<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;

class DashboardController extends Controller
{
    public function index()
    {
        $jumlahBuku = Buku::count();
        $jumlahEbook = Buku::where('jenis', 'ebook')->count();
        $jumlahFisik = Buku::where('jenis', 'fisik')->count();
        $jumlahKategori = Kategori::count();
        $jumlahPeminjaman = Peminjaman::count();
        $menunggu = Peminjaman::where('status', 'diajukan')->count();

        $terbaru = Peminjaman::with(['pengguna', 'buku'])
            ->orderByDesc('diajukan_pada')
            ->take(5)
            ->get();

        $grafik = collect(range(5, 0))->map(function ($i) {
            $bulan = now()->subMonths($i)->startOfMonth();
            return [
                'label'  => $bulan->format('M'),
                'jumlah' => Peminjaman::whereYear('diajukan_pada', $bulan->year)
                    ->whereMonth('diajukan_pada', $bulan->month)
                    ->count(),
            ];
        });
        $maks = max(1, $grafik->max('jumlah'));

        return view('dashboard', compact(
            'jumlahBuku',
            'jumlahEbook',
            'jumlahFisik',
            'jumlahKategori',
            'jumlahPeminjaman',
            'menunggu',
            'terbaru',
            'grafik',
            'maks'
        ));
    }
}
