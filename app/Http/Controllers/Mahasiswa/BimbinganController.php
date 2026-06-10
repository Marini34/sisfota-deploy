<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\JadwalBimbingan;
use App\Models\Mahasiswa;
use App\Models\RekamBimbingan;
use App\Models\SeminarSidang;
use App\Models\TugasAkhir;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BimbinganController extends Controller
{
    public function getJadwalBimbinganDosen(Request $request)
    {
        $request->validate([
            'dosen_id' => 'required|integer',
        ]);

        $now = Carbon::now();
        $today = $now->toDateString();
        $timeNow = $now->format('H:i:s');

        $jadwal = JadwalBimbingan::query()
            ->where('dosen_id', $request->dosen_id)
            ->whereColumn('terisi', '<', 'kuota')
            ->where(function ($q) use ($today, $timeNow) {
                // tanggal setelah hari ini -> valid
                $q->where('tanggal', '>', $today)
                    // atau tanggal hari ini tapi jam_selesai masih lewat
                    ->orWhere(function ($q2) use ($today, $timeNow) {
                        $q2->where('tanggal', $today)
                            ->where('jam_selesai', '>', $timeNow);
                    });
            })
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->get()
            ->map(function ($j) {
                return [
                    'id' => $j->id,
                    'label' => Carbon::parse($j->tanggal)->format('d-m-Y') . ' | ' .
                        substr($j->jam_mulai, 0, 5) . '-' . substr($j->jam_selesai, 0, 5) .
                        ' | Lokasi: ' . ($j->lokasi ?? '-') .
                        ' | Sisa: ' . max(0, ((int) $j->kuota - (int) $j->terisi)),
                ];
            });

        return response()->json($jadwal);
    }

    public function viewBimbingan()
    {
        $mahasiswa_id = Mahasiswa::where('nim', Auth::user()->nim_nip)->first()->id;
        $seminarSidang = SeminarSidang::where('mahasiswa_id', $mahasiswa_id)->latest()->first();
        $tugasAkhir = TugasAkhir::where('mahasiswa_id', $mahasiswa_id)->latest()->first();
        $rekamBimbingans = RekamBimbingan::where('mahasiswa_id', $mahasiswa_id)->latest()->get();
        Log::channel('slack')->info(Auth::user()->name . ' Mengakses halaman bimbingan!');

        return view('mahasiswa.bimbingan.index', compact('rekamBimbingans', 'tugasAkhir', 'seminarSidang'));
    }

    public function storeBimbingan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kemajuan' => 'required|max:255',
            // 'pengerjaanSelanjutnya' => 'required|max:255',
            'pembimbing' => 'required|integer',
            'jadwal_bimbingan_id' => 'required|integer',
            'persentase_mhs' => 'required',
            'dokumen_bimbingan_mhs' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('fail', 'Gagal menyimpan, pastikan semua bagian formulir telah terisi');
        }

        $mhs_id = Mahasiswa::where('nim', Auth::user()->nim_nip)->value('id');
        $ta = TugasAkhir::where('mahasiswa_id', $mhs_id)->latest()->first();

        if (! $ta) {
            return redirect()->back()->with('fail', 'Data tugas akhir tidak ditemukan.');
        }

        try {
            DB::transaction(function () use ($request, $mhs_id, $ta) {

                // lock jadwal agar tidak bentrok (anti double booking)
                $jadwal = JadwalBimbingan::where('id', $request->jadwal_bimbingan_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                // validasi: jadwal milik dosen yang dipilih
                if ((int) $jadwal->dosen_id !== (int) $request->pembimbing) {
                    throw new \Exception('Jadwal tidak sesuai dengan dosen yang dipilih.');
                }

                // validasi: kuota masih ada
                if ((int) $jadwal->terisi >= (int) $jadwal->kuota) {
                    throw new \Exception('Kuota jadwal sudah penuh.');
                }

                // validasi: jadwal masih aktif (tanggal/jam)
                $now = Carbon::now();

                $tgl = Carbon::parse($jadwal->tanggal)->format('Y-m-d'); // pastikan cuma tanggal
                $jadwalMulai = Carbon::parse($tgl . ' ' . $jadwal->jam_mulai);
                $jadwalEnd = Carbon::parse($tgl . ' ' . $jadwal->jam_selesai);

                if ($jadwalEnd->lte($now)) {
                    throw new \Exception('Jadwal sudah lewat.');
                }
                // =========================
                // UPLOAD DOKUMEN
                // =========================
                $pathDokumen = null;

                if ($request->hasFile('dokumen_bimbingan_mhs')) {

                    $file = $request->file('dokumen_bimbingan_mhs');

                    $namaFile = time() . '_' . $file->getClientOriginalName();

                    $pathDokumen = $file->storeAs(
                        'dokumen_bimbingan_mhs',
                        $namaFile,
                        'public'
                    );
                }
                // Simpan rekam bimbingan
                RekamBimbingan::create([
                    'mahasiswa_id' => $mhs_id,
                    'dosen_pembimbing_1_id' => $ta->dosen_pembimbing_1_id,
                    'dosen_pembimbing_2_id' => $ta->dosen_pembimbing_2_id,
                    'uraian_kemajuan_ta' => $request->kemajuan,
                    // 'pengerjaan_selanjutnya' => $request->pengerjaanSelanjutnya,

                    // tanggal bimbingan diambil dari jadwal
                    'tanggal_bimbingan' => $tgl,
                    'pembimbing_id' => $request->pembimbing,
                    'persentase_mhs' => $request->persentase_mhs,
                    'dokumen_bimbingan_mhs' => $pathDokumen,
                ]);

                // update terisi
                $jadwal->increment('terisi');
            });

            return redirect()->back()->with('success', 'Reservasi bimbingan berhasil disimpan.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('fail', $e->getMessage());
        }
    }

    public function viewEdit($slug)
    {
        $rekamBimbingan = RekamBimbingan::where('slug', $slug)->first();
        if ($rekamBimbingan) {
            if ($rekamBimbingan->mahasiswa->user_id == Auth::user()->id) {
                Log::channel('slack')->info(Auth::user()->name . ' Mengubah data bimbingan!');

                return view('mahasiswa.bimbingan.edit', compact('rekamBimbingan'));
            } else {
                abort(403, 'Biar Ape Sih? Biar Keren? Iye iye kaulah yang paling keren');
            }
        } else {
            abort(404);
        }
    }

    public function storeEdit(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
            'kemajuan' => 'required|max:255',
            'pengerjaanSelanjutnya' => 'required|max:255',
            'tanggal' => 'required',
            'pembimbing' => 'required',
            'persentase_mhs' => 'required|integer|min:0|max:100',
            'dokumen_bimbingan_mhs' => 'nullable|file|mimes:pdf,doc,docx|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('fail', 'Gagal mengedit, pastikan semua bagian formulir telah terisi');
        }

        $rekamBimbingan = RekamBimbingan::where('slug', $slug)->firstOrFail();

        try {

            // ======================
            // HANDLE UPLOAD FILE
            // ======================
            if ($request->hasFile('dokumen_bimbingan_mhs')) {

                // hapus file lama jika ada
                if ($rekamBimbingan->dokumen_bimbingan_mhs) {
                    Storage::disk('public')->delete($rekamBimbingan->dokumen_bimbingan_mhs);
                }

                $file = $request->file('dokumen_bimbingan_mhs');

                $namaFile = time() . '_' . $file->getClientOriginalName();

                $path = $file->storeAs(
                    'dokumen_bimbingan_mhs',
                    $namaFile,
                    'public'
                );

                $rekamBimbingan->dokumen_bimbingan_mhs = $path;
            }

            // ======================
            // UPDATE DATA
            // ======================
            $rekamBimbingan->persentase_mhs = $request->persentase_mhs;
            $rekamBimbingan->pembimbing_id = $request->pembimbing;
            $rekamBimbingan->tanggal_bimbingan = $request->tanggal;
            $rekamBimbingan->uraian_kemajuan_ta = $request->kemajuan;
            $rekamBimbingan->pengerjaan_selanjutnya = $request->pengerjaanSelanjutnya;
            $rekamBimbingan->status_rekam_bimbingan = 'Menunggu Verifikasi';

            $rekamBimbingan->save();

            return redirect()
                ->route('view.bimbingan')
                ->with('success', 'Rekam Bimbingan Berhasil Diedit');
        } catch (\Throwable $e) {

            return redirect()->back()
                ->with('fail', 'Gagal mengedit data: ' . $e->getMessage());
        }
    }

    public function delete($slug)
    {
        $rekamBimbingan = RekamBimbingan::where('slug', $slug)->first();
        if ($rekamBimbingan && ($rekamBimbingan->mahasiswa->user_id == Auth::user()->id)) {
            $rekamBimbingan->delete();
            Log::channel('slack')->info(Auth::user()->name . ' Menghapus data bimbingan!');

            return redirect()->route('view.bimbingan')->with('success', 'Berhasil MENGHAPUS Rekam Bimbingan');
        } else {
            return redirect()->route('view.bimbingan')->with('fail', 'Gagal MENGHAPUS Rekam Bimbingan, ID rekam bimbingan tidak ditemukan');
        }
    }
}
