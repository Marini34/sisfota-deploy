<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\SeminarSidang;
use App\Models\TugasAkhir;
use Illuminate\Support\Facades\Log;

class JudulController extends Controller
{
    public function viewIndex()
    {
        $seminarSidang = SeminarSidang::with('tugas_akhir')->where('status_pendaftaran', 'Diterima')->where('tahapan_ta', 'Seminar Proposal')->get();
        Log::channel('slack')->info(auth()->user()->name . ' Mengakses halaman judul!');

        return view('mahasiswa.judul.index', compact('seminarSidang'));
    }

    public function viewDetail($judul)
    {
        $judulTugasAkhir = TugasAkhir::where('judul', rawurlencode($judul))->first();
        $tugasAkhir = TugasAkhir::where('judul', $judul)->first();
        $seminarSidang = SeminarSidang::where('tugas_akhir_id', $tugasAkhir->id)->first();
        Log::channel('slack')->info(auth()->user()->name . ' Mengakses halaman detail judul!');


        return view('mahasiswa.judul.detail', compact('seminarSidang', 'tugasAkhir', 'judulTugasAkhir'));
    }
}
