<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('logbooks', function (Blueprint $table) {
            $table->foreignId('pengajuan_magang_id')
                ->nullable()
                ->after('user_id')
                ->constrained('pengajuan_magangs')
                ->nullOnDelete();

            $table->index('pengajuan_magang_id');
        });
    }

    public function down(): void
    {
        Schema::table('logbooks', function (Blueprint $table) {
            $table->dropForeign(['pengajuan_magang_id']);
            $table->dropIndex(['pengajuan_magang_id']);
            $table->dropColumn('pengajuan_magang_id');
        });
    }
};