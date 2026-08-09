<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('logbooks', function (Blueprint $table) {


        $table->text('catatan_mentor')
              ->nullable();

        $table->string('bukti')
              ->nullable();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logbooks', function (Blueprint $table) {
            //
        });
    }
};
