<?php

namespace App\Services;

use App\Exceptions\AturanBisnisException;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Pengguna;
use Illuminate\Support\Facades\DB;

class PeminjamanService
{
    // Peta transisi status yang diperbolehkan
    public const TRANSISI = [
        'diajukan'   => ['dipinjam', 'ditolak'],
        'dipinjam'   => ['diserahkan', 'selesai'],
        'diserahkan' => ['selesai'],
        'ditolak'    => [],
        'selesai'    => [],
    ];

    public const STATUS_AKTIF = ['diajukan', 'dipinjam', 'diserahkan'];
    public const MAKS_PINJAMAN_AKTIF = 3;
    public const LAMA_PINJAM_HARI = 7;

    // Anggota mengajukan peminjaman
    public function ajukan(Pengguna $pengguna, int $idBuku, ?string $catatan = null): Peminjaman
    {
        return DB::transaction(function () use ($pengguna, $idBuku, $catatan) {
            $buku = Buku::lockForUpdate()->findOrFail($idBuku);

            $aktif = Peminjaman::where('id_pengguna', $pengguna->getKey())
                ->whereIn('status', self::STATUS_AKTIF);

            if ((clone $aktif)->where('id_buku', $idBuku)->exists()) {
                throw new AturanBisnisException('Anda masih memiliki peminjaman aktif untuk buku ini.');
            }

            if ((clone $aktif)->count() >= self::MAKS_PINJAMAN_AKTIF) {
                throw new AturanBisnisException('Batas maksimal ' . self::MAKS_PINJAMAN_AKTIF . ' peminjaman aktif sudah tercapai.');
            }

            if ($this->fisik($buku) && $buku->stok < 1) {
                throw new AturanBisnisException('Stok buku ini sedang habis.');
            }

            return Peminjaman::create([
                'id_pengguna'   => $pengguna->getKey(),
                'id_buku'       => $idBuku,
                'catatan'       => $catatan,
                'status'        => 'diajukan',
                'diajukan_pada' => now(),
            ]);
        });
    }

    // Semua perubahan status lewat sini: validasi transisi + ubah stok dalam satu transaction
    public function ubahStatus(
        Peminjaman $peminjaman,
        string $statusBaru,
        ?int $idPetugas = null,
        ?string $batasWaktu = null,
        ?string $catatan = null
    ): Peminjaman {
        return DB::transaction(function () use ($peminjaman, $statusBaru, $idPetugas, $batasWaktu, $catatan) {
            // Kunci baris agar dua permintaan bersamaan tidak saling menimpa
            $p = Peminjaman::lockForUpdate()->findOrFail($peminjaman->getKey());
            $lama = $p->status;

            // Status sama: hanya perbarui catatan / batas waktu, tanpa efek samping
            if ($statusBaru === $lama) {
                $data = [];
                if ($catatan !== null) {
                    $data['catatan'] = $catatan;
                }
                if ($batasWaktu && $lama === 'dipinjam') {
                    $data['batas_waktu'] = $batasWaktu;
                }
                if ($data) {
                    $p->update($data);
                }
                return $p;
            }

            if (!in_array($statusBaru, self::TRANSISI[$lama] ?? [], true)) {
                throw new AturanBisnisException("Status tidak dapat diubah dari {$lama} ke {$statusBaru}.");
            }

            $buku = Buku::lockForUpdate()->findOrFail($p->id_buku);
            $data = ['status' => $statusBaru];

            if ($statusBaru === 'dipinjam') {
                if ($this->fisik($buku)) {
                    if ($buku->stok < 1) {
                        throw new AturanBisnisException('Stok buku habis, peminjaman tidak dapat disetujui.');
                    }
                    $buku->decrement('stok');
                }
                $data['batas_waktu'] = $batasWaktu ?: now()->addDays(self::LAMA_PINJAM_HARI)->toDateString();
                $data['diverifikasi_oleh'] = $idPetugas;
            }

            if ($statusBaru === 'ditolak') {
                $data['diverifikasi_oleh'] = $idPetugas;
            }

            if ($statusBaru === 'selesai') {
                if ($this->fisik($buku)) {
                    $buku->increment('stok');
                }
                $data['dikembalikan_pada'] = now();
            }

            if ($catatan !== null) {
                $data['catatan'] = $catatan;
            }

            $p->update($data);

            return $p;
        });
    }

    private function fisik(Buku $buku): bool
    {
        return $buku->jenis !== 'ebook';
    }
}
