<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Notifikasi;
use App\Models\PassingGrade;
use App\Models\SeminarSidang;
use App\Models\TugasAkhir;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminSemproController extends Controller
{
    public function viewPendaftaran()
    {

        $seminarSidangs = SeminarSidang::where('tahapan_ta', 'Seminar Proposal')->where('status_pendaftaran', 'Menunggu Verifikasi')->get();

        return view('admin.sempro.pendaftaran', compact('seminarSidangs'));
    }

    public function viewRekap()
    {
        $sempros = SeminarSidang::with('mahasiswa', 'tugas_akhir')->where('tahapan_ta', 'Seminar Proposal')->whereIn('status_kelulusan', ['LULUS', 'TIDAK LULUS'])->orderBy('tanggal_pelaksanaan', 'desc')->get();

        return view('admin.sempro.rekap', compact('sempros'));
    }

    public function cetakRekap(Request $request)
    {
        if ($request->periode != null) {
            $sempros = SeminarSidang::where('tahapan_ta', 'Seminar Proposal')->where('tanggal_pelaksanaan', 'like', '%'.$request->periode.'%')->whereIn('status_kelulusan', ['LULUS', 'TIDAK LULUS'])->get();
            $kaprodiId = DB::table('role_user')->where('role_id', 4)->pluck('user_id');
            $kaprodi = Dosen::where('user_id', $kaprodiId)->first();
            $pdf = Pdf::loadView('admin.sempro.cetak-rekap', compact('sempros', 'kaprodi'))->setPaper('a4', 'landscape');

            return $pdf->stream('Rekapitulasi Seminar Proposal.pdf');
        } else {
            return redirect()->back()->with('fail', 'Gagal Mencetak Rekapitulasi');
        }
    }

    public function viewDetail($slug)
    {
        $dosens = Dosen::all()->except(1);
        $notulenID = Mahasiswa::all();

        $aksesAdmin = Auth::user()->hasRole('admin');
        $sempros = SeminarSidang::with('tugas_akhir')->where('slug', $slug)->where('tahapan_ta', 'Seminar Proposal')->get();

        return view('admin.sempro.detail', compact('sempros', 'dosens', 'aksesAdmin', 'notulenID'));
    }


    public function batalkan(Request $request, $id)
    {
        $request->validate([
            'alasan_dibatalkan' => 'required|string|max:1000'
        ]);

        $ss = SeminarSidang::with('tugasAkhir', 'mahasiswa')->findOrFail($id);

        $aksesAdmin = Auth::user()->hasRole('admin');
        $nameAdmin = Auth::user()->name;

        // ✅ hanya admin yang boleh
        if (!$aksesAdmin) {
            abort(403, 'Tidak memiliki akses');
        }

        // 🔥 format alasan lengkap
        $alasanLengkap = "Dibatalkan oleh: {$nameAdmin}\n" .
            "Dengan Alasan: {$request->alasan_dibatalkan}";

        $ss->update([
            'status_kelulusan' => 'TIDAK LULUS',
            'status_pendaftaran' => 'Dibatalkan',
            'alasan_dibatalkan' => $alasanLengkap
        ]);

        $ss->refresh();

        // 🔥 kirim notif
        $this->kirimNotifikasiPembatalan($ss, $nameAdmin);

        return back()->with('success', 'Seminar berhasil dibatalkan');
    }

    private function kirimNotifikasiPembatalan($ss, $namaDosen)
    {
        $ss->load('tugasAkhir', 'mahasiswa');

        $ta = $ss->tugasAkhir;
        $mhs = $ss->mahasiswa;

        $dosenIds = collect([
            $ta->dosen_pembimbing_1_id,
            $ta->dosen_pembimbing_2_id,
            $ta->dosen_penguji_1_id,
            $ta->dosen_penguji_2_id,
        ])->filter()->unique();

        $keterangan = "❌ [{$ss->tahapan_ta} DIBATALKAN]

{$mhs->nama_lengkap} ({$mhs->nim})
{$ss->alasan_dibatalkan}";

        foreach ($dosenIds as $dosenId) {
            Notifikasi::create([
                'dosen_id' => $dosenId,
                'ref_id' => $ss->id,
                'keterangan' => $keterangan,
                'dibaca' => false,
            ]);
        }
    }


    public function editMahasiswa(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            'no_hp' => 'required',
            'notulen_mahasiswa_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('fail', 'Gagal');
        }

        try {
            DB::transaction(function () use ($request, $slug) {
                // 1. Cari data Tugas Akhir berdasarkan slug
                $tugasAkhir = TugasAkhir::where('slug', $slug)->firstOrFail();

                // 2. Update data di tabel tugas_akhir
                $tugasAkhir->judul = $request->judul;
            $tugasAkhir->no_hp = $request->no_hp;
            $tugasAkhir->save();

                // 3. Update data di tabel seminar_sidang
                // Kita cari data seminar_sidang yang terhubung dengan tugas_akhir_id ini
                // dan pastikan tahapannya adalah Seminar Proposal (sesuaikan jika namanya berbeda)
                $seminarSidang = SeminarSidang::where('tugas_akhir_id', $tugasAkhir->id)
                    ->where('tahapan_ta', 'Seminar Proposal')
                    ->first();

                if ($seminarSidang) {
                    $seminarSidang->notulen_mahasiswa_id = $request->notulen_mahasiswa_id;
                    $seminarSidang->save();
                }
            });

            return redirect()->route('admin.sempro.riwayat')->with('success', 'Perubahan data berhasil');
        } catch (\Exception $e) {
            // Jika terjadi error saat menyimpan, kembalikan dengan pesan error
            return redirect()->back()->with('fail', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function terima(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
            // 'dospeng1' => 'required',
            // 'dospeng2' => 'required',
            'tanggal' => 'required',
            'jam' => 'required',
            'tempat' => 'required|max:255',
            'status_pendaftaran' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('fail', 'Pendaftaran gagal, pastikan semua bagian formulir telah terisi dan file yang diupload sudah sesuai dengan format yang ditentukan');
        } else {
            $seminarSidang = SeminarSidang::where('slug', $slug)->first();
            // $tugasAkhirId = $seminarSidang->tugas_akhir_id;
            // $tugasAkhir = TugasAkhir::find($tugasAkhirId);

            // $tugasAkhir->dosen_penguji_1_id = $request->dospeng1;
            // $tugasAkhir->dosen_penguji_2_id = $request->dospeng2;
            // $tugasAkhir->save();

            $seminarSidang->tanggal_pelaksanaan = $request->tanggal;
            $seminarSidang->jam_pelaksanaan = $request->jam;
            $seminarSidang->tempat_pelaksanaan = $request->tempat;
            $seminarSidang->status_pendaftaran = $request->status_pendaftaran;
            $seminarSidang->save();

            return redirect()->route('admin.sempro')->with('success', 'Pendaftaran Berhasil Diterima');
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

            return redirect()->route('admin.sempro.riwayat')->with('success', 'Pendaftaran Berhasil Ditolak');
        }
    }

    public function batal($slug)
    {
        $seminarSidang = SeminarSidang::where('slug', $slug)->first();
        $tugasAkhirId = $seminarSidang->tugas_akhir_id;
        $tugasAkhir = TugasAkhir::find($tugasAkhirId);

        $tugasAkhir->dosen_penguji_1_id = null;
        $tugasAkhir->dosen_penguji_2_id = null;
        $tugasAkhir->save();

        $seminarSidang->tanggal_pelaksanaan = null;
        $seminarSidang->jam_pelaksanaan = null;
        $seminarSidang->tempat_pelaksanaan = null;
        $seminarSidang->status_pendaftaran = 'Menunggu Verifikasi';
        $seminarSidang->save();

        return redirect()->route('admin.sempro.riwayat')->with('success', 'Status Pendaftaran Dibatalkan');
    }

    public function delete($slug)
    {
        $seminarSidang = SeminarSidang::where('slug', $slug)->first();
        if ($seminarSidang) {
            $lampiran = json_decode($seminarSidang->file_lampiran);
            for ($i = 0; $i < count($lampiran); $i++) {
                $fileLampiran[$i] = $lampiran[$i];
            }

            if (Storage::exists($lampiran[1])) {
                Storage::move($lampiran[1], 'Sampah/Bukti Hadir Seminar/'.$lampiran[1]);
            }

            if (Storage::exists($lampiran[2])) {
                Storage::move($lampiran[2], 'Sampah/Bukti Notulen/'.$lampiran[2]);
            }

            if (Storage::exists($lampiran[3])) {
                Storage::move($lampiran[3], 'Sampah/LIRS/'.$lampiran[3]);
            }

            for ($i = 4; $i < count($lampiran); $i++) {
                if (Storage::exists($lampiran[$i])) {
                    Storage::move($lampiran[$i], 'Sampah/File Lainnya/Sempro'.$lampiran[$i]);
                }
            }

            Storage::delete('Dokumen Proposal/'.$seminarSidang->dokumen_ta);
            SeminarSidang::find($seminarSidang->id)->delete();
            $tugasAkhirId = $seminarSidang->tugas_akhir_id;
            TugasAkhir::find($tugasAkhirId)->delete();

            return redirect()->route('admin.sempro')->with('success', 'Data telah dihapus');
        } else {
            return redirect()->route('admin.sempro')->with('fail', 'Gagal menghapus data');
        }
    }

    public function viewRiwayat()
    {
        $seminarSidangs = SeminarSidang::where('tahapan_ta', 'Seminar Proposal')
            ->whereIn('status_pendaftaran', ['Diterima', 'Ditolak', 'Dibatalkan'])
            ->orderBy('created_at', 'desc')->get();

        return view('admin.sempro.riwayat', compact('seminarSidangs'));
    }

    public function viewBeritaAcara()
    {
        $seminarSidangs = SeminarSidang::where('tahapan_ta', 'Seminar Proposal')->whereIn('status_kelulusan', ['LULUS', 'TIDAK LULUS'])->orderBy('created_at', 'desc')->get();

        return view('admin.sempro.berita-acara', compact('seminarSidangs'));
    }

    public function cetakBeritaAcara($slug)
    {
        $seminarSidang = SeminarSidang::where('slug', $slug)->first();
        $passingGrade = PassingGrade::where('tahapan_ta', 'Seminar Proposal')->first()->nilai;
        $kaprodiId = DB::table('role_user')->where('role_id', 4)->pluck('user_id');
        $kaprodi = Dosen::where('user_id', $kaprodiId)->first();
        $pdf = Pdf::loadView('admin.sempro.cetak', compact('seminarSidang', 'passingGrade', 'kaprodi'))->setPaper('a4', 'portrait')->setOptions(['font-family' => 'times-new-roman']);

        return $pdf->stream('Berita Acara Seminar Proposal '.ucwords(strtolower($seminarSidang->mahasiswa->nim)).'.pdf');
    }
}
