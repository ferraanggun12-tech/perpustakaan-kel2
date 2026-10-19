<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_create_peminjaman_table.php
public function up(): void
{
    Schema::create('peminjaman', function (Blueprint $table) {
        $table->id('id_peminjaman');
        $table->foreignId('id_pengguna')->constrained('pengguna', 'id_pengguna');
        $table->foreignId('id_buku')->constrained('buku', 'id_buku');
        $table->foreignId('diverifikasi_oleh')->nullable()->constrained('pengguna', 'id_pengguna');
        $table->enum('status', ['diajukan', 'disetujui', 'ditolak', 'dipinjam', 'selesai'])->default('diajukan');
        $table->timestamp('diajukan_pada')->useCurrent();
        $table->date('batas_waktu')->nullable();
        $table->timestamp('dikembalikan_pada')->nullable();
        $table->text('catatan')->nullable();
        $table->timestamp('dibuat_pada')->useCurrent();
        $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
    });
}

public function down(): void
{
    Schema::dropIfExists('peminjaman');
}
};
