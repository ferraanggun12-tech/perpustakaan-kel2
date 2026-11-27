<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE pengguna MODIFY peran ENUM('admin','petugas','pengguna') NOT NULL DEFAULT 'pengguna'");
    }

    public function down(): void
    {
        DB::table('pengguna')->where('peran', 'petugas')->update(['peran' => 'pengguna']);
        DB::statement("ALTER TABLE pengguna MODIFY peran ENUM('admin','pengguna') NOT NULL DEFAULT 'pengguna'");
    }
};
