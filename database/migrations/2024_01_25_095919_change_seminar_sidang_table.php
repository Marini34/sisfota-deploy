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
        Schema::table('seminar_sidang', function (Blueprint $table) {
            $table->date('tanggal_pelaksanaan')->nullable()->change();
            $table->time('jam_pelaksanaan')->nullable()->change();
            $table->string('tempat_pelaksanaan')->nullable()->change();
            $table->string('dokumen_ta')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
