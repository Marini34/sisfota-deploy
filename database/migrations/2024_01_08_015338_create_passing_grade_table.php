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
        Schema::create('passing_grade', function (Blueprint $table) {
            $table->id();
            $table->enum('tahapan_ta', ['Seminar Proposal','Seminar Hasil','Sidang Akhir']);
            $table->float('nilai');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('passing_grade');
    }
};
