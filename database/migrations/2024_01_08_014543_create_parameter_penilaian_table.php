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
        Schema::create('parameter_penilaian', function (Blueprint $table) {
            $table->id();
            $table->enum('tahapan_ta', ['Seminar Proposal','Seminar Hasil','Skripsi', 'Artikel', 'Presentasi']);
            $table->string('nama_parameter');
            $table->string('deskripsi_parameter', 500)->nullable();
            $table->float('persentase')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parameter_penilaian');
    }
};
