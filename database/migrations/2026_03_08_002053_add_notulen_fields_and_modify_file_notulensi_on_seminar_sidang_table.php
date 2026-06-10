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
            //

            // ubah tipe file_notulensi
            $table->text('file_notulensi')->nullable()->change();

            // kolom baru
            $table->unsignedBigInteger('notulen_mahasiswa_id')
                ->nullable()
                ->after('mahasiswa_id');

            $table->text('catatan_ta_notulen_mahasiswa_id')
                ->nullable()
                ->after('notulen_mahasiswa_id');

            $table->enum('status_validasi_notulen_mhs', ['Menunggu', 'Disetujui', 'Ditolak'])
                ->default('Menunggu')
                ->after('catatan_ta_notulen_mahasiswa_id');

            // foreign key
            $table->foreign('notulen_mahasiswa_id')
                ->references('id')
                ->on('mahasiswa')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seminar_sidang', function (Blueprint $table) {
            //
            // kembalikan tipe lama
            $table->string('file_notulensi')->change();

            // hapus kolom
            $table->dropColumn([
                'notulen_mahasiswa_id',
                'catatan_ta_notulen_mahasiswa_id',
                'status_validasi_notulen_mhs'
            ]);
        });
    }
};
