<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('anggota_magang', function (Blueprint $table) {

            $table->id();

            $table->foreignId('pengajuan_magang_id')
                ->constrained('pengajuan_magang')
                ->cascadeOnDelete();

            $table->string('nama_anggota');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota_magang');
    }
};