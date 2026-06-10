<?php

namespace App\Http\Controllers\Dosen;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Penilaian;
use App\Models\TugasAkhir;
use App\Models\PassingGrade;
use Illuminate\Http\Request;
use App\Models\SeminarSidang;
use App\Models\StatusTranskrip;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ParameterPenilaian;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class RepositoriController extends Controller
{
    public function viewIndex()
    {
        $tugasAkhirs = TugasAkhir::all();
        return view('dosen.repositori.index', compact('tugasAkhirs'));
    }

    public function viewDetail($slug)
    {
        $tugasAkhir = TugasAkhir::where('slug', $slug)->first();
        $sempro = SeminarSidang::where('tugas_akhir_id', $tugasAkhir->id)
            ->where('tahapan_ta', 'Seminar Proposal')
            ->latest()
            ->first();
        $semhas = SeminarSidang::where('tugas_akhir_id', $tugasAkhir->id)
            ->where('tahapan_ta', 'Seminar Hasil')
            ->latest()
            ->first();
        $sidang = SeminarSidang::where('tugas_akhir_id', $tugasAkhir->id)
            ->where('tahapan_ta', 'Sidang Akhir')
            ->latest()
            ->first();

        return view('dosen.repositori.detail', compact('sempro', 'semhas', 'sidang', 'tugasAkhir'));
    }

    public function cetakBeritaAcara($slug)
    {
        $seminarSidang = SeminarSidang::where('slug', $slug)->first();
        // if($seminarSidang->status_kelulusan=='Menunggu Keputusan'){
        //     return redirect(route('dashboard.dosen'))->with('fail', 'Sabar! Tunggu semua dosen selesai menilai');
        // }
        $passingGrade = PassingGrade::where('tahapan_ta', $seminarSidang->tahapan_ta)->first()->nilai;
        $kaprodiId = DB::table('role_user')->where('role_id', 4)->pluck('user_id');
        $kaprodi = Dosen::where('user_id', $kaprodiId)->first();
        $statusTranskrip = StatusTranskrip::where('mahasiswa_id', $seminarSidang->mahasiswa_id)
            ->where('status_transkrip', 'Diterima')
            ->first();
        if ($seminarSidang->tahapan_ta == 'Seminar Proposal') {
            $pdf = Pdf::loadView('admin.sempro.cetak', compact('seminarSidang', 'passingGrade', 'kaprodi'))->setPaper('a4', 'portrait')->setOptions(['font-family' => 'times-new-roman',]);
            return $pdf->stream('Berita Acara Seminar Proposal ' . ucwords(strtolower($seminarSidang->mahasiswa->nama_lengkap)) . '.pdf');
        } elseif ($seminarSidang->tahapan_ta == 'Seminar Hasil') {
            $parameterPenilaianIds = ParameterPenilaian::where('tahapan_ta', 'Seminar Hasil')->pluck('id');
            $pembimbing1nilais = Penilaian::where('seminar_sidang_id', $seminarSidang->id)
                ->where('dosen_id', $seminarSidang->tugas_akhir->dosen_pembimbing_1_id)
                ->whereIn('parameter_penilaian_id', $parameterPenilaianIds)
                ->get();

            $pembimbing2nilais = Penilaian::where('seminar_sidang_id', $seminarSidang->id)
                ->where('dosen_id', $seminarSidang->tugas_akhir->dosen_pembimbing_2_id)
                ->whereIn('parameter_penilaian_id', $parameterPenilaianIds)
                ->get();

            $penguji1nilais = Penilaian::where('seminar_sidang_id', $seminarSidang->id)
                ->where('dosen_id', $seminarSidang->tugas_akhir->dosen_penguji_1_id)
                ->whereIn('parameter_penilaian_id', $parameterPenilaianIds)
                ->get();

            $penguji2nilais = Penilaian::where('seminar_sidang_id', $seminarSidang->id)
                ->where('dosen_id', $seminarSidang->tugas_akhir->dosen_penguji_2_id)
                ->whereIn('parameter_penilaian_id', $parameterPenilaianIds)
                ->get();
            $pdf = Pdf::loadView('admin.semhas.cetak', compact('seminarSidang', 'passingGrade', 'kaprodi', 'pembimbing1nilais', 'pembimbing2nilais', 'penguji1nilais', 'penguji2nilais'))->setPaper('a4', 'portrait')->setOptions(['font-family' => 'times-new-roman',]);
            return $pdf->stream('Berita Acara Seminar Hasil ' . ucwords(strtolower($seminarSidang->mahasiswa->nama_lengkap)) . '.pdf');
        } elseif ($seminarSidang->tahapan_ta == 'Sidang Akhir') {
            $semhas = SeminarSidang::where('tugas_akhir_id', $seminarSidang->tugas_akhir_id)
                ->where('tahapan_ta', 'Seminar Hasil')
                ->where('status_kelulusan', 'LULUS')
                ->latest()
                ->first();
            $passingGrade = PassingGrade::where('tahapan_ta', 'Sidang Akhir')->first()->nilai;
            $kaprodiId = DB::table('role_user')->where('role_id', 4)->pluck('user_id');
            $kaprodi = Dosen::where('user_id', $kaprodiId)->first();
            $dekan = Dosen::where('id', 1)->first();
            $statusTranskrip = StatusTranskrip::where('mahasiswa_id', $seminarSidang->mahasiswa_id)
                ->where('status_transkrip', 'Diterima')
                ->first();

            $parameterPenilaianSkripsiIds = ParameterPenilaian::where('tahapan_ta', 'Skripsi')->pluck('id');
            $parameterPenilaianArtikelIds = ParameterPenilaian::where('tahapan_ta', 'Artikel')->pluck('id');
            $parameterPenilaianPresentasiIds = ParameterPenilaian::where('tahapan_ta', 'Presentasi')->pluck('id');

            // Nilai Pembimbing 1
            $pembimbing1SkripsiNilais = Penilaian::where('seminar_sidang_id', $seminarSidang->id)
                ->where('dosen_id', $seminarSidang->tugas_akhir->dosen_pembimbing_1_id)
                ->whereIn('parameter_penilaian_id', $parameterPenilaianSkripsiIds)
                ->get();
            $pembimbing1ArtikelNilais = Penilaian::where('seminar_sidang_id', $seminarSidang->id)
                ->where('dosen_id', $seminarSidang->tugas_akhir->dosen_pembimbing_1_id)
                ->whereIn('parameter_penilaian_id', $parameterPenilaianArtikelIds)
                ->get();
            $pembimbing1PresentasiNilais = Penilaian::where('seminar_sidang_id', $seminarSidang->id)
                ->where('dosen_id', $seminarSidang->tugas_akhir->dosen_pembimbing_1_id)
                ->whereIn('parameter_penilaian_id', $parameterPenilaianPresentasiIds)
                ->get();

            // Nilai Pembimbing 2
            $pembimbing2SkripsiNilais = Penilaian::where('seminar_sidang_id', $seminarSidang->id)
                ->where('dosen_id', $seminarSidang->tugas_akhir->dosen_pembimbing_2_id)
                ->whereIn('parameter_penilaian_id', $parameterPenilaianSkripsiIds)
                ->get();
            $pembimbing2ArtikelNilais = Penilaian::where('seminar_sidang_id', $seminarSidang->id)
                ->where('dosen_id', $seminarSidang->tugas_akhir->dosen_pembimbing_2_id)
                ->whereIn('parameter_penilaian_id', $parameterPenilaianArtikelIds)
                ->get();
            $pembimbing2PresentasiNilais = Penilaian::where('seminar_sidang_id', $seminarSidang->id)
                ->where('dosen_id', $seminarSidang->tugas_akhir->dosen_pembimbing_2_id)
                ->whereIn('parameter_penilaian_id', $parameterPenilaianPresentasiIds)
                ->get();

            // Nilai Penguji 1
            $penguji1SkripsiNilais = Penilaian::where('seminar_sidang_id', $seminarSidang->id)
                ->where('dosen_id', $seminarSidang->tugas_akhir->dosen_penguji_1_id)
                ->whereIn('parameter_penilaian_id', $parameterPenilaianSkripsiIds)
                ->get();
            $penguji1ArtikelNilais = Penilaian::where('seminar_sidang_id', $seminarSidang->id)
                ->where('dosen_id', $seminarSidang->tugas_akhir->dosen_penguji_1_id)
                ->whereIn('parameter_penilaian_id', $parameterPenilaianArtikelIds)
                ->get();
            $penguji1PresentasiNilais = Penilaian::where('seminar_sidang_id', $seminarSidang->id)
                ->where('dosen_id', $seminarSidang->tugas_akhir->dosen_penguji_1_id)
                ->whereIn('parameter_penilaian_id', $parameterPenilaianPresentasiIds)
                ->get();

            // Nilai Penguji 2
            $penguji2SkripsiNilais = Penilaian::where('seminar_sidang_id', $seminarSidang->id)
                ->where('dosen_id', $seminarSidang->tugas_akhir->dosen_penguji_2_id)
                ->whereIn('parameter_penilaian_id', $parameterPenilaianSkripsiIds)
                ->get();
            $penguji2ArtikelNilais = Penilaian::where('seminar_sidang_id', $seminarSidang->id)
                ->where('dosen_id', $seminarSidang->tugas_akhir->dosen_penguji_2_id)
                ->whereIn('parameter_penilaian_id', $parameterPenilaianArtikelIds)
                ->get();
            $penguji2PresentasiNilais = Penilaian::where('seminar_sidang_id', $seminarSidang->id)
                ->where('dosen_id', $seminarSidang->tugas_akhir->dosen_penguji_2_id)
                ->whereIn('parameter_penilaian_id', $parameterPenilaianPresentasiIds)
                ->get();
            $pdf = Pdf::loadView(
                'admin.sidang.cetak',
                compact(
                    'seminarSidang',
                    'semhas',
                    'passingGrade',
                    'kaprodi',
                    'dekan',
                    'statusTranskrip',
                    'pembimbing1SkripsiNilais',
                    'pembimbing1ArtikelNilais',
                    'pembimbing1PresentasiNilais',
                    'pembimbing2SkripsiNilais',
                    'pembimbing2ArtikelNilais',
                    'pembimbing2PresentasiNilais',
                    'penguji1SkripsiNilais',
                    'penguji1ArtikelNilais',
                    'penguji1PresentasiNilais',
                    'penguji2SkripsiNilais',
                    'penguji2ArtikelNilais',
                    'penguji2PresentasiNilais',
                ),
            ) ->setPaper('a4', 'portrait')
            ->setOptions(['font-family' => 'times-new-roman',]);
            return $pdf->stream('Berita Acara Sidang Sarjana ' . ucwords(strtolower($seminarSidang->mahasiswa->nama_lengkap)) . '.pdf');
        } else {
            return view('dosen.repositori.detail');
        }
    }
}
