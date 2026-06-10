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
        Schema::table('rekam_bimbingan', function (Blueprint $table) {
            //

            $table->tinyInteger('persentase_mhs')
                ->nullable()
                ->after('pengerjaan_selanjutnya');

            $table->string('dokumen_bimbingan_mhs')
                ->nullable()
                ->after('persentase_mhs');

            $table->text('komentar_dosen')
                ->nullable()
                ->after('dokumen_bimbingan_mhs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rekam_bimbingan', function (Blueprint $table) {
            //
            $table->dropColumn([
                'persentase_mhs',
                'dokumen_bimbingan_mhs',
                'komentar_dosen'
            ]);
        });
    }
};
