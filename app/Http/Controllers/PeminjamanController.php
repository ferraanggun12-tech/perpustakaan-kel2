<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Buku;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index()
    {
        $query = Peminjaman::with(['pengguna', 'buku'])->orderByDesc('diajukan_pada');

        // Pengguna biasa hanya melihat peminjamannya sendiri
        if (!auth()->user()->isAdmin()) {
            $query->where('id_pengguna', auth()->id());
        }

        $peminjaman = $query->get();

        return view('peminjaman.index', compact('peminjaman'));
    }

    public function create()
    {
        $buku = Buku::orderBy('judul')->get();

        return view('peminjaman.create', compact('buku'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_buku' => 'required|exists:buku,id_buku',
            'catatan' => 'nullable|string',
        ]);

        Peminjaman::create([
            'id_pengguna'   => auth()->id(),
            'id_buku'       => $validated['id_buku'],
            'catatan'       => $validated['catatan'] ?? null,
            'status'        => 'diajukan',
            'diajukan_pada' => now(),
        ]);

        return redirect()
            ->route('peminjaman.index')
            ->with('success', 'Peminjaman berhasil diajukan.');
    }

    public function edit(Peminjaman $peminjaman)
    {
        return view('peminjaman.edit', compact('peminjaman'));
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        $validated = $request->validate([
            'status'       => 'required|in:diajukan,disetujui,ditolak,dipinjam,selesai',
            'batas_waktu'  => 'nullable|date',
            'catatan'      => 'nullable|string',
        ]);

        $status = $validated['status'];

        if ($status === 'disetujui') {
            $status = 'dipinjam';
        }

        $batasWaktu = $validated['batas_waktu'] ?? $peminjaman->batas_waktu;
        if ($status === 'dipinjam' && !$batasWaktu) {
            $batasWaktu = now()->addDays(7)->toDateString();
        }

        $peminjaman->update([
            'status'            => $status,
            'batas_waktu'       => $batasWaktu,
            'catatan'           => $validated['catatan'] ?? $peminjaman->catatan,
            'diverifikasi_oleh' => auth()->id(),
            'dikembalikan_pada' => $status === 'selesai'
                ? ($peminjaman->dikembalikan_pada ?? now())
                : $peminjaman->dikembalikan_pada,
        ]);

        $pesan = match ($status) {
            'dipinjam' => 'Peminjaman disetujui. Batas pengembalian: ' . \Carbon\Carbon::parse($batasWaktu)->translatedFormat('d M Y') . '.',
            'selesai'  => 'Buku ditandai sudah dikembalikan.',
            'ditolak'  => 'Peminjaman ditolak.',
            default    => 'Status peminjaman berhasil diperbarui.',
        };

        return redirect()
            ->route('peminjaman.index')
            ->with('success', $pesan);
    }

    public function destroy(Peminjaman $peminjaman)
    {
        $peminjaman->delete();

        return redirect()
            ->route('peminjaman.index')
            ->with('success', 'Peminjaman berhasil dihapus.');
    }
}
