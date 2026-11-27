<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE peminjaman MODIFY status ENUM('diajukan','disetujui','ditolak','dipinjam','diserahkan','selesai') NOT NULL DEFAULT 'diajukan'");
    }

    public function down(): void
    {
        DB::table('peminjaman')->where('status', 'diserahkan')->update(['status' => 'dipinjam']);
        DB::statement("ALTER TABLE peminjaman MODIFY status ENUM('diajukan','disetujui','ditolak','dipinjam','selesai') NOT NULL DEFAULT 'diajukan'");
    }
};
