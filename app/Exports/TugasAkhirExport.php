<?php

namespace App\Exports;

use App\Models\TugasAkhir;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TugasAkhirExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return TugasAkhir::join('mahasiswa', 'tugas_akhir.mahasiswa_id', '=', 'mahasiswa.id')
            ->leftJoin('seminar_sidang', function($join) {
                $join->on('tugas_akhir.id', '=', 'seminar_sidang.tugas_akhir_id')
                     ->where('seminar_sidang.tahapan_ta', 'Sidang Akhir')
                     ->where('seminar_sidang.status_pendaftaran', 'Diterima');
            })
            ->select('tugas_akhir.*', 'mahasiswa.nim', 'mahasiswa.nama_lengkap', 'seminar_sidang.dokumen_ta')
            ->get();
    }

    public function headings(): array
    {
        return [
            'NIM',
            'Nama Lengkap',
            'Judul',
            'Abstrak',
            'File TA',
        ];
    }

    public function map($row): array
    {
        return [
            $row->nim,
            $row->nama_lengkap,
            $row->judul,
            $row->abstrak,
            $row->dokumen_ta ? asset('storage/' . $row->dokumen_ta) : 'null',
        ];
    }
}
