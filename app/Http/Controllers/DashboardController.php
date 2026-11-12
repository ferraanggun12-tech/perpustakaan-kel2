<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;

class DashboardController extends Controller
{
    public function index()
    {
        $admin = auth()->user()->isAdmin();

        // Admin melihat semua data, pengguna biasa hanya miliknya
        $milik = fn ($q) => $admin ? $q : $q->where('id_pengguna', auth()->id());

        $jumlahBuku = Buku::count();
        $jumlahEbook = Buku::where('jenis', 'ebook')->count();
        $jumlahFisik = Buku::where('jenis', 'fisik')->count();
        $jumlahKategori = Kategori::count();
        $jumlahPeminjaman = $milik(Peminjaman::query())->count();
        $menunggu = $milik(Peminjaman::query()->where('status', 'diajukan'))->count();

        $terbaru = $milik(Peminjaman::with(['pengguna', 'buku']))
            ->orderByDesc('diajukan_pada')
            ->take(5)
            ->get();

        $grafik = collect(range(5, 0))->map(function ($i) use ($milik) {
            $bulan = now()->subMonths($i)->startOfMonth();
            return [
                'label'  => $bulan->format('M'),
                'jumlah' => $milik(Peminjaman::query())
                    ->whereYear('diajukan_pada', $bulan->year)
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
