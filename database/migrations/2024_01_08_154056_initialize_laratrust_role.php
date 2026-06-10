<?php

use App\Models\Role;
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
        $admin = Role::create([
            'name' => 'admin',
            'display_name' => 'Admin Prodi',
            'description' => 'Admin pada program studi sekaligus sebagai admin sistem',
        ]);
        $dosen = Role::create([
            'name' => 'dosen',
            'display_name' => 'Dosen',
            'description' => 'Dosen yang berada di program studi',
        ]);
        $mahasiswa = Role::create([
            'name' => 'mahasiswa',
            'display_name' => 'Mahasiswa',
            'description' => 'Mahasiswa yang berada di program studi',
        ]);

        $kaprodi = Role::create([
            'name' => 'kaprodi',
            'display_name' => 'Ketua Program Studi',
            'description' => 'Dosen yang mengepalai Program Studi',
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
