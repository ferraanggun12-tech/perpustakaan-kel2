<?php

namespace App\Http\Controllers;

use App\Exceptions\AturanBisnisException;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Services\PeminjamanService;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function __construct(private PeminjamanService $layanan)
    {
    }

    public function index()
    {
        $query = Peminjaman::with(['pengguna', 'buku'])->orderByDesc('diajukan_pada');

        // Anggota hanya melihat peminjamannya sendiri
        if (!auth()->user()->isStaf()) {
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

        try {
            $this->layanan->ajukan(auth()->user(), (int) $validated['id_buku'], $validated['catatan'] ?? null);
        } catch (AturanBisnisException $e) {
            return back()->withErrors(['id_buku' => $e->getMessage()])->withInput();
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Pengajuan gagal diproses. Silakan coba lagi.')->withInput();
        }

        return redirect()
            ->route('peminjaman.index')
            ->with('success', 'Peminjaman berhasil diajukan.');
    }

    public function edit(Peminjaman $peminjaman)
    {
        // Pilihan status hanya yang boleh menurut alur
        $opsi = array_merge([$peminjaman->status], PeminjamanService::TRANSISI[$peminjaman->status] ?? []);

        return view('peminjaman.edit', compact('peminjaman', 'opsi'));
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        $validated = $request->validate([
            'status'      => 'required|in:diajukan,disetujui,ditolak,dipinjam,diserahkan,selesai',
            'batas_waktu' => 'nullable|date',
            'catatan'     => 'nullable|string',
        ]);

        $status = $validated['status'] === 'disetujui' ? 'dipinjam' : $validated['status'];

        try {
            $hasil = $this->layanan->ubahStatus(
                $peminjaman,
                $status,
                auth()->id(),
                $validated['batas_waktu'] ?? null,
                $validated['catatan'] ?? null
            );
        } catch (AturanBisnisException $e) {
            return redirect()->route('peminjaman.index')->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            report($e);
            return redirect()->route('peminjaman.index')->with('error', 'Perubahan status gagal diproses.');
        }

        $pesan = match ($hasil->status) {
            'dipinjam' => 'Peminjaman disetujui. Batas pengembalian: ' . \Carbon\Carbon::parse($hasil->batas_waktu)->translatedFormat('d M Y') . '.',
            'selesai'  => 'Pengembalian buku sudah dikonfirmasi.',
            'ditolak'  => 'Peminjaman ditolak.',
            default    => 'Peminjaman berhasil diperbarui.',
        };

        return redirect()->route('peminjaman.index')->with('success', $pesan);
    }

    // Anggota mengembalikan buku miliknya sendiri
    public function kembalikan(Peminjaman $peminjaman)
    {
        abort_unless($peminjaman->id_pengguna === auth()->id(), 403, 'Ini bukan peminjaman Anda.');
        abort_unless($peminjaman->status === 'dipinjam', 403, 'Buku ini tidak sedang dipinjam.');

        $ebook = $peminjaman->buku->jenis === 'ebook';

        try {
            $this->layanan->ubahStatus($peminjaman, $ebook ? 'selesai' : 'diserahkan', auth()->id());
        } catch (AturanBisnisException $e) {
            return redirect()->route('peminjaman.index')->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            report($e);
            return redirect()->route('peminjaman.index')->with('error', 'Pengembalian gagal diproses.');
        }

        return redirect()
            ->route('peminjaman.index')
            ->with('success', $ebook
                ? 'E-book berhasil dikembalikan.'
                : 'Pengembalian dicatat. Serahkan buku ke petugas untuk dikonfirmasi.');
    }

    public function destroy(Peminjaman $peminjaman)
    {
        $peminjaman->delete();

        return redirect()
            ->route('peminjaman.index')
            ->with('success', 'Peminjaman berhasil dihapus.');
    }
}
