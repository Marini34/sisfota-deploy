<?php

use App\Models\PassingGrade;
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
        PassingGrade::create([
            'tahapan_ta' => 'Seminar Proposal',
            'nilai' => 0
        ]);

        PassingGrade::create([
            'tahapan_ta' => 'Seminar Hasil',
            'nilai' => 0
        ]);

        PassingGrade::create([
            'tahapan_ta' => 'Sidang Akhir',
            'nilai' => 0
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
