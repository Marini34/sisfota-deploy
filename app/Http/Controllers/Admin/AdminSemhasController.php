<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Notifikasi;
use App\Models\ParameterPenilaian;
use App\Models\PassingGrade;
use App\Models\Penilaian;
use App\Models\SeminarSidang;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminSemhasController extends Controller
{
    public function viewPendaftaran()
    {
        $seminarSidangs = SeminarSidang::where('tahapan_ta', 'Seminar Hasil')->where('status_pendaftaran', 'Menunggu Verifikasi')->get();
        return view('admin.semhas.pendaftaran', compact('seminarSidangs'));
    }

    public function viewDetail($slug)
    {
        $dosens = Dosen::all()->except(1);
        $semhases = SeminarSidang::where('slug', $slug)->get();
        return view('admin.semhas.detail', compact('semhases', 'dosens'));
    }

    public function terima(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'jam' => 'required|in:08:00,10:00,13:00',
            'tempat' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()->with('fail', 'Data tidak valid');
        }
        $waktuDicari = $request->jam . ':00';

        // 2. Gunakan whereDate dan whereTime bawaan Laravel
        $jumlah = SeminarSidang::whereDate('tanggal_pelaksanaan', $request->tanggal)
            ->whereTime('jam_pelaksanaan', $waktuDicari)
            ->where('tahapan_ta', 'Seminar Hasil')
            ->count();
        // dd($jumlah);

        if ($jumlah >= 1) {
            return back()->with('fail', 'Kuota jam ini sudah Terisi oleh Mahasiswa Lain');
        }

        $tanggal = Carbon::parse($request->tanggal);

        // ✅ hanya Senin
        if ($tanggal->dayOfWeek !== Carbon::MONDAY) {
            return back()->with('fail', 'Hanya boleh memilih hari Senin');
        }

        // 🔧 FIX: Format jam dari '08:00' menjadi '08:00:00' agar cocok dengan database
        $jamDatabase = Carbon::parse($request->jam)->format('H:i:s');

        $seminarSidang = SeminarSidang::where('slug', $slug)->firstOrFail();

        DB::transaction(function () use ($request, $seminarSidang, $jamDatabase) {
            $seminarSidang->update([
                'tanggal_pelaksanaan' => $request->tanggal,
                'jam_pelaksanaan' => $jamDatabase, // Simpan dengan format yang konsisten
                'tempat_pelaksanaan' => $request->tempat,
                'status_pendaftaran' => 'Diterima',
            ]);
        });

        $seminarSidang->refresh();
        $this->kirimNotifikasiSeminar($seminarSidang);

        return redirect()->route('admin.semhas')
            ->with('success', 'Pendaftaran Berhasil Diterima');
    }
    public function cekKuota(Request $request)
    {
        $tanggal = $request->tanggal;

        // Ambil data jumlah mahasiswa per jam pada tanggal tersebut
        // yang sudah berstatus 'Diterima'
        return SeminarSidang::select(
            DB::raw("DATE_FORMAT(jam_pelaksanaan, '%H:%i') as jam"),
            DB::raw('count(*) as total')
        )
            ->where('tanggal_pelaksanaan', $tanggal)
            ->where('tahapan_ta', 'Seminar Hasil')
            ->where('status_pendaftaran', 'Diterima') // PENTING: Hanya hitung yang sudah diterima
            ->groupBy('jam')
            ->pluck('total', 'jam');
        // Hasilnya akan seperti: {"08:00": 3, "10:00": 1}
    }
    private function kirimNotifikasiSeminar($ss)
    {
        // 🔥 pastikan relasi ada
        $ss->loadMissing(['tugasAkhir', 'mahasiswa']);

        $ta = $ss->tugasAkhir;
        $mhs = $ss->mahasiswa;

        // ❗ safety (hindari error kalau data kosong)
        if (!$ta || !$mhs) {
            return;
        }

        // 🔥 ambil semua dosen terkait
        $dosenIds = collect([
            $ta->dosen_pembimbing_1_id,
            $ta->dosen_pembimbing_2_id,
            $ta->dosen_penguji_1_id,
            $ta->dosen_penguji_2_id,
        ])
            ->filter()   // buang null
            ->unique();  // hilangkan duplikat

        // 🔥 format keterangan (lebih clean)
        $keterangan = trim("
Jadwal {$ss->tahapan_ta}

📅 Tanggal : {$ss->tanggal_pelaksanaan}
⏰ Jam     : {$ss->jam_pelaksanaan}
📍 Tempat  : {$ss->tempat_pelaksanaan}

👨‍🎓 {$mhs->nama_lengkap} ({$mhs->nim})
");

        // 🔥 insert notif
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

            return redirect()->route('admin.semhas')->with('success', 'Pendaftaran Berhasil Ditolak');
        }
    }

    public function batal($slug)
    {
        $seminarSidang = SeminarSidang::where('slug', $slug)->first();

        $seminarSidang->tanggal_pelaksanaan = null;
        $seminarSidang->jam_pelaksanaan = null;
        $seminarSidang->tempat_pelaksanaan = null;
        $seminarSidang->status_pendaftaran = 'Menunggu Verifikasi';
        $seminarSidang->save();

        return redirect()->route('admin.semhas.riwayat')->with('success', 'Verifikasi Dibatalkan');
    }

    public function viewRiwayat()
    {
        $seminarSidangs = SeminarSidang::where('tahapan_ta', 'Seminar Hasil')
            ->whereIn('status_pendaftaran', ['Diterima', 'Ditolak'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.semhas.riwayat', compact('seminarSidangs'));
    }

    public function viewBeritaAcara()
    {
        $seminarSidangs = SeminarSidang::where('tahapan_ta', 'Seminar Hasil')
            ->whereIn('status_kelulusan', ['LULUS', 'TIDAK LULUS'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.semhas.berita-acara', compact('seminarSidangs'));
    }

    // public function cetakBeritaAcara($slug)
    // {
    //     $seminarSidang = SeminarSidang::where('slug', $slug)->first();
    //     $passingGrade = PassingGrade::where('tahapan_ta', 'Seminar Hasil')->first()->nilai;
    //     // $parameterPenilaians = ParameterPenilaian::where('tahapan_ta', 'Seminar Hasil')->get();
    //     $parameterPenilaianIds = ParameterPenilaian::where('tahapan_ta', 'Seminar Hasil')->pluck('id');
    //     $pembimbing1nilais = Penilaian::where('seminar_sidang_id', $seminarSidang->id)
    //         ->where('dosen_id', $seminarSidang->tugas_akhir->dosen_pembimbing_1_id)
    //         ->whereIn('parameter_penilaian_id', $parameterPenilaianIds)
    //         ->get();
    //     $pembimbing2nilais = Penilaian::where('seminar_sidang_id', $seminarSidang->id)
    //         ->where('dosen_id', $seminarSidang->tugas_akhir->dosen_pembimbing_2_id)
    //         ->whereIn('parameter_penilaian_id', $parameterPenilaianIds)
    //         ->get();
    //     $penguji1nilais = Penilaian::where('seminar_sidang_id', $seminarSidang->id)
    //         ->where('dosen_id', $seminarSidang->tugas_akhir->dosen_penguji_1_id)
    //         ->whereIn('parameter_penilaian_id', $parameterPenilaianIds)
    //         ->get();
    //     $penguji2nilais = Penilaian::where('seminar_sidang_id', $seminarSidang->id)
    //         ->where('dosen_id', $seminarSidang->tugas_akhir->dosen_penguji_2_id)
    //         ->whereIn('parameter_penilaian_id', $parameterPenilaianIds)
    //         ->get();
    //     $kaprodiId = DB::table('role_user')->where('role_id', 4)->pluck('user_id');
    //     $kaprodi = Dosen::where('user_id', $kaprodiId)->first();
    //     $pdf = Pdf::loadView('admin.semhas.cetak', compact('seminarSidang', 'passingGrade', 'kaprodi', 'pembimbing1nilais', 'pembimbing2nilais', 'penguji1nilais', 'penguji2nilais'))
    //         ->setPaper('a4', 'portrait')
    //         ->setOptions([
    //             'font-family' => 'times-new-roman',
    //         ]);
    //     return $pdf->stream('Berita Acara Seminar Hasil ' . ucwords(strtolower($seminarSidang->mahasiswa->nama_lengkap)) . '.pdf');
    // }

    private function getDataForBeritaAcara($slug)
    {
        $seminarSidang = SeminarSidang::where('slug', $slug)->first();
        $passingGrade = PassingGrade::where('tahapan_ta', 'Seminar Hasil')->first()->nilai;
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

        $kaprodiId = DB::table('role_user')->where('role_id', 4)->pluck('user_id');
        $kaprodi = Dosen::where('user_id', $kaprodiId)->first();

        return compact('seminarSidang', 'passingGrade', 'kaprodi', 'pembimbing1nilais', 'pembimbing2nilais', 'penguji1nilais', 'penguji2nilais');
    }

    public function cetakBeritaAcara($slug)
    {
        $data = $this->getDataForBeritaAcara($slug);

        $pdf = Pdf::loadView('admin.semhas.cetak', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'font-family' => 'times-new-roman',
            ]);

        return $pdf->stream('Berita Acara Seminar Hasil ' . ucwords(strtolower($data['seminarSidang']->mahasiswa->nama_lengkap)) . '.pdf');
    }

    public function updateTA(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            'studi_kasus' => 'required',
            'metode' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('fail', 'Gagal memperbarui data. Pastikan semua field terisi.');
        }

        $seminarSidang = SeminarSidang::where('slug', $slug)->firstOrFail();
        $tugasAkhir = $seminarSidang->tugas_akhir;

        $tugasAkhir->update([
            'judul' => $request->judul,
            'studi_kasus' => $request->studi_kasus,
            'metode' => $request->metode,
        ]);

        return redirect()->back()->with('success', 'Data Tugas Akhir berhasil diperbarui.');
    }
}