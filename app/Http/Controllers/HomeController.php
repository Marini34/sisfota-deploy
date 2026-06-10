<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\RekamBimbingan;
use App\Models\SeminarSidang;
use App\Models\StatusTranskrip;
use App\Models\TugasAkhir;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirect()
    {
        if (Auth::user()->hasRole('mahasiswa')) {
            return redirect('/mahasiswa/dashboard');
        } elseif (Auth::user()->hasRole('admin')) {
            return redirect('/admin/dashboard');
        } elseif (Auth::user()->hasRole('kaprodi')) {
            return redirect('/kaprodi/dashboard');
        } elseif (Auth::user()->hasRole('dosen')) {
            return redirect('/dosen/dashboard');
        } else {
            return redirect('/login');
        }
    }

    public function dosen()
    {
        $dosenId = Dosen::where('nip_nidk', Auth::user()->nim_nip)->first()->id;
        $tugasAkhirIds = TugasAkhir::where('dosen_pembimbing_1_id', $dosenId)->orwhere('dosen_pembimbing_2_id', $dosenId)->orwhere('dosen_penguji_1_id', $dosenId)->orwhere('dosen_penguji_2_id', $dosenId)->get()->pluck('id');
        $sempro = SeminarSidang::whereIn('tugas_akhir_id', $tugasAkhirIds)
            ->where('tahapan_ta', 'Seminar Proposal')
            ->where('status_pendaftaran', 'Diterima')
            ->whereDoesntHave('penilaians', function ($query) use ($dosenId) {
                $query->where('dosen_id', $dosenId);
            })
            ->whereDate('tanggal_pelaksanaan', '>=', Carbon::now()->subDays(7))
            ->get();
        $semhas = SeminarSidang::whereIn('tugas_akhir_id', $tugasAkhirIds)
            ->where('tahapan_ta', 'Seminar Hasil')
            ->where('status_pendaftaran', 'Diterima')
            ->whereDoesntHave('penilaians', function ($query) use ($dosenId) {
                $query->where('dosen_id', $dosenId);
            })
            ->whereDate('tanggal_pelaksanaan', '>=', Carbon::now()->subDays(7))
            ->get();
        $sidang = SeminarSidang::whereIn('tugas_akhir_id', $tugasAkhirIds)
            ->where('tahapan_ta', 'Sidang Akhir')
            ->where('status_pendaftaran', 'Diterima')
            ->whereDoesntHave('penilaians', function ($query) use ($dosenId) {
                $query->where('dosen_id', $dosenId);
            })
            ->whereDate('tanggal_pelaksanaan', '>=', Carbon::now()->subDays(7))
            ->get();
        $dosenPembimbing = RekamBimbingan::where('dosen_pembimbing_1_id', $dosenId)->orwhere('dosen_pembimbing_2_id', $dosenId)->get();
        $bimbingans = RekamBimbingan::where('pembimbing_id', $dosenId)->get();
        $bimbingan = RekamBimbingan::where('pembimbing_id', $dosenId)->where('status_rekam_bimbingan', 'Menunggu Verifikasi')->get();

        // start marini dosen logic
        $dosenId = Dosen::where('user_id', Auth::id())->value('id');

        if (! $dosenId) {
            $data['BimbinganAktif'] = 0;
            $data['BimbinganLulus'] = 0;
            $data['diujiAktif'] = 0;
            $data['diujiLulus'] = 0;

            return view('dosen.dashboard', $data, compact('sempro', 'semhas', 'sidang', 'bimbingan'));
        }

        // Kolom dosen di tugas_akhir
        $PB1 = 'dosen_pembimbing_1_id';
        $PB2 = 'dosen_pembimbing_2_id';
        $UJ1 = 'dosen_penguji_1_id';
        $UJ2 = 'dosen_penguji_2_id';

        // =========================
        // BIMBINGAN AKTIF (tahun_lulus NULL)
        // =========================
        $data['BimbinganAktif'] = TugasAkhir::query()
            ->join('mahasiswa as m', 'm.id', '=', 'tugas_akhir.mahasiswa_id')
            ->whereNull('tugas_akhir.deleted_at')
            ->whereNull('m.tahun_lulus')
            ->where(function ($q) use ($dosenId, $PB1, $PB2) {
                $q->where("tugas_akhir.$PB1", $dosenId)
                    ->orWhere("tugas_akhir.$PB2", $dosenId);
            })
            ->distinct('tugas_akhir.mahasiswa_id')
            ->count('tugas_akhir.mahasiswa_id');

        // =========================
        // BIMBINGAN LULUS (tahun_lulus NOT NULL)
        // =========================
        $data['BimbinganLulus'] = TugasAkhir::query()
            ->join('mahasiswa as m', 'm.id', '=', 'tugas_akhir.mahasiswa_id')
            ->whereNull('tugas_akhir.deleted_at')
            ->whereNotNull('m.tahun_lulus')
            ->where(function ($q) use ($dosenId, $PB1, $PB2) {
                $q->where("tugas_akhir.$PB1", $dosenId)
                    ->orWhere("tugas_akhir.$PB2", $dosenId);
            })
            ->distinct('tugas_akhir.mahasiswa_id')
            ->count('tugas_akhir.mahasiswa_id');

        // =========================
        // DIUJI AKTIF (tahun_lulus NULL)
        // =========================
        $data['diujiAktif'] = TugasAkhir::query()
            ->join('mahasiswa as m', 'm.id', '=', 'tugas_akhir.mahasiswa_id')
            ->whereNull('tugas_akhir.deleted_at')
            ->whereNull('m.tahun_lulus')
            ->where(function ($q) use ($dosenId, $UJ1, $UJ2) {
                $q->where("tugas_akhir.$UJ1", $dosenId)
                    ->orWhere("tugas_akhir.$UJ2", $dosenId);
            })
            ->distinct('tugas_akhir.mahasiswa_id')
            ->count('tugas_akhir.mahasiswa_id');

        // =========================
        // DIUJI LULUS (tahun_lulus NOT NULL)
        // =========================
        $data['diujiLulus'] = TugasAkhir::query()
            ->join('mahasiswa as m', 'm.id', '=', 'tugas_akhir.mahasiswa_id')
            ->whereNull('tugas_akhir.deleted_at')
            ->whereNotNull('m.tahun_lulus')
            ->where(function ($q) use ($dosenId, $UJ1, $UJ2) {
                $q->where("tugas_akhir.$UJ1", $dosenId)
                    ->orWhere("tugas_akhir.$UJ2", $dosenId);
            })
            ->distinct('tugas_akhir.mahasiswa_id')
            ->count('tugas_akhir.mahasiswa_id');
        // marini end

        return view('dosen.dashboard', $data, compact('sempro', 'semhas', 'sidang', 'bimbingan'));
    }

    public function kaprodi()
    {
        $dosenId = Dosen::where('nip_nidk', Auth::user()->nim_nip)->first()->id;
        $tugasAkhirIds = TugasAkhir::where('dosen_pembimbing_1_id', $dosenId)->orwhere('dosen_pembimbing_2_id', $dosenId)->orwhere('dosen_penguji_1_id', $dosenId)->orwhere('dosen_penguji_2_id', $dosenId)->get()->pluck('id');
        $sempro = SeminarSidang::whereIn('tugas_akhir_id', $tugasAkhirIds)
            ->where('tahapan_ta', 'Seminar Proposal')
            ->where('status_pendaftaran', 'Diterima')
            ->whereDoesntHave('penilaians', function ($query) use ($dosenId) {
                $query->where('dosen_id', $dosenId);
            })
            ->whereDate('tanggal_pelaksanaan', '>=', Carbon::now()->subDays(7))
            ->get();
        $semhas = SeminarSidang::whereIn('tugas_akhir_id', $tugasAkhirIds)
            ->where('tahapan_ta', 'Seminar Hasil')
            ->where('status_pendaftaran', 'Diterima')
            ->whereDoesntHave('penilaians', function ($query) use ($dosenId) {
                $query->where('dosen_id', $dosenId);
            })
            ->whereDate('tanggal_pelaksanaan', '>=', Carbon::now()->subDays(7))
            ->get();
        $sidang = SeminarSidang::whereIn('tugas_akhir_id', $tugasAkhirIds)
            ->where('tahapan_ta', 'Sidang Akhir')
            ->where('status_pendaftaran', 'Diterima')
            ->whereDoesntHave('penilaians', function ($query) use ($dosenId) {
                $query->where('dosen_id', $dosenId);
            })
            ->whereDate('tanggal_pelaksanaan', '>=', Carbon::now()->subDays(7))
            ->get();
        $dosenPembimbing = RekamBimbingan::where('dosen_pembimbing_1_id', $dosenId)->orwhere('dosen_pembimbing_2_id', $dosenId)->get();
        $bimbingans = RekamBimbingan::where('pembimbing_id', $dosenId)->get();
        $bimbingan = RekamBimbingan::where('pembimbing_id', $dosenId)->where('status_rekam_bimbingan', 'Menunggu Verifikasi')->get();
        // marini
        // Rata-rata Waktu Pengerjaan TA
        $mahasiswaData = Mahasiswa::all();

        $totalHari = 0;
        $totalMahasiswa = 0;

        foreach ($mahasiswaData as $mhs) {

            $seminarProposal = SeminarSidang::where('mahasiswa_id', $mhs->id)
                ->where('tahapan_ta', 'Seminar Proposal')
                ->where('status_kelulusan', 'LULUS')
                ->first();

            $sidangAkhir = SeminarSidang::where('mahasiswa_id', $mhs->id)
                ->where('tahapan_ta', 'Sidang Akhir')
                ->where('status_kelulusan', 'LULUS')
                ->first();

            if ($seminarProposal && $sidangAkhir) {

                $tanggalProposal = Carbon::parse($seminarProposal->tanggal_pelaksanaan);
                $tanggalSidang = Carbon::parse($sidangAkhir->tanggal_pelaksanaan);

                $selisihHari = $tanggalProposal->diffInDays($tanggalSidang);

                $totalHari += $selisihHari;
                $totalMahasiswa++;
            }
        }

        $rataRataHari = $totalMahasiswa > 0 ? round($totalHari / $totalMahasiswa) : 0;

        // 🔥 Konversi ke Bulan & Hari
        $bulan = floor($rataRataHari / 30);
        $hari = $rataRataHari % 30;

        $formatWaktu = $bulan.' Bulan '.$hari.' Hari';

        $data['hasilCountLamaTa'] = $formatWaktu;
        // Rata-rata Waktu Pengerjaan TA
        // Mahasiswa aktif TA
        // 1️⃣ Proposal LULUS
        $proposalLulus = SeminarSidang::where('tahapan_ta', 'Seminar Proposal')
            ->where('status_kelulusan', 'LULUS')
            ->pluck('mahasiswa_id');

        // 2️⃣ Sidang Akhir LULUS (yang sudah selesai TA)
        $sidangLulus = SeminarSidang::where('tahapan_ta', 'Sidang Akhir')
            ->where('status_kelulusan', 'LULUS')
            ->pluck('mahasiswa_id');

        // 3️⃣ Aktif TA = Proposal LULUS tapi belum Sidang LULUS
        $mahasiswaAktifTa = $proposalLulus
            ->diff($sidangLulus)
            ->unique();

        $data['countMahasiswaAktifTa'] = $mahasiswaAktifTa->count();
        // Mahasiswa aktif TA
        // Kelulusan Tepat Waktu (KTW)
        $mahasiswaTepatWaktu = Mahasiswa::whereNotNull('tahun_lulus')
            ->whereRaw('(tahun_lulus - tahun_masuk) <= 4')
            ->count();
        $datamahasiswaTepatWaktu = Mahasiswa::whereNotNull('tahun_lulus')
            ->whereRaw('(tahun_lulus - tahun_masuk) <= 4')->get();
        $data['countKTW'] = $mahasiswaTepatWaktu;
        // Kelulusan Tepat Waktu (KTW)
        // lama masa studi
        $rataLMS = Mahasiswa::whereNotNull('tahun_lulus')
            ->selectRaw('AVG(tahun_lulus - tahun_masuk) as rata_lms')
            ->value('rata_lms');

        $rataLMS = round($rataLMS, 2);

        $tahun = floor($rataLMS);
        $bulan = round(($rataLMS - $tahun) * 12);

        $data['rataLMS'] = $tahun.' Tahun '.$bulan.' Bulan';
        $data['dataLMS'] = Mahasiswa::whereNotNull('tahun_lulus')->get();
        // lama masa studi

        // marini end
        return view('kaprodi.dashboard', $data, compact('sempro', 'semhas', 'sidang', 'bimbingan'));
    }

    public function mahasiswa()
    {
        return view('mahasiswa.dashboard');
    }

    public function admin()
    {
        $users = User::where('status', null)->get();
        $sempro = SeminarSidang::where('tahapan_ta', 'Seminar Proposal')->where('status_pendaftaran', 'Menunggu Verifikasi')->get();
        $semhas = SeminarSidang::where('tahapan_ta', 'Seminar Hasil')->where('status_pendaftaran', 'Menunggu Verifikasi')->get();
        $sidang = SeminarSidang::where('tahapan_ta', 'Sidang Akhir')->where('status_pendaftaran', 'Menunggu Verifikasi')->get();
        $transkrip = StatusTranskrip::where('status_transkrip', 'Menunggu Verifikasi')->get();

        return view('admin.dashboard', compact('users', 'sempro', 'semhas', 'sidang', 'transkrip'));
    }

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
}
