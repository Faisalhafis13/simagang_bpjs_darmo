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
        Schema::table('anggota_magangs', function (Blueprint $table) {

            $table->string('email')
                ->nullable()
                ->after('nama_anggota');

            $table->string('no_hp', 20)
                ->nullable()
                ->after('email');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anggota_magangs', function (Blueprint $table) {
            //
        });
    }
};
