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
        Schema::create('rekam_bimbingan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->references('id')->on('mahasiswa');
            $table->unsignedBigInteger('dosen_pembimbing_1_id')->nullable();
            $table->foreign('dosen_pembimbing_1_id')->references('id')->on('dosen');
            $table->unsignedBigInteger('dosen_pembimbing_2_id')->nullable();
            $table->foreign('dosen_pembimbing_2_id')->references('id')->on('dosen');
            $table->text('uraian_kemajuan_ta');
            $table->text('pengerjaan_selanjutnya');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekam_bimbingan');
    }
};
