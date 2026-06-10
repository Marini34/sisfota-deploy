<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LazyCollection::make(function () {
            $handle = fopen(base_path("database/data_source/Data Mahasiswa FMIPA-Edited.csv"), 'r');

            while (($line = fgetcsv($handle, 4096)) !== false) {
                $dataString = implode(", ", $line);
                $row = explode(';', $dataString);
                yield $row;
            }

            fclose($handle);
        })
            ->skip(1)
            ->chunk(500)
            ->each(function (LazyCollection $chunk) {
                $records = $chunk->map(function ($row) {
                    return [
                        "nim" => $row['0'],
                        "nama_lengkap" => $row['1'],
                        "jenis_kelamin" => $row['2'],
                        "alamat" => $row['4'],
                        "tahun_masuk" => $row['5'],
                        "created_at" => now(),
                        "updated_at" => now(),
                    ];
                })->toArray();

                DB::table('mahasiswa')->insert($records);
            });
    }
}
