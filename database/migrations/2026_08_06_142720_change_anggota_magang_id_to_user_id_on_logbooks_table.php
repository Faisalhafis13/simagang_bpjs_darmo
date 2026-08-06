<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('logbooks', function (Blueprint $table) {

            // Hapus foreign key lama
            $table->dropForeign(['anggota_magang_id']);

            // Hapus kolom lama
            $table->dropColumn('anggota_magang_id');

            // Tambah kolom baru
            $table->foreignId('user_id')
                ->after('id')
                ->constrained('users')
                ->cascadeOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('logbooks', function (Blueprint $table) {

            // Hapus foreign key user
            $table->dropForeign(['user_id']);

            // Hapus kolom user
            $table->dropColumn('user_id');

            // Balik lagi ke anggota
            $table->foreignId('anggota_magang_id')
                ->after('id')
                ->constrained('anggota_magangs')
                ->cascadeOnDelete();

        });
    }
};