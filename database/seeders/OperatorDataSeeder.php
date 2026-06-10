<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OperatorDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Role
        $role = \App\Models\Role::firstOrCreate(
            ['name' => 'operator_data'],
            [
                'display_name' => 'Operator Data',
                'description'  => 'Operator untuk update data tugas akhir'
            ]
        );

        // 2. Create Permission
        $permission = \App\Models\Permission::firstOrCreate(
            ['name' => 'update_data_tugas_akhir'],
            [
                'display_name' => 'Update Data Tugas Akhir',
                'description'  => 'Dapat mengupdate judul, abstrak, dan file tugas akhir'
            ]
        );

        // 3. Attach Permission to Role
        if (!$role->permissions()->where('name', 'update_data_tugas_akhir')->exists()) {
            $role->permissions()->attach($permission->id);
        }

        // 4. Assign Role to User
        $user = \App\Models\User::where('email', 'dianprawira@sisfo.untan.ac.id')->first();
        if ($user) {
            if (!$user->roles()->where('name', 'operator_data')->exists()) {
                $user->roles()->attach($role->id);
            }
        }

        // 5. Assign Role to Student User for Testing/Usage
        $studentUser = \App\Models\User::where('email', 'h1101221057@student.untan.ac.id')->first();
        if ($studentUser) {
             if (!$studentUser->roles()->where('name', 'operator_data')->exists()) {
                $studentUser->roles()->attach($role->id);
            }
        }
    }
}
