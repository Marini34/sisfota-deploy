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
        Schema::table('mahasiswa', function (Blueprint $table) {

            // Setelah kolom alamat
            $table->string('tgl_masuk')->nullable()->after('alamat');
            $table->string('bln_masuk', 2)->nullable()->after('tgl_masuk');

            // Setelah tahun_masuk
            $table->string('tgl_lulus')->nullable()->after('tahun_masuk');
            $table->string('bln_lulus', 2)->nullable()->after('tgl_lulus');
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            //
            $table->dropColumn([
                'tgl_masuk',
                'bln_masuk',
                'tgl_lulus',
                'bln_lulus'
            ]);
        });
    }
};
