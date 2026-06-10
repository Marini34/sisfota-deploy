<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('seminar_sidang', function (Blueprint $table) {
            $table->integer('no_surat')->nullable()->after('dokumen_ta'); // Gantilah 'existing_column_name' dengan nama kolom yang ada pada tabel Anda
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seminar_sidang', function (Blueprint $table) { 
            $table->dropColumn('no_surat'); 
        });
    }
};
