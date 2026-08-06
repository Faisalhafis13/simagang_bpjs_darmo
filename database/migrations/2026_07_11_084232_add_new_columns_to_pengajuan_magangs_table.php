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
    if (! Schema::hasColumn('pengajuan_magangs', 'catatan')) {
        Schema::table('pengajuan_magangs', function (Blueprint $table) {
            $table->text('catatan')
                ->nullable()
                ->after('status');
        });
    }
}

public function down(): void
{
    if (Schema::hasColumn('pengajuan_magangs', 'catatan')) {
        Schema::table('pengajuan_magangs', function (Blueprint $table) {
            $table->dropColumn('catatan');
        });
    }
}};
