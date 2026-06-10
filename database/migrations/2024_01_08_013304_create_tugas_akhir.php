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
        Schema::create('tugas_akhir', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mahasiswa_id')->nullable();
            $table->foreign('mahasiswa_id')->references('id')->on('mahasiswa');
            $table->string('judul');
            $table->unsignedBigInteger('dosen_pembimbing_1_id')->nullable();
            $table->foreign('dosen_pembimbing_1_id')->references('id')->on('dosen');
            $table->unsignedBigInteger('dosen_pembimbing_2_id')->nullable();
            $table->foreign('dosen_pembimbing_2_id')->references('id')->on('dosen');
            $table->unsignedBigInteger('dosen_penguji_1_id')->nullable();
            $table->foreign('dosen_penguji_1_id')->references('id')->on('dosen');
            $table->unsignedBigInteger('dosen_penguji_2_id')->nullable();
            $table->foreign('dosen_penguji_2_id')->references('id')->on('dosen');
            $table->string('lampiran')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas_akhir');
    }
};
