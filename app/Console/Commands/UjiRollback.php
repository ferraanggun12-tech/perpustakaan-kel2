<?php

namespace App\Console\Commands;

use App\Models\Buku;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UjiRollback extends Command
{
    protected $signature = 'perpus:uji-rollback';
    protected $description = 'Simulasi kegagalan di dalam transaction untuk membuktikan rollback';

    public function handle(): int
    {
        $buku = Buku::where('jenis', '!=', 'ebook')->first();

        if (!$buku) {
            $this->error('Tidak ada buku fisik untuk diuji.');
            return self::FAILURE;
        }

        $this->info('Buku uji: ' . $buku->judul);
        $this->line('Stok sebelum proses      : ' . Buku::find($buku->getKey())->stok);

        try {
            DB::transaction(function () use ($buku) {
                // Operasi 1: stok dikurangi
                $buku->decrement('stok');
                $this->line('Stok di dalam transaction: ' . Buku::find($buku->getKey())->stok);

                // Operasi 2: sengaja gagal
                throw new \RuntimeException('Simulasi: operasi kedua gagal');
            });
        } catch (\Throwable $e) {
            $this->warn('Exception: ' . $e->getMessage());
        }

        $this->line('Stok setelah rollback    : ' . Buku::find($buku->getKey())->stok);
        $this->info('Jika stok setelah rollback sama dengan stok sebelum proses, rollback berhasil.');

        return self::SUCCESS;
    }
}
