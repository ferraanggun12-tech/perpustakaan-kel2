<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_create_buku_table.php
public function up(): void
{
    Schema::create('buku', function (Blueprint $table) {
        $table->id('id_buku');
        $table->foreignId('id_kategori')->constrained('kategori', 'id_kategori');
        $table->string('judul');
        $table->string('penulis');
        $table->enum('jenis', ['ebook', 'fisik']);
        $table->text('deskripsi')->nullable();
        $table->string('gambar_sampul')->nullable();
        $table->string('path_berkas')->nullable();
        $table->integer('stok')->nullable();
        $table->timestamp('dibuat_pada')->useCurrent();
        $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
    });
}

public function down(): void
{
    Schema::dropIfExists('buku');
}
};
