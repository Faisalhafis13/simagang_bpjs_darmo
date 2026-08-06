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
        Schema::create('pengajuan_magangs', function (Blueprint $table) {

            $table->id();

            // Kode Pengajuan
            $table->string('kode_pengajuan')->unique();

            // Data Ketua
            $table->string('nama_ketua');
            $table->string('universitas');
            $table->unsignedTinyInteger('semester');

            $table->string('no_hp',20);

            $table->string('email_ketua');

            // Periode Magang
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');

            // Dokumen
            $table->string('proposal');
            $table->string('surat_permohonan');

            // Status
            $table->enum('status',[
                'Pending',
                'Diterima',
                'Ditolak'
            ])->default('Pending');

            // Catatan Admin
            $table->text('catatan')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_magangs');
    }
};