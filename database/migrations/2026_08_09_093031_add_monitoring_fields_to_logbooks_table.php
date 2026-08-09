<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('logbooks', function (Blueprint $table) {
            $table->string('bukti')->nullable()->after('catatan');
            $table->string('status')->default('Menunggu')->after('bukti');
            $table->text('catatan_mentor')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('logbooks', function (Blueprint $table) {
            $table->dropColumn([
                'bukti',
                'status',
                'catatan_mentor',
            ]);
        });
    }
};