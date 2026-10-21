<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Buku;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = Peminjaman::with(['pengguna', 'buku'])
            ->orderByDesc('diajukan_pada')
            ->get();

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
            'id_pengguna'   => auth()->id() ?? 1,
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

        $peminjaman->update([
            'status'             => $validated['status'],
            'batas_waktu'        => $validated['batas_waktu'] ?? $peminjaman->batas_waktu,
            'catatan'            => $validated['catatan'] ?? $peminjaman->catatan,
            'diverifikasi_oleh'  => auth()->id() ?? 1,
            'dikembalikan_pada'  => $validated['status'] === 'selesai' ? now() : $peminjaman->dikembalikan_pada,
        ]);

        return redirect()
            ->route('peminjaman.index')
            ->with('success', 'Status peminjaman berhasil diperbarui.');
    }

    public function destroy(Peminjaman $peminjaman)
    {
        $peminjaman->delete();

        return redirect()
            ->route('peminjaman.index')
            ->with('success', 'Peminjaman berhasil dihapus.');
    }
}
