<?php

namespace Database\Seeders;

use App\Models\Dosen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Dosen::create([
            'nama_dosen' => 'Renny Puspita Sari, S.T., M.T.',
            'nip_nidk' => '198704182015042001'
        ]);
    
        Dosen::create([
            'nama_dosen' => 'Ibnur Rusi, S.Kom., M.M',
            'nip_nidk' => '198907282019031008'
        ]);
    
        Dosen::create([
            'nama_dosen' => 'Ilhamsyah, S.Si., M.Cs.',
            'nip_nidk' => '198405102012121001'
        ]);

        Dosen::create([
            'nama_dosen' => 'Nurul Mutiah, S.T., M.T.',
            'nip_nidk' => '198711182015042002'
        ]);

        Dosen::create([
            'nama_dosen' => 'Dian Prawira, S.T., M.Eng',
            'nip_nidk' => '198411132015041001'
        ]);

        Dosen::create([
            'nama_dosen' => 'Ferdy Febriyanto, S.Kom., M.Kom',
            'nip_nidk' => '198902012019031008'
        ]);

        Dosen::create([
            'nama_dosen' => 'Syahru Rahmayuda, S.Kom., M.Kom.',
            'nip_nidk' => '88884370018'
        ]);
    }
}
