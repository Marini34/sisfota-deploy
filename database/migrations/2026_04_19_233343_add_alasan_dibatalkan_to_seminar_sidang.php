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
            $table->text('alasan_dibatalkan')
                  ->nullable()
                  ->after('alasan_penolakan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seminar_sidang', function (Blueprint $table) {
            //
            $table->dropColumn('alasan_dibatalkan');
        });
    }
};
