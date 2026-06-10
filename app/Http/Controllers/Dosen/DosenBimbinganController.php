<?php

namespace App\Http\Controllers\Dosen;

use DateTime;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\TugasAkhir;
use Illuminate\Http\Request;
use App\Models\SeminarSidang;
use App\Models\RekamBimbingan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DosenBimbinganController extends Controller
{
    public function viewBimbingan()
    {
        $dosenId = Dosen::where('nip_nidk', Auth::user()->nim_nip)->first()->id;
        // $dosenPembimbing = RekamBimbingan::where('dosen_pembimbing_1_id', $dosenId)->orwhere('dosen_pembimbing_2_id', $dosenId)->get();
        $bimbingans = RekamBimbingan::where('pembimbing_id', $dosenId)->get();
        $kelolaBimbingans = RekamBimbingan::where('pembimbing_id', $dosenId)->where('status_rekam_bimbingan', 'Menunggu Verifikasi')->get();

        return view('dosen.bimbingan.index', compact('kelolaBimbingans'));
    }
    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'status_rekam_bimbingan' => 'required',
        ]);

        $rekam = RekamBimbingan::findOrFail($id);
        if ($rekam) {
            $rekam->status_rekam_bimbingan = $request->status_rekam_bimbingan;
            $rekam->komentar_dosen = $request->komentar_dosen;

            $rekam->save();
            return redirect()->back()->with('success', 'Rekam bimbingan berhasil diverifikasi');
        } else {
            return redirect()->back()->with('fail', 'Gagal Menerima Rekam Bimbingan Tugas Akhir, id tidak ditemukan');
        }
    }
    public function terima($id)
    {
        $rekamBimbingan = RekamBimbingan::where('id', $id)->first();

        if ($rekamBimbingan) {
            $rekamBimbingan->status_rekam_bimbingan = 'Diterima';
            $rekamBimbingan->save();

            return redirect()->back()->with('success', 'Berhasil MENERIMA Rekam Bimbingan Tugas Akhir');
        } else {
            return redirect()->back()->with('fail', 'Gagal Menerima Rekam Bimbingan Tugas Akhir, id tidak ditemukan');
        }
    }

    public function tolak($id)
    {
        $rekamBimbingan = RekamBimbingan::where('id', $id)->first();

        if ($rekamBimbingan) {
            $rekamBimbingan->status_rekam_bimbingan = 'Ditolak';
            $rekamBimbingan->save();

            return redirect()->back()->with('success', 'Berhasil MENOLAK Rekam Bimbingan Tugas Akhir');
        } else {
            return redirect()->back()->with('fail', 'Gagal menolak Rekam Bimbingan Tugas Akhir, id tidak ditemukan');
        }
    }

    public function viewDaftarBimbingan()
    {
        $dosenId = Dosen::where('nip_nidk', Auth::user()->nim_nip)->first()->id;
        $mahasiswaBimbingan = RekamBimbingan::select(DB::raw('MIN(id) as id, mahasiswa_id'))->where('dosen_pembimbing_1_id', $dosenId)->orwhere('dosen_pembimbing_2_id', $dosenId)->groupBy('mahasiswa_id')->get()->pluck('id');

        $rekamBimbingans = RekamBimbingan::whereIn('id', $mahasiswaBimbingan)->get();
        return view('dosen.bimbingan.mahasiswa', compact('rekamBimbingans', 'dosenId'));
    }

    public function detail($slug)
    {
        $mahasiswa = Mahasiswa::where('slug', $slug)->first();
        $rekamBimbingans = RekamBimbingan::where('mahasiswa_id', $mahasiswa->id)
            ->oldest()
            ->get();
        $rekamBimbingan = RekamBimbingan::where('mahasiswa_id', $mahasiswa->id)
            ->latest()
            ->first();
        $dosenId = Dosen::where('nip_nidk', Auth::user()->nim_nip)->first()->id;
        return view('dosen.bimbingan.detail', compact('rekamBimbingans', 'mahasiswa', 'rekamBimbingan', 'dosenId'));
    }

    public function informasiMahasiswa($slug)
    {
        $mahasiswa = Mahasiswa::where('slug', $slug)->first();
        $seminarSidang = SeminarSidang::with('tugas_akhir')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->latest()
            ->first();
        $proposal = SeminarSidang::where('tahapan_ta', 'Seminar Proposal')
            ->where('status_kelulusan', 'LULUS')
            ->where('tugas_akhir_id', $seminarSidang->tugas_akhir_id)
            ->first();
        $duration = null;
        if ($proposal && $seminarSidang->tanggal_pelaksanaan) {
            $startDate = new DateTime($proposal->tanggal_pelaksanaan);
            if ($seminarSidang->tahapan_ta == 'Sidang Akhir' && $seminarSidang->status_kelulusan == 'LULUS' && $seminarSidang->tanggal_pelaksanaan != null) {
                $endDate = new DateTime($seminarSidang->tanggal_pelaksanaan);
            } else {
                $endDate = new DateTime();
            }

            $interval = $endDate->diff($startDate);
            $years = $interval->y;
            $months = $interval->m;
            $days = $interval->d;

            if ($years > 0) {
                $duration = "$years tahun $months bulan $days hari";
            } elseif ($months > 0) {
                $duration = "$months bulan $days hari";
            } else {
                $duration = "$days hari";
            }
        }
        return view('dosen.bimbingan.informasi-mahasiswa', compact('mahasiswa', 'seminarSidang', 'proposal', 'duration'));
    }

    public function storeBimbingan(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kemajuan' => 'required|max:255',
            'pengerjaanSelanjutnya' => 'required|max:255',
            'tanggal' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('fail', 'Gagal menyimpan, pastikan semua bagian formulir telah terisi');
        } else {
            $bimbingan = RekamBimbingan::where('mahasiswa_id', $id)->latest()->first();
            $dosenId = Dosen::where('nip_nidk', Auth::user()->nim_nip)->first()->id;
            RekamBimbingan::create([
                'mahasiswa_id' => $id,
                'dosen_pembimbing_1_id' => $bimbingan->dosen_pembimbing_1_id,
                'dosen_pembimbing_2_id' => $bimbingan->dosen_pembimbing_2_id,
                'uraian_kemajuan_ta' => $request->kemajuan,
                'pengerjaan_selanjutnya' => $request->pengerjaanSelanjutnya,
                'tanggal_bimbingan' => $request->tanggal,
                'status_rekam_bimbingan' => 'Diterima',
                'pembimbing_id' => $dosenId,
            ]);

            return redirect()->back()->with('success', 'Rekam Bimbingan Berhasi Disimpan');
        }
    }

    public function viewEdit($slug)
    {
        $rekamBimbingan = RekamBimbingan::where('slug', $slug)->first();
        return view('dosen.bimbingan.edit', compact('rekamBimbingan'));
    }

    public function storeEdit(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
            'kemajuan' => 'required',
            'status' => 'required',
            'pengerjaanSelanjutnya' => 'required',
            'tanggal' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('fail', 'Gagal mengedit, pastikan semua bagian formulir telah terisi');
        } else {
            $rekamBimbingan = RekamBimbingan::where('slug', $slug)->first();

            $rekamBimbingan->tanggal_bimbingan = $request->tanggal;
            $rekamBimbingan->uraian_kemajuan_ta = $request->kemajuan;
            $rekamBimbingan->pengerjaan_selanjutnya = $request->pengerjaanSelanjutnya;
            $rekamBimbingan->status_rekam_bimbingan = $request->status;
            $rekamBimbingan->update();

            return redirect()
                ->route('dosen.daftar.bimbingan.detail', $rekamBimbingan->mahasiswa->slug)
                ->with('success', 'Rekam Bimbingan Berhasi Diedit');
        }
    }

    public function delete($slug)
    {
        $rekamBimbingan = RekamBimbingan::where('slug', $slug)->first();
        if ($rekamBimbingan) {
            $rekamBimbingan->delete();
            return redirect()
                ->route('dosen.daftar.bimbingan.detail', $rekamBimbingan->mahasiswa->slug)
                ->with('success', 'Berhasil MENGHAPUS Rekam Bimbingan');
        } else {
            return redirect()
                ->route('dosen.daftar.bimbingan.detail', $rekamBimbingan->mahasiswa->slug)
                ->with('fail', 'Gagal MENGHAPUS Rekam Bimbingan, ID rekam bimbingan tidak ditemukan');
        }
    }
}
