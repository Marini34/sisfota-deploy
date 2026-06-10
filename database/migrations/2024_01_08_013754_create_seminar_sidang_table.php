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
        Schema::create('seminar_sidang', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique()->nullable();
            $table->unsignedBigInteger('tugas_akhir_id')->nullable();
            $table->foreign('tugas_akhir_id')->references('id')->on('tugas_akhir');
            $table->unsignedBigInteger('mahasiswa_id')->nullable();
            $table->foreign('mahasiswa_id')->references('id')->on('mahasiswa');
            $table->enum('tahapan_ta', ['Seminar Proposal', 'Seminar Hasil', 'Sidang Akhir']);
            $table->enum('status_pendaftaran', ['Menunggu Verifikasi', 'Diterima', 'Ditolak'])->default('Menunggu Verifikasi');
            $table->date('tanggal_pelaksanaan');
            $table->time('jam_pelaksanaan');
            $table->string('tempat_pelaksanaan');
            $table->string('dokumen_ta');
            $table->string('file_lampiran', 500)->nullable();
            $table->string('file_notulensi')->nullable();
            $table->string('link_video')->nullable();
            $table->string('file_revisi')->nullable();
            $table->float('total_nilai_pembimbing_1')->nullable();
            $table->float('total_nilai_pembimbing_2')->nullable();
            $table->float('total_nilai_penguji_1')->nullable();
            $table->float('total_nilai_penguji_2')->nullable();
            $table->text('catatan_ta_pembimbing_1')->nullable();
            $table->text('catatan_ta_pembimbing_2')->nullable();
            $table->text('catatan_ta_penguji_1')->nullable();
            $table->text('catatan_ta_penguji_2')->nullable();
            $table->float('total_nilai_akhir')->nullable();
            $table->enum('status_kelulusan', ['Menunggu Keputusan', 'LULUS', 'TIDAK LULUS'])->default('Menunggu Keputusan');
            $table->text('alasan_penolakan')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seminar_sidang');
    }
};
