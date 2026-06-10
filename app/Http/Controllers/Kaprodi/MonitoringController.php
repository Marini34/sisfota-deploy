<?php

namespace App\Http\Controllers\Kaprodi;

use DateTime;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\SeminarSidang;
use App\Charts\monitoringChart;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class MonitoringController extends Controller
{
    // public function viewMonitoring(Request $request)
    // {
    //     // START VIEW CHART JUMLAH SEMINAR SIDANG
    //     $request->session()->put('startDate', $request->input('startDate'));
    //     $request->session()->put('endDate', $request->input('endDate'));

    //     if (request('startDate')) {
    //         $mulai = request('startDate');
    //     } else {
    //         $mulai = date('Y') . '-01-01';
    //     }

    //     if (request('endDate')) {
    //         $selesai = request('endDate');
    //     } else {
    //         $selesai = date('Y') . '-12-31';
    //     }

    //     // Mendapatkan tahun pertama dari rentang tanggal
    //     $tahunPertama = (int) date('Y', strtotime($mulai));
    //     $bulanPertama = (int) date('n', strtotime($mulai));

    //     // Mendapatkan tahun terakhir dari rentang tanggal
    //     $tahunTerakhir = (int) date('Y', strtotime($selesai));
    //     $bulanTerakhir = (int) date('n', strtotime($selesai));

    //     // Menghitung total bulan dalam rentang waktu
    //     $totalBulan = ($tahunTerakhir - $tahunPertama) * 12 + ($bulanTerakhir - $bulanPertama) + 1;

    //     // Inisialisasi array untuk setiap jenis seminar
    //     $sempro = array_fill(1, $totalBulan, 0);
    //     $semhas = array_fill(1, $totalBulan, 0);
    //     $sidang = array_fill(1, $totalBulan, 0);

    //     // Mengambil data seminar proposal
    //     $seminarProposal = SeminarSidang::selectRaw('COUNT(*) as jumlah, YEAR(tanggal_pelaksanaan) as tahun, MONTH(tanggal_pelaksanaan) as bulan')
    //         ->where('tahapan_ta', 'Seminar Proposal')
    //         ->whereBetween('tanggal_pelaksanaan', [$mulai, $selesai])
    //         ->groupBy('tahun', 'bulan')
    //         ->get();

    //     // Mengisi nilai array untuk seminar proposal
    //     foreach ($seminarProposal as $item) {
    //         $bulan = ($item->tahun - $tahunPertama) * 12 + $item->bulan - $bulanPertama + 1;
    //         $sempro[$bulan] = $item->jumlah;
    //     }

    //     // Mengambil data seminar hasil
    //     $seminarHasil = SeminarSidang::selectRaw('COUNT(*) as jumlah, YEAR(tanggal_pelaksanaan) as tahun, MONTH(tanggal_pelaksanaan) as bulan')
    //         ->where('tahapan_ta', 'Seminar Hasil')
    //         ->whereBetween('tanggal_pelaksanaan', [$mulai, $selesai])
    //         ->groupBy('tahun', 'bulan')
    //         ->get();

    //     // Mengisi nilai array untuk seminar hasil
    //     foreach ($seminarHasil as $item) {
    //         $bulan = ($item->tahun - $tahunPertama) * 12 + $item->bulan - $bulanPertama + 1;
    //         $semhas[$bulan] = $item->jumlah;
    //     }

    //     // Mengambil data sidang akhir
    //     $sidangAkhir = SeminarSidang::selectRaw('COUNT(*) as jumlah, YEAR(tanggal_pelaksanaan) as tahun, MONTH(tanggal_pelaksanaan) as bulan')
    //         ->where('tahapan_ta', 'Sidang Akhir')
    //         ->whereBetween('tanggal_pelaksanaan', [$mulai, $selesai])
    //         ->groupBy('tahun', 'bulan')
    //         ->get();

    //     // Mengisi nilai array untuk sidang akhir
    //     foreach ($sidangAkhir as $item) {
    //         $bulan = ($item->tahun - $tahunPertama) * 12 + $item->bulan - $bulanPertama + 1;
    //         $sidang[$bulan] = $item->jumlah;
    //     }

    //     // Mendapatkan label bulan-tahun untuk chart
    //     $namaBulan = [];
    //     for ($i = 0; $i < $totalBulan; $i++) {
    //         $tahun = $tahunPertama + floor(($bulanPertama + $i - 1) / 12); // Mendapatkan tahun berdasarkan indeks bulan
    //         $bulan = (($bulanPertama + $i - 1) % 12) + 1; // Mendapatkan bulan berdasarkan indeks bulan
    //         $dateTime = DateTime::createFromFormat('!m Y', $bulan . ' ' . $tahun);
    //         $namaBulan[] = $dateTime->format('M y');
    //     }
    //     // Membuat chart
    //     $chart = new monitoringChart();
    //     $chart->labels($namaBulan);
    //     $chart->dataset('Data Seminar Proposal', 'line', array_values($sempro))->options([
    //         'borderColor' => 'rgba(255, 101, 5)',
    //         'backgroundColor' => 'rgba(255, 101, 5, 0.2)',
    //     ]);
    //     $chart->dataset('Data Seminar Hasil', 'line', array_values($semhas))->options([
    //         'borderColor' => '#36A2EB',
    //         'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
    //     ]);
    //     $chart->dataset('Data Sidang Akhir', 'line', array_values($sidang))->options([
    //         'borderColor' => '#078a12',
    //         'backgroundColor' => 'rgba(7, 138, 18, 0.2)',
    //     ]);
    //     $jumlahSempro = SeminarSidang::where('tahapan_ta', 'Seminar Proposal')
    //         ->whereBetween('tanggal_pelaksanaan', [$mulai, $selesai])
    //         ->get()
    //         ->count();
    //     $jumlahSemhas = SeminarSidang::where('tahapan_ta', 'Seminar Hasil')
    //         ->whereBetween('tanggal_pelaksanaan', [$mulai, $selesai])
    //         ->get()
    //         ->count();
    //     $jumlahSidang = SeminarSidang::where('tahapan_ta', 'Sidang Akhir')
    //         ->whereBetween('tanggal_pelaksanaan', [$mulai, $selesai])
    //         ->get()
    //         ->count();
    //     // END VIEW CHART JUMLAH SEMINAR SIDANG

    //     // START VIEW TABEL DURASI PENGERJAAN TA MAHASISWA //
    //     // Mengambil data Seminar Sidang terbaru untuk tiap mahasiswa berdasarkan tugas_akhir_id tertinggi
    //     $latestTugasAkhirId = SeminarSidang::select(DB::raw('MAX(tugas_akhir_id) as tugas_akhir_id, mahasiswa_id'))->groupBy('mahasiswa_id')->get();
    //     $latestTugasAkhir = SeminarSidang::whereIn('tugas_akhir_id', $latestTugasAkhirId->pluck('tugas_akhir_id'))->get();

    //     // Mengambil data paling lama dari data tugas_akhir_id tertinggi tiap mahasiswa(yakni Seminar Proposal)
    //     $latestSeminarProposals = SeminarSidang::whereIn('id', function ($query) use ($latestTugasAkhir) {
    //         $query->select(DB::raw('MIN(id)'))->from('seminar_sidang')->whereIn('tugas_akhir_id', $latestTugasAkhir->pluck('tugas_akhir_id'))->groupBy('mahasiswa_id');
    //     })->get();

    //     $latestSeminarSidangs = SeminarSidang::whereIn('id', function ($query) use ($latestTugasAkhir) {
    //         $query->select(DB::raw('MAX(id)'))->from('seminar_sidang')->whereIn('tugas_akhir_id', $latestTugasAkhir->pluck('tugas_akhir_id'))->groupBy('mahasiswa_id');
    //     })->get();
    //     // END VIEW TABEL DURASI PENGERJAAN TA MAHASISWA

    //     return view('kaprodi.monitoring.index', compact(
    //         'chart', 
    //         'jumlahSempro', 
    //         'jumlahSemhas', 
    //         'jumlahSidang', 
    //         'latestSeminarProposals', 
    //         'latestSeminarSidangs'
    //     ));
    // }

    private function getMonitoringData($mulai, $selesai)
    {
        // Mengambil data seminar proposal
        $seminarProposal = SeminarSidang::selectRaw('COUNT(*) as jumlah, YEAR(tanggal_pelaksanaan) as tahun, MONTH(tanggal_pelaksanaan) as bulan')
            ->where('tahapan_ta', 'Seminar Proposal')
            ->whereBetween('tanggal_pelaksanaan', [$mulai, $selesai])
            ->groupBy('tahun', 'bulan')
            ->get();

        // Mengambil data seminar hasil
        $seminarHasil = SeminarSidang::selectRaw('COUNT(*) as jumlah, YEAR(tanggal_pelaksanaan) as tahun, MONTH(tanggal_pelaksanaan) as bulan')
            ->where('tahapan_ta', 'Seminar Hasil')
            ->whereBetween('tanggal_pelaksanaan', [$mulai, $selesai])
            ->groupBy('tahun', 'bulan')
            ->get();

        // Mengambil data sidang akhir
        $sidangAkhir = SeminarSidang::selectRaw('COUNT(*) as jumlah, YEAR(tanggal_pelaksanaan) as tahun, MONTH(tanggal_pelaksanaan) as bulan')
            ->where('tahapan_ta', 'Sidang Akhir')
            ->whereBetween('tanggal_pelaksanaan', [$mulai, $selesai])
            ->groupBy('tahun', 'bulan')
            ->get();

        $jumlahSempro = SeminarSidang::where('tahapan_ta', 'Seminar Proposal')
            ->whereBetween('tanggal_pelaksanaan', [$mulai, $selesai])
            ->count();

        $jumlahSemhas = SeminarSidang::where('tahapan_ta', 'Seminar Hasil')
            ->whereBetween('tanggal_pelaksanaan', [$mulai, $selesai])
            ->count();

        $jumlahSidang = SeminarSidang::where('tahapan_ta', 'Sidang Akhir')
            ->whereBetween('tanggal_pelaksanaan', [$mulai, $selesai])
            ->count();

        // Mengambil data Seminar Sidang terbaru untuk tiap mahasiswa berdasarkan tugas_akhir_id tertinggi
        $latestTugasAkhirId = SeminarSidang::select(DB::raw('MAX(tugas_akhir_id) as tugas_akhir_id, mahasiswa_id'))->groupBy('mahasiswa_id')->get();
        $latestTugasAkhir = SeminarSidang::whereIn('tugas_akhir_id', $latestTugasAkhirId->pluck('tugas_akhir_id'))->get();

        // Mengambil data paling lama dari data tugas_akhir_id tertinggi tiap mahasiswa(yakni Seminar Proposal)
        $latestSeminarProposals = SeminarSidang::whereIn('id', function ($query) use ($latestTugasAkhir) {
            $query->select(DB::raw('MIN(id)'))->from('seminar_sidang')->whereIn('tugas_akhir_id', $latestTugasAkhir->pluck('tugas_akhir_id'))->groupBy('mahasiswa_id');
        })->get();

        $latestSeminarSidangs = SeminarSidang::whereIn('id', function ($query) use ($latestTugasAkhir) {
            $query->select(DB::raw('MAX(id)'))->from('seminar_sidang')->whereIn('tugas_akhir_id', $latestTugasAkhir->pluck('tugas_akhir_id'))->groupBy('mahasiswa_id');
        })->get();

        return [
            'seminarProposal' => $seminarProposal,
            'seminarHasil' => $seminarHasil,
            'sidangAkhir' => $sidangAkhir,
            'jumlahSempro' => $jumlahSempro,
            'jumlahSemhas' => $jumlahSemhas,
            'jumlahSidang' => $jumlahSidang,
            'latestSeminarProposals' => $latestSeminarProposals,
            'latestSeminarSidangs' => $latestSeminarSidangs,
        ];
    }

    public function viewMonitoring(Request $request)
    {
        // START VIEW CHART JUMLAH SEMINAR SIDANG
        $request->session()->put('startDate', $request->input('startDate'));
        $request->session()->put('endDate', $request->input('endDate'));

        $mulai = $request->input('startDate') ?: date('Y') . '-01-01';
        $selesai = $request->input('endDate') ?: date('Y') . '-12-31';

        // Mendapatkan tahun pertama dari rentang tanggal
        $tahunPertama = (int) date('Y', strtotime($mulai));
        $bulanPertama = (int) date('n', strtotime($mulai));

        // Mendapatkan tahun terakhir dari rentang tanggal
        $tahunTerakhir = (int) date('Y', strtotime($selesai));
        $bulanTerakhir = (int) date('n', strtotime($selesai));

        // Menghitung total bulan dalam rentang waktu
        $totalBulan = ($tahunTerakhir - $tahunPertama) * 12 + ($bulanTerakhir - $bulanPertama) + 1;

        // Inisialisasi array untuk setiap jenis seminar
        $sempro = array_fill(1, $totalBulan, 0);
        $semhas = array_fill(1, $totalBulan, 0);
        $sidang = array_fill(1, $totalBulan, 0);

        $monitoringData = $this->getMonitoringData($mulai, $selesai);

        // Mengisi nilai array untuk seminar proposal
        foreach ($monitoringData['seminarProposal'] as $item) {
            $bulan = ($item->tahun - $tahunPertama) * 12 + $item->bulan - $bulanPertama + 1;
            $sempro[$bulan] = $item->jumlah;
        }

        // Mengisi nilai array untuk seminar hasil
        foreach ($monitoringData['seminarHasil'] as $item) {
            $bulan = ($item->tahun - $tahunPertama) * 12 + $item->bulan - $bulanPertama + 1;
            $semhas[$bulan] = $item->jumlah;
        }

        // Mengisi nilai array untuk sidang akhir
        foreach ($monitoringData['sidangAkhir'] as $item) {
            $bulan = ($item->tahun - $tahunPertama) * 12 + $item->bulan - $bulanPertama + 1;
            $sidang[$bulan] = $item->jumlah;
        }

        // Mendapatkan label bulan-tahun untuk chart
        $namaBulan = [];
        for ($i = 0; $i < $totalBulan; $i++) {
            $tahun = $tahunPertama + floor(($bulanPertama + $i - 1) / 12); // Mendapatkan tahun berdasarkan indeks bulan
            $bulan = (($bulanPertama + $i - 1) % 12) + 1; // Mendapatkan bulan berdasarkan indeks bulan
            $dateTime = DateTime::createFromFormat('!m Y', $bulan . ' ' . $tahun);
            $namaBulan[] = $dateTime->format('M y');
        }

        // Membuat chart
        $chart = new monitoringChart();
        $chart->labels($namaBulan);
        $chart->dataset('Data Seminar Proposal', 'line', array_values($sempro))->options([
            'borderColor' => 'rgba(255, 101, 5)',
            'backgroundColor' => 'rgba(255, 101, 5, 0.2)',
        ]);
        $chart->dataset('Data Seminar Hasil', 'line', array_values($semhas))->options([
            'borderColor' => '#36A2EB',
            'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
        ]);
        $chart->dataset('Data Sidang Akhir', 'line', array_values($sidang))->options([
            'borderColor' => '#078a12',
            'backgroundColor' => 'rgba(7, 138, 18, 0.2)',
        ]);

        return view('kaprodi.monitoring.index', [
            'chart' => $chart,
            'jumlahSempro' => $monitoringData['jumlahSempro'],
            'jumlahSemhas' => $monitoringData['jumlahSemhas'],
            'jumlahSidang' => $monitoringData['jumlahSidang'],
            'latestSeminarProposals' => $monitoringData['latestSeminarProposals'],
            'latestSeminarSidangs' => $monitoringData['latestSeminarSidangs'],
        ]);
    }


    public function viewDetail($slug)
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

        return view('kaprodi.monitoring.detail', compact('mahasiswa','seminarSidang', 'proposal', 'duration'));
    }
}
