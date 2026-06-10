<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Makul;
use App\Models\Notifikasi;
use App\Models\ParameterPenilaian;
use App\Models\PassingGrade;
use App\Models\Penilaian;
use App\Models\SeminarSidang;
use App\Models\StatusTranskrip;
use App\Models\TranskripNilai;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdminSidangController extends Controller
{
    public function viewPendaftaran()
    {
        $seminarSidangs = SeminarSidang::where('tahapan_ta', 'Sidang Akhir')->where('status_pendaftaran', 'Menunggu Verifikasi')->get();
        return view('admin.sidang.pendaftaran', compact('seminarSidangs'));
    }

    public function viewTranskrip()
    {
        $statusTranskrip = StatusTranskrip::where('status_transkrip', 'Menunggu Verifikasi')->get();

        $makuls = Makul::all();

        return view('admin.sidang.transkrip.index', compact('statusTranskrip', 'makuls'));
    }

    public function terimaTranskrip($id)
    {
        $statusTranskrip = StatusTranskrip::where('id', $id)->first();

        if ($statusTranskrip) {
            $statusTranskrip->status_transkrip = 'Diterima';
            $statusTranskrip->save();

            return redirect()->route('admin.transkrip')->with('success', 'Transkrip Nilai Mahasiswa Diterima');
        } else {
            return redirect()->back()->with('fail', 'Transkrip Nilai Mahasiswa tidak ditemukan');
        }
    }

    public function tolakTranskrip(Request $request, $id)
    {
        $statusTranskrip = StatusTranskrip::where('id', $id)->first();

        if ($statusTranskrip) {
            $validator = Validator::make($request->all(), [
                'tolak' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('fail', 'Gagal Menolak Transkrip');
            } else {
                $statusTranskrip->status_transkrip = 'Ditolak';
                $statusTranskrip->alasan_penolakan = $request->tolak;
                $statusTranskrip->save();

                return redirect()->route('admin.transkrip')->with('success', 'Transkrip Nilai Mahasiswa Ditolak');
            }
        } else {
            return redirect()->back()->with('fail', 'Transkrip Nilai Mahasiswa tidak ditemukan');
        }
    }

    public function viewDetailTranskrip($id)
    {
        $transkrips = TranskripNilai::where('mahasiswa_id', $id)->get();
        $statusTranskrip = StatusTranskrip::where('mahasiswa_id', $id)->first();
        if ($transkrips) {
            $jumlahSks = 0;
            $jumlahMutu = 0;
            $ipk = [];
            foreach ($transkrips as $index => $transkrip) {
                $sks[$index] = $transkrip->makul->sks;
                $jumlahSks += $sks[$index];

                switch ($transkrip->nilai) {
                    case 'A':
                        $nilai = 4;
                        break;
                    case 'B+':
                        $nilai = 3.5;
                        break;
                    case 'B':
                        $nilai = 3.0;
                        break;
                    case 'C+':
                        $nilai = 2.5;
                        break;
                    case 'C':
                        $nilai = 2.0;
                        break;
                    case 'D+':
                        $nilai = 1.5;
                        break;
                    case 'D':
                        $nilai = 1.0;
                        break;
                    case 'E':
                        $nilai = 0;
                        break;
                    default:
                        $nilai = null;
                        break;
                }

                if ($nilai !== null) {
                    $ipk[$index] = $nilai * $sks[$index];
                    $jumlahMutu += $ipk[$index];
                }
            }

            if ($jumlahSks > 0) {
                $ipkAkhir = $jumlahMutu / $jumlahSks;
            } else {
                $ipkAkhir = 0;
            }

            return view('admin.sidang.transkrip.detail', compact('transkrips', 'jumlahSks', 'ipkAkhir', 'statusTranskrip'));
        } else {
            return redirect()->back()->with('fail', 'Transkrip Nilai Mahasiswa tidak ditemukan');
        }
    }

    public function storeMakul(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kodeMakul' => 'required|max:30|unique:makuls,kode_makul,NULL,id',
            'namaMakul' => 'required|max:100',
            'sks' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('fail', 'Gagal Menambahkan Makul');
        } else {
            Makul::create([
                'kode_makul' => $request->kodeMakul,
                'nama_makul' => $request->namaMakul,
                'sks' => $request->sks,
            ]);
            return redirect()->back()->with('success', 'Berhasil Menambahkan Makul');
        }
    }

    public function editMakul(Request $request, $id)
    {
        $makul = Makul::where('id', $id)->first();

        $validator = Validator::make($request->all(), [
            'editKodeMakul' => ['required', 'max:30', Rule::unique('makuls', 'kode_makul')->ignore($makul->id)],
            'editNamaMakul' => ['required', 'max:100', Rule::unique('makuls', 'nama_makul')->ignore($makul->id)],
            'editSks' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('fail', 'Gagal Mengedit Makul');
        } else {
            $makul->kode_makul = $request->editKodeMakul;
            $makul->nama_makul = $request->editNamaMakul;
            $makul->sks = $request->editSks;
            $makul->save();

            return redirect()->back()->with('success', 'Berhasil Mengedit Makul');
        }
    }

    public function hapusMakul($id)
    {
        $makul = Makul::where('id', $id)->first();
        $transkrips = TranskripNilai::where('makul_id', $id)->get();
        if ($makul) {
            foreach ($transkrips as $transkrip) {
                $statusTranskrip = StatusTranskrip::where('mahasiswa_id', $transkrip->mahasiswa_id)->first();
                if ($statusTranskrip) {
                    $statusTranskrip->status_transkrip = 'Menunggu Verifikasi';
                    $statusTranskrip->save();
                }
                $transkrip->delete();
            }
            $makul->delete();
            return redirect()->back()->with('success', 'Berhasil Menghapus Makul');
        } else {
            return redirect()->back()->with('fail', 'Gagal Menghapus Makul, Id makul tidak ditemukan');
        }
    }

    public function viewRiwayatTranskrip()
    {
        $statusTranskrip = StatusTranskrip::whereIn('status_transkrip', ['Diterima', 'Ditolak'])->orderBy('created_at', 'desc')->get();
        return view('admin.sidang.transkrip.riwayat', compact('statusTranskrip'));
    }

    public function batalkanTranskrip($id)
    {
        $statusTranskrip = StatusTranskrip::where('id', $id)->first();

        if ($statusTranskrip) {
            $statusTranskrip->status_transkrip = 'Menunggu Verifikasi';
            $statusTranskrip->alasan_penolakan = null;
            $statusTranskrip->save();

            return redirect()->route('admin.transkrip.riwayat')->with('success', 'Transkrip Nilai Mahasiswa Dibatalkan');
        } else {
            return redirect()->back()->with('fail', 'Transkrip Nilai Mahasiswa tidak ditemukan');
        }
    }

    public function viewDetail($slug)
    {
        $dosens = Dosen::all()->except(1);
        $sidang = SeminarSidang::where('slug', $slug)->first();
        $fileTranskrip = StatusTranskrip::where('mahasiswa_id', $sidang->mahasiswa_id)->first()->file_transkrip;
        $lampiranSidang = json_decode($sidang->file_lampiran);
        return view('admin.sidang.detail', compact('sidang', 'dosens', 'fileTranskrip', 'lampiranSidang'));
    }

    public function viewRiwayat()
    {
        $seminarSidangs = SeminarSidang::where('tahapan_ta', 'Sidang Akhir')
            ->whereIn('status_pendaftaran', ['Diterima', 'Ditolak'])
            ->orderBy('created_at', 'desc')->get();
        return view('admin.sidang.riwayat', compact('seminarSidangs'));
    }

    // private function ambilDataRiwayat() : Returntype {}

    public function batal($slug)
    {
        $seminarSidang = SeminarSidang::where('slug', $slug)->first();

        $seminarSidang->tanggal_pelaksanaan = null;
        $seminarSidang->jam_pelaksanaan = null;
        $seminarSidang->tempat_pelaksanaan = null;
        $seminarSidang->status_pendaftaran = 'Menunggu Verifikasi';
        $seminarSidang->save();

        return redirect()->route('admin.sidang.riwayat')->with('success', 'Status Pendaftaran Dibatalkan');
    }

    public function terima(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required',
            'jam' => 'required',
            'tempat' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('fail', 'Pendaftaran gagal, pastikan semua bagian formulir telah terisi');
        }

        $seminarSidang = SeminarSidang::where('slug', $slug)->firstOrFail();

        DB::transaction(function () use ($request, $seminarSidang) {

            $seminarSidang->update([
                'tanggal_pelaksanaan' => $request->tanggal,
                'jam_pelaksanaan' => $request->jam,
                'tempat_pelaksanaan' => $request->tempat,
                'status_pendaftaran' => 'Diterima',
            ]);
        });

        // 🔥 penting: reload relasi terbaru
        $seminarSidang->refresh();

        // 🔥 kirim notifikasi ke semua dosen terkait
        $this->kirimNotifikasiSeminar($seminarSidang);

        return redirect()
            ->route('admin.sidang')
            ->with('success', 'Pendaftaran Sidang Berhasil Diterima');
    }
    private function kirimNotifikasiSeminar($ss)
    {
        $ss->load('tugasAkhir', 'mahasiswa');

        $ta = $ss->tugasAkhir;
        $mhs = $ss->mahasiswa;

        // 🔥 ambil semua dosen terkait
        $dosenIds = collect([
            $ta->dosen_pembimbing_1_id,
            $ta->dosen_pembimbing_2_id,
            $ta->dosen_penguji_1_id,
            $ta->dosen_penguji_2_id,
        ])->filter()->unique();

        // 🔥 format notif lebih rapi
        $keterangan = "[{$ss->tahapan_ta}]
{$mhs->nama_lengkap} ({$mhs->nim})

📅 {$ss->tanggal_pelaksanaan}
⏰ {$ss->jam_pelaksanaan}
📍 {$ss->tempat_pelaksanaan}";

        foreach ($dosenIds as $dosenId) {
            Notifikasi::create([
                'dosen_id' => $dosenId,
                'ref_id' => $ss->id,
                'keterangan' => $keterangan,
                'dibaca' => false,
            ]);
        }
    }

    public function tolak(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
            'tolak' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('fail', 'Pendaftaran Gagal Ditolak');
        } else {
            $seminarSidang = SeminarSidang::where('slug', $slug)->first();
            $seminarSidang->alasan_penolakan = $request->tolak;
            $seminarSidang->status_pendaftaran = 'Ditolak';

            $seminarSidang->save();

            return redirect()->route('admin.sidang')->with('success', 'Pendaftaran Berhasil Ditolak');
        }
    }

    public function viewBeritaAcara()
    {
        $seminarSidangs = SeminarSidang::where('tahapan_ta', 'Sidang Akhir')->where('status_kelulusan', 'LULUS')->orderBy('created_at', 'desc')->get();
        return view('admin.sidang.berita-acara', compact('seminarSidangs'));
    }

    private function getDataForBeritaAcara($slug)
    {
        $seminarSidang = SeminarSidang::where('slug', $slug)->first();
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

        return compact(
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
            'penguji2PresentasiNilais'
        );
    }

    public function cetakBeritaAcara($slug)
    {
        $data = $this->getDataForBeritaAcara($slug);

        $pdf = Pdf::loadView('admin.sidang.cetak', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions(['font-family' => 'times-new-roman',]);

        return $pdf->stream('Berita Acara Sidang Sarjana ' . ucwords(strtolower($data['seminarSidang']->mahasiswa->nama_lengkap)) . '.pdf');
    }

    public function updateNoSuratBA(Request $request, $slug)
    {
        $request->validate([ 'no_surat' => 'required|integer', ]);
        $seminarSidang = SeminarSidang::where('slug', $slug)->firstOrFail();
        // dd($seminarSidang);
        $seminarSidang->no_surat = $request->input('no_surat');
        $seminarSidang->save();
        return redirect()->route('admin.sidang.berita-acara.cetak', ['slug' => $slug])->with('success', 'Nomor surat berhasil diperbarui.');
    }


    // public function cetakBeritaAcara($slug)
    // {
    //     $seminarSidang = SeminarSidang::where('slug', $slug)->first();
    //     $semhas = SeminarSidang::where('tugas_akhir_id', $seminarSidang->tugas_akhir_id)
    //         ->where('tahapan_ta', 'Seminar Hasil')
    //         ->where('status_kelulusan', 'LULUS')
    //         ->latest()
    //         ->first();
    //     $passingGrade = PassingGrade::where('tahapan_ta', 'Sidang Akhir')->first()->nilai;
    //     $kaprodiId = DB::table('role_user')->where('role_id', 4)->pluck('user_id');
    //     $kaprodi = Dosen::where('user_id', $kaprodiId)->first();
    //     $dekan = Dosen::where('id', 1)->first();
    //     $statusTranskrip = StatusTranskrip::where('mahasiswa_id', $seminarSidang->mahasiswa_id)
    //         ->where('status_transkrip', 'Diterima')
    //         ->first();

    //     $parameterPenilaianSkripsiIds = ParameterPenilaian::where('tahapan_ta', 'Skripsi')->pluck('id');
    //     $parameterPenilaianArtikelIds = ParameterPenilaian::where('tahapan_ta', 'Artikel')->pluck('id');
    //     $parameterPenilaianPresentasiIds = ParameterPenilaian::where('tahapan_ta', 'Presentasi')->pluck('id');

    //     // Nilai Pembimbing 1
    //     $pembimbing1SkripsiNilais = Penilaian::where('seminar_sidang_id', $seminarSidang->id)
    //         ->where('dosen_id', $seminarSidang->tugas_akhir->dosen_pembimbing_1_id)
    //         ->whereIn('parameter_penilaian_id', $parameterPenilaianSkripsiIds)
    //         ->get();
    //     $pembimbing1ArtikelNilais = Penilaian::where('seminar_sidang_id', $seminarSidang->id)
    //         ->where('dosen_id', $seminarSidang->tugas_akhir->dosen_pembimbing_1_id)
    //         ->whereIn('parameter_penilaian_id', $parameterPenilaianArtikelIds)
    //         ->get();
    //     $pembimbing1PresentasiNilais = Penilaian::where('seminar_sidang_id', $seminarSidang->id)
    //         ->where('dosen_id', $seminarSidang->tugas_akhir->dosen_pembimbing_1_id)
    //         ->whereIn('parameter_penilaian_id', $parameterPenilaianPresentasiIds)
    //         ->get();

    //     // Nilai Pembimbing 2
    //     $pembimbing2SkripsiNilais = Penilaian::where('seminar_sidang_id', $seminarSidang->id)
    //         ->where('dosen_id', $seminarSidang->tugas_akhir->dosen_pembimbing_2_id)
    //         ->whereIn('parameter_penilaian_id', $parameterPenilaianSkripsiIds)
    //         ->get();
    //     $pembimbing2ArtikelNilais = Penilaian::where('seminar_sidang_id', $seminarSidang->id)
    //         ->where('dosen_id', $seminarSidang->tugas_akhir->dosen_pembimbing_2_id)
    //         ->whereIn('parameter_penilaian_id', $parameterPenilaianArtikelIds)
    //         ->get();
    //     $pembimbing2PresentasiNilais = Penilaian::where('seminar_sidang_id', $seminarSidang->id)
    //         ->where('dosen_id', $seminarSidang->tugas_akhir->dosen_pembimbing_2_id)
    //         ->whereIn('parameter_penilaian_id', $parameterPenilaianPresentasiIds)
    //         ->get();

    //     // Nilai Penguji 1
    //     $penguji1SkripsiNilais = Penilaian::where('seminar_sidang_id', $seminarSidang->id)
    //         ->where('dosen_id', $seminarSidang->tugas_akhir->dosen_penguji_1_id)
    //         ->whereIn('parameter_penilaian_id', $parameterPenilaianSkripsiIds)
    //         ->get();
    //     $penguji1ArtikelNilais = Penilaian::where('seminar_sidang_id', $seminarSidang->id)
    //         ->where('dosen_id', $seminarSidang->tugas_akhir->dosen_penguji_1_id)
    //         ->whereIn('parameter_penilaian_id', $parameterPenilaianArtikelIds)
    //         ->get();
    //     $penguji1PresentasiNilais = Penilaian::where('seminar_sidang_id', $seminarSidang->id)
    //         ->where('dosen_id', $seminarSidang->tugas_akhir->dosen_penguji_1_id)
    //         ->whereIn('parameter_penilaian_id', $parameterPenilaianPresentasiIds)
    //         ->get();

    //     // Nilai Penguji 2
    //     $penguji2SkripsiNilais = Penilaian::where('seminar_sidang_id', $seminarSidang->id)
    //         ->where('dosen_id', $seminarSidang->tugas_akhir->dosen_penguji_2_id)
    //         ->whereIn('parameter_penilaian_id', $parameterPenilaianSkripsiIds)
    //         ->get();
    //     $penguji2ArtikelNilais = Penilaian::where('seminar_sidang_id', $seminarSidang->id)
    //         ->where('dosen_id', $seminarSidang->tugas_akhir->dosen_penguji_2_id)
    //         ->whereIn('parameter_penilaian_id', $parameterPenilaianArtikelIds)
    //         ->get();
    //     $penguji2PresentasiNilais = Penilaian::where('seminar_sidang_id', $seminarSidang->id)
    //         ->where('dosen_id', $seminarSidang->tugas_akhir->dosen_penguji_2_id)
    //         ->whereIn('parameter_penilaian_id', $parameterPenilaianPresentasiIds)
    //         ->get();
    //     $pdf = Pdf::loadView('admin.sidang.cetak', compact(
    //         'seminarSidang',
    //         'passingGrade',
    //         'kaprodi',
    //         'dekan',
    //         'semhas',
    //         'statusTranskrip',

    //         'pembimbing1SkripsiNilais',
    //         'pembimbing1ArtikelNilais',
    //         'pembimbing1PresentasiNilais',

    //         'pembimbing2SkripsiNilais',
    //         'pembimbing2ArtikelNilais',
    //         'pembimbing2PresentasiNilais',

    //         'penguji1SkripsiNilais',
    //         'penguji1ArtikelNilais',
    //         'penguji1PresentasiNilais',

    //         'penguji2SkripsiNilais',
    //         'penguji2ArtikelNilais',
    //         'penguji2PresentasiNilais',

    //         ))->setPaper('a4', 'portrait')->setOptions(['font-family' => 'times-new-roman',]);
    //     return $pdf->stream('Berita Acara Sidang Sarjana ' . ucwords(strtolower($seminarSidang->mahasiswa->nama_lengkap)) . '.pdf');
    // }
}