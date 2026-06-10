<?php

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // inisiasi dekan fakultas
        Dosen::create([
            'id' => 1,
            'nama_dosen' => 'Nama Dekan',
            'nip_nidk' => '1234567890',
        ]);

        $admin = User::create([
            'name' => 'The Admin',
            'nim_nip' => 'a1231234',
            'email' => 'admin@example.com',
            'password' => Hash::make('inipassword'),
        ]);
        $admin->addRole('admin');

        $dosen = User::create([
            'name' => 'The Dosen',
            'nim_nip' => 'a1231234',
            'email' => 'dosen@example.com',
            'password' => Hash::make('inipassword'),
        ]);
        $dosen->addRole('dosen');

        $mahasiswa = User::create([
            'name' => 'The Mahasiswa',
            'nim_nip' => 'a1231234',
            'email' => 'mahasiswa@example.com',
            'password' => Hash::make('inipassword'),
        ]);
        $mahasiswa->addRole('mahasiswa');

        $kaprodi = User::create([
        'name' => 'The Kaprodi',
            'nim_nip' => 'a1231234',
            'email' => 'kaprodi@example.com',
            'password' => Hash::make('inipassword'),
        ]);
        $kaprodi->addRole('kaprodi');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
