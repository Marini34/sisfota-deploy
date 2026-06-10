<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\TugasAkhir;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DosenMonitorController extends Controller
{
    protected function getDosenId()
    {
        return Dosen::where('user_id', Auth::id())->value('id');
    }

    // =========================
    // BIMBINGAN AKTIF
    // =========================
    public function bimbinganAktif()
    {
        $dosenId = $this->getDosenId();
        $showTahunLulus = false;

        $rows = Mahasiswa::with('tugasAkhir', 'seminarSidangData', 'rekamBimbinganData')
            ->whereHas('tugasAkhir', function ($q) use ($dosenId) {
                $q->where('dosen_pembimbing_1_id', $dosenId)
                    ->orWhere('dosen_pembimbing_2_id', $dosenId);
            })
            ->whereNull('tahun_lulus')
            ->orderBy('nama_lengkap')
            ->get();

        $title = 'Data Bimbingan Aktif';

        return view('dosen.monitor.index', compact('rows', 'title', 'showTahunLulus', 'dosenId'));
    }

    // =========================
    // GRAFIK BIMBINGAN (FIXED)
    // =========================
    public function grafikBimbingan($mahasiswaId)
    {
        $mahasiswa = Mahasiswa::findOrFail($mahasiswaId);

        $data = DB::table('rekam_bimbingan')
            ->selectRaw('
                YEAR(created_at) as tahun,
                MONTH(created_at) as bulan,
                COUNT(*) as total
            ')
            ->where('mahasiswa_id', $mahasiswaId)
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at) ASC, MONTH(created_at) ASC')
            ->get();

        $bulanMap = [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'Mei',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Agu',
            9 => 'Sep',
            10 => 'Okt',
            11 => 'Nov',
            12 => 'Des',
        ];

        $labels = $data->map(function ($d) use ($bulanMap) {
            return $bulanMap[$d->bulan].' '.$d->tahun;
        });

        $values = $data->pluck('total');

        return view('dosen.monitor.grafik-bimbingan', [
            'mahasiswa' => $mahasiswa,
            'labels' => $labels,
            'values' => $values,
        ]);
    }

    // =========================
    // BIMBINGAN LULUS
    // =========================
    public function bimbinganLulus()
    {
        $dosenId = $this->getDosenId();
        $showTahunLulus = true;

        $mahasiswaIds = TugasAkhir::query()
            ->join('mahasiswa as m', 'm.id', '=', 'tugas_akhir.mahasiswa_id')
            ->whereNull('tugas_akhir.deleted_at')
            ->whereNotNull('m.tahun_lulus')
            ->where(function ($q) use ($dosenId) {
                $q->where('tugas_akhir.dosen_pembimbing_1_id', $dosenId)
                    ->orWhere('tugas_akhir.dosen_pembimbing_2_id', $dosenId);
            })
            ->pluck('tugas_akhir.mahasiswa_id')
            ->unique()
            ->values();

        $rows = Mahasiswa::with('tugasAkhir')
            ->whereIn('id', $mahasiswaIds)
            ->orderByDesc('tahun_lulus')
            ->get();

        $lamaPengerjaanTa = $rows->map(fn ($m) => $m->lamaPengerjaanTa());

        $title = 'Data Bimbingan Lulus';

        return view('dosen.monitor.index', compact('rows', 'title', 'showTahunLulus', 'lamaPengerjaanTa', 'dosenId'));
    }

    // =========================
    // DIUJI AKTIF
    // =========================
    public function diujiAktif()
    {
        $dosenId = $this->getDosenId();
        $showTahunLulus = false;

        $rows = Mahasiswa::with(['tugasAkhir', 'seminarSidangData'])
            ->whereHas('tugasAkhir', function ($q) use ($dosenId) {
                $q->where('dosen_penguji_1_id', $dosenId)
                    ->orWhere('dosen_penguji_2_id', $dosenId);
            })
            ->whereNull('tahun_lulus')
            ->orderBy('nama_lengkap')
            ->get();

        $title = 'Data Diuji Aktif';

        return view('dosen.monitor.index', compact('rows', 'title', 'showTahunLulus', 'dosenId'));
    }

    // =========================
    // DIUJI LULUS
    // =========================
    public function diujiLulus()
    {
        $dosenId = $this->getDosenId();
        $showTahunLulus = true;

        $rows = Mahasiswa::with('tugasAkhir')
            ->whereHas('tugasAkhir', function ($q) use ($dosenId) {
                $q->where('dosen_penguji_1_id', $dosenId)
                    ->orWhere('dosen_penguji_2_id', $dosenId);
            })
            ->whereNotNull('tahun_lulus')
            ->orderByDesc('tahun_lulus')
            ->get();

        $title = 'Data Diuji Lulus';

        return view('dosen.monitor.index', compact('rows', 'title', 'showTahunLulus', 'dosenId'));
    }
}
