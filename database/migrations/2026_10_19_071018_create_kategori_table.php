<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_create_kategori_table.php
public function up(): void
{
    Schema::create('kategori', function (Blueprint $table) {
        $table->id('id_kategori');
        $table->string('nama')->unique();
        $table->timestamp('dibuat_pada')->useCurrent();
        $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
    });
}

public function down(): void
{
    Schema::dropIfExists('kategori');
}
};
