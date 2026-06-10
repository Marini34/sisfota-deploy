<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\RekamBimbingan;
use App\Models\SeminarSidang;
use App\Models\TugasAkhir;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MonitorController extends Controller
{
    //
    public function pengerjaanTa(Request $request)
    {
        $data['title'] = 'Lama Pengerjaan TA';

        $angkatan = $request->query('angkatan');
        $data['selectedAngkatan'] = $angkatan;

        // 🔥 dropdown angkatan
        $data['listAngkatan'] = Mahasiswa::select('tahun_masuk')
            ->distinct()
            ->orderBy('tahun_masuk', 'desc')
            ->pluck('tahun_masuk');

        $rowsAll = SeminarSidang::query()
            ->select('mahasiswa_id', 'tugas_akhir_id')

            // ========================
            // SUBQUERY TANGGAL
            // ========================
            ->selectSub(function ($q) {
                $q->from('seminar_sidang as ss2')
                    ->select('tanggal_pelaksanaan')
                    ->whereColumn('ss2.mahasiswa_id', 'seminar_sidang.mahasiswa_id')
                    ->whereColumn('ss2.tugas_akhir_id', 'seminar_sidang.tugas_akhir_id')
                    ->where('ss2.tahapan_ta', 'Seminar Proposal')
                    ->where('ss2.status_kelulusan', 'LULUS')
                    ->orderBy('ss2.tanggal_pelaksanaan', 'asc')
                    ->limit(1);
            }, 'tgl_proposal')

            ->selectSub(function ($q) {
                $q->from('seminar_sidang as ss2')
                    ->select('tanggal_pelaksanaan')
                    ->whereColumn('ss2.mahasiswa_id', 'seminar_sidang.mahasiswa_id')
                    ->whereColumn('ss2.tugas_akhir_id', 'seminar_sidang.tugas_akhir_id')
                    ->where('ss2.tahapan_ta', 'Seminar Hasil')
                    ->where('ss2.status_kelulusan', 'LULUS')
                    ->orderBy('ss2.tanggal_pelaksanaan', 'asc')
                    ->limit(1);
            }, 'tgl_hasil')

            ->selectSub(function ($q) {
                $q->from('seminar_sidang as ss2')
                    ->select('tanggal_pelaksanaan')
                    ->whereColumn('ss2.mahasiswa_id', 'seminar_sidang.mahasiswa_id')
                    ->whereColumn('ss2.tugas_akhir_id', 'seminar_sidang.tugas_akhir_id')
                    ->where('ss2.tahapan_ta', 'Sidang Akhir')
                    ->where('ss2.status_kelulusan', 'LULUS')
                    ->orderBy('ss2.tanggal_pelaksanaan', 'asc')
                    ->limit(1);
            }, 'tgl_sidang')

            ->with(['mahasiswa', 'tugasAkhir'])
            ->groupBy('mahasiswa_id', 'tugas_akhir_id')
            ->get()

            // ========================
            // HITUNG DURASI
            // ========================
            ->map(function ($r) {
                if ($r->tgl_proposal && $r->tgl_sidang) {

                    $start = Carbon::parse($r->tgl_proposal);
                    $end = Carbon::parse($r->tgl_sidang);

                    if ($end->lessThan($start)) {
                        $r->durasi_ta = '-';
                        $r->durasi_tahun = null;
                    } else {
                        $diff = $start->diff($end);

                        $r->durasi_ta = $diff->format('%y tahun %m bulan %d hari');
                        $r->durasi_tahun = $start->diffInDays($end) / 365;
                    }
                } else {
                    $r->durasi_ta = '-';
                    $r->durasi_tahun = null;
                }

                return $r;
            });

        // ========================
        // FILTER ANGKATAN
        // ========================
        $rows = $rowsAll->filter(function ($r) use ($angkatan) {
            if (! $angkatan) {
                return true;
            }

            return $r->mahasiswa?->tahun_masuk == $angkatan;
        });

        $data['rows'] = $rows;

        // ========================
        // 🔥 RATA-RATA PER ANGKATAN
        // ========================
        $data['rataPerAngkatan'] = $rowsAll
            ->filter(fn ($r) => $r->durasi_tahun !== null)
            ->groupBy(fn ($r) => $r->mahasiswa->tahun_masuk)
            ->map(function ($items, $angkatan) {
                return (object) [
                    'angkatan' => $angkatan,
                    'rata' => round($items->avg('durasi_tahun'), 2),
                ];
            })
            ->sortByDesc('angkatan')
            ->values();

        $data['rataTotal'] = round(
            $rowsAll->filter(fn ($r) => $r->durasi_tahun !== null)
                ->avg('durasi_tahun'),
            2
        );

        // =========================
        // 🔥 GRAFIK 3 BATANG (5 TAHUN)
        // =========================
        $tahunSekarang = now()->year;
        $tahunAwalGrafik = $tahunSekarang - 5;

        $angkatanList = Mahasiswa::whereBetween('tahun_masuk', [$tahunAwalGrafik, $tahunSekarang])
            ->pluck('tahun_masuk')
            ->unique()
            ->sort()
            ->values();

        $sempro = SeminarSidang::select(
            'mahasiswa.tahun_masuk',
            DB::raw('count(distinct seminar_sidang.mahasiswa_id) as total')
        )
            ->join('mahasiswa', 'mahasiswa.id', '=', 'seminar_sidang.mahasiswa_id')
            ->where('tahapan_ta', 'Seminar Proposal')
            ->whereBetween('mahasiswa.tahun_masuk', [$tahunAwalGrafik, $tahunSekarang])
            ->groupBy('mahasiswa.tahun_masuk')
            ->pluck('total', 'tahun_masuk');

        $semhas = SeminarSidang::select(
            'mahasiswa.tahun_masuk',
            DB::raw('count(distinct seminar_sidang.mahasiswa_id) as total')
        )
            ->join('mahasiswa', 'mahasiswa.id', '=', 'seminar_sidang.mahasiswa_id')
            ->where('tahapan_ta', 'Seminar Hasil')
            ->whereBetween('mahasiswa.tahun_masuk', [$tahunAwalGrafik, $tahunSekarang])
            ->groupBy('mahasiswa.tahun_masuk')
            ->pluck('total', 'tahun_masuk');

        $sidang = SeminarSidang::select(
            'mahasiswa.tahun_masuk',
            DB::raw('count(distinct seminar_sidang.mahasiswa_id) as total')
        )
            ->join('mahasiswa', 'mahasiswa.id', '=', 'seminar_sidang.mahasiswa_id')
            ->where('tahapan_ta', 'Sidang Akhir')
            ->whereBetween('mahasiswa.tahun_masuk', [$tahunAwalGrafik, $tahunSekarang])
            ->groupBy('mahasiswa.tahun_masuk')
            ->pluck('total', 'tahun_masuk');

        $labelsGrafikTA = [];
        $dataSempro = [];
        $dataSemhas = [];
        $dataSidang = [];

        foreach ($angkatanList as $angkatan) {
            $labelsGrafikTA[] = (string) $angkatan;
            $dataSempro[] = (int) ($sempro[$angkatan] ?? 0);
            $dataSemhas[] = (int) ($semhas[$angkatan] ?? 0);
            $dataSidang[] = (int) ($sidang[$angkatan] ?? 0);
        }

        $data['labelsGrafikTA'] = $labelsGrafikTA;
        $data['dataSempro'] = $dataSempro;
        $data['dataSemhas'] = $dataSemhas;
        $data['dataSidang'] = $dataSidang;

        return view('kaprodi.monitor.pengerjaanTa', $data);
    }

    public function mhsAktifTa(Request $request)
    {

        $data['title'] = 'Mahasiswa Aktif TA';
        // ambil filter angkatan
        $angkatan = $request->query('angkatan');
        $data['selectedAngkatan'] = $angkatan;

        // 🔥 ambil list angkatan (buat dropdown)
        $data['listAngkatan'] = Mahasiswa::select('tahun_masuk')
            ->distinct()
            ->orderBy('tahun_masuk', 'desc')
            ->pluck('tahun_masuk');

        $proposalLulus = SeminarSidang::where('tahapan_ta', 'Seminar Proposal')
            ->where('status_kelulusan', 'LULUS')
            ->pluck('mahasiswa_id');

        $sidangLulus = SeminarSidang::where('tahapan_ta', 'Sidang Akhir')
            ->where('status_kelulusan', 'LULUS')
            ->pluck('mahasiswa_id');

        $mahasiswaAktifTaIds = $proposalLulus
            ->diff($sidangLulus)
            ->unique()
            ->values();

        // 🔥 query mahasiswa
        $query = Mahasiswa::query()
            ->whereIn('id', $mahasiswaAktifTaIds);

        // 🔥 filter angkatan
        if ($angkatan) {
            $query->where('tahun_masuk', $angkatan);
        }

        // Ambil data mahasiswa + info tahapan terakhir + judul TA (via tugas_akhir_id dari record terakhir)
        $data['mahasiswaAktifTa'] = $query
            ->with(['seminarSidang' => function ($q) {
                $q->orderBy('tanggal_pelaksanaan', 'desc');
            }])
            ->get()
            ->map(function ($mhs) {
                $last = $mhs->seminarSidang->first();

                $mhs->current_tahapan = $last?->tahapan_ta ?? '-';
                $mhs->current_status = $last?->status_kelulusan ?? '-';
                $mhs->current_judul = $last?->tugasAkhir?->judul ?? '-';

                return $mhs;
            });
        $data['totalAktif'] = $data['mahasiswaAktifTa']->count();

        return view('kaprodi.monitor.mhsAktifTa', $data);
    }

    public function monBimbinganMhs()
    {
        $data['title'] = 'Monitoring Bimbingan';
        // sesuaikan nama kolom sesuai DB kamu
        $PB1 = 'dosen_pembimbing_1_id';
        $PB2 = 'dosen_pembimbing_2_id';
        $UJ1 = 'dosen_penguji_1_id';
        $UJ2 = 'dosen_penguji_2_id';

        $data['dosenRows'] = Dosen::query()
            ->leftJoin('tugas_akhir as ta', function ($join) use ($PB1, $PB2, $UJ1, $UJ2) {
                $join->on("ta.$PB1", '=', 'dosen.id')
                    ->orOn("ta.$PB2", '=', 'dosen.id')
                    ->orOn("ta.$UJ1", '=', 'dosen.id')
                    ->orOn("ta.$UJ2", '=', 'dosen.id');
            })
            ->leftJoin('mahasiswa as m', 'm.id', '=', 'ta.mahasiswa_id')
            ->whereNull('ta.deleted_at')
            ->select(
                'dosen.id',
                'dosen.nama_dosen',

                DB::raw("SUM(CASE WHEN ta.$PB1 = dosen.id AND m.tahun_lulus IS NULL THEN 1 ELSE 0 END) as jml_bim1"),
                DB::raw("SUM(CASE WHEN ta.$PB2 = dosen.id AND m.tahun_lulus IS NULL THEN 1 ELSE 0 END) as jml_bim2"),
                DB::raw("SUM(CASE WHEN ta.$UJ1 = dosen.id AND m.tahun_lulus IS NULL THEN 1 ELSE 0 END) as jml_uji1"),
                DB::raw("SUM(CASE WHEN ta.$UJ2 = dosen.id AND m.tahun_lulus IS NULL THEN 1 ELSE 0 END) as jml_uji2")
            )
            ->groupBy('dosen.id', 'dosen.nama_dosen')
            ->orderBy('dosen.nama_dosen')
            ->get()
            ->map(function ($d) {
                $d->jml_aktif_bimbingan = (int) $d->jml_bim1 + (int) $d->jml_bim2;
                $d->jml_aktif_diuji = (int) $d->jml_uji1 + (int) $d->jml_uji2;

                return $d;
            });

        return view('kaprodi.monitor.monBimbinganMhs', $data);
    }

    public function monBimbinganMhsDetail(Request $request, Dosen $dosen)
    {
        $data['title'] = 'Detail Monitoring Dosen';
        $data['dosen'] = $dosen;
        $angkatan = $request->query('angkatan');
        $data['selectedAngkatan'] = $angkatan;
        $data['listAngkatan'] = Mahasiswa::select('tahun_masuk')
            ->distinct()
            ->orderByDesc('tahun_masuk')
            ->pluck('tahun_masuk');

        $role = $request->query('role', 'pembimbing'); // pembimbing|penguji
        $data['role'] = $role;

        $PB1 = 'dosen_pembimbing_1_id';
        $PB2 = 'dosen_pembimbing_2_id';
        $UJ1 = 'dosen_penguji_1_id';
        $UJ2 = 'dosen_penguji_2_id';

        // =========================
        // 🔥 HITUNG UNTUK GRAFIK
        // =========================
        $data['grafikSummary'] = [
            'totalBimbingan' => TugasAkhir::whereNull('deleted_at')
                ->whereHas('mahasiswa', fn ($q) => $q->whereNull('tahun_lulus'))
                ->where(function ($q) use ($dosen, $PB1, $PB2) {
                    $q->where($PB1, $dosen->id)
                        ->orWhere($PB2, $dosen->id);
                })
                ->count(),
            'totalDiuji' => TugasAkhir::whereNull('deleted_at')
                ->whereHas('mahasiswa', fn ($q) => $q->whereNull('tahun_lulus'))
                ->where(function ($q) use ($dosen, $UJ1, $UJ2) {
                    $q->where($UJ1, $dosen->id)
                        ->orWhere($UJ2, $dosen->id);
                })
                ->count(),

            'pb1' => TugasAkhir::where($PB1, $dosen->id)
                ->whereNull('deleted_at')
                ->whereHas('mahasiswa', fn ($q) => $q->whereNull('tahun_lulus'))
                ->count(),

            'pb2' => TugasAkhir::where($PB2, $dosen->id)
                ->whereNull('deleted_at')
                ->whereHas('mahasiswa', fn ($q) => $q->whereNull('tahun_lulus'))
                ->count(),

            'uj1' => TugasAkhir::where($UJ1, $dosen->id)
                ->whereNull('deleted_at')
                ->whereHas('mahasiswa', fn ($q) => $q->whereNull('tahun_lulus'))
                ->count(),

            'uj2' => TugasAkhir::where($UJ2, $dosen->id)
                ->whereNull('deleted_at')
                ->whereHas('mahasiswa', fn ($q) => $q->whereNull('tahun_lulus'))
                ->count(),
        ];

        if ($role === 'penguji') {
            $data['rows'] = TugasAkhir::query()
                ->with('mahasiswa')
                ->whereNull('deleted_at')
                ->whereHas('mahasiswa', function ($q) use ($angkatan) {
                    $q->whereNull('tahun_lulus');

                    if ($angkatan) {
                        $q->where('tahun_masuk', $angkatan);
                    }
                })
                ->where(function ($q) use ($dosen, $UJ1, $UJ2) {
                    $q->where($UJ1, $dosen->id)->orWhere($UJ2, $dosen->id);
                })
                ->select('tugas_akhir.*')
                ->selectSub(function ($q) {
                    $q->from('seminar_sidang as ss')
                        ->select('tahapan_ta')
                        ->whereColumn('ss.tugas_akhir_id', 'tugas_akhir.id')
                        ->orderBy('ss.tanggal_pelaksanaan', 'desc')
                        ->limit(1);
                }, 'tahapan_terakhir')
                ->get()
                ->map(function ($ta) use ($dosen, $UJ1) {
                    $ta->sebagai = ($dosen->id == $ta->$UJ1) ? 'Penguji 1' : 'Penguji 2';

                    return $ta;
                });
        } else {
            $data['rows'] = TugasAkhir::query()
                ->with('mahasiswa')
                ->whereNull('deleted_at')
                ->whereHas('mahasiswa', function ($q) use ($angkatan) {
                    $q->whereNull('tahun_lulus');

                    if ($angkatan) {
                        $q->where('tahun_masuk', $angkatan);
                    }
                })
                ->where(function ($q) use ($dosen, $PB1, $PB2) {
                    $q->where($PB1, $dosen->id)->orWhere($PB2, $dosen->id);
                })
                ->get()
                ->map(function ($ta) use ($dosen, $PB1) {
                    $ta->sebagai = ($dosen->id == $ta->$PB1) ? 'Pembimbing 1' : 'Pembimbing 2';

                    return $ta;
                });
        }

        return view('kaprodi.monitor.monBimbinganMhsDetail', $data);
    }

    public function rekamBimbinganMhs(Dosen $dosen, Mahasiswa $mahasiswa)
    {
        $data['title'] = 'Rekam Bimbingan Mahasiswa';
        $data['dosen'] = $dosen;
        $data['mahasiswa'] = $mahasiswa;

        $data['rekamRows'] = RekamBimbingan::query()
            ->where('mahasiswa_id', $mahasiswa->id)
            ->where('pembimbing_id', $dosen->id) // yang ngisi bimbingan (dosen tsb)
            ->whereNull('deleted_at')
            ->orderBy('tanggal_bimbingan', 'desc')
            ->get();

        return view('kaprodi.monitor.rekamBimbinganMhs', $data);
    }

    public function mhsKtw(Request $request)
    {
        $data['title'] = 'Mahasiswa Lulus Tepat Waktu';

        $angkatan = $request->query('angkatan');

        // =========================
        // DROPDOWN ANGKATAN
        // =========================
        $data['listAngkatan'] = Mahasiswa::query()
            ->whereNotNull('tahun_masuk')
            ->select('tahun_masuk')
            ->distinct()
            ->orderBy('tahun_masuk', 'desc')
            ->pluck('tahun_masuk');

        // =========================
        // DATA TABEL
        // =========================
        $q = Mahasiswa::query()
            ->whereNotNull('tahun_lulus')
            ->whereNotNull('tahun_masuk')
            ->whereRaw('(tahun_lulus - tahun_masuk) <= 4');

        if ($angkatan) {
            $q->where('tahun_masuk', $angkatan);
        }

        $data['selectedAngkatan'] = $angkatan;
        $data['datamahasiswaTepatWaktu'] = $q
            ->orderBy('tahun_lulus', 'desc')
            ->get();

        // =========================
        // 🔥 AUTO TAHUN TERBARU
        // =========================
        $tahunSekarang = max(
            Mahasiswa::max('tahun_lulus') ?? 0,
            date('Y')
        );

        // =========================
        // 🔥 GRAFIK 5 TAHUN (JUMLAH)
        // =========================
        $tahunAwal = $tahunSekarang - 4;

        $rawGrafik = Mahasiswa::select(
            'tahun_lulus',
            DB::raw('count(*) as total')
        )
            ->whereNotNull('tahun_lulus')
            ->whereNotNull('tahun_masuk')
            ->whereRaw('(tahun_lulus - tahun_masuk) <= 4')
            ->whereBetween('tahun_lulus', [$tahunAwal, $tahunSekarang])
            ->groupBy('tahun_lulus')
            ->pluck('total', 'tahun_lulus');

        $tahunGrafik = [];
        $totalGrafik = [];

        for ($i = $tahunAwal; $i <= $tahunSekarang; $i++) {
            $tahunGrafik[] = (string) $i;
            $totalGrafik[] = (int) ($rawGrafik[$i] ?? 0);
        }

        $data['tahunGrafik'] = $tahunGrafik;
        $data['totalGrafik'] = $totalGrafik;

        // =========================
        // 🔥 GRAFIK PERSEN (6 TAHUN)
        // =========================
        $tahunAwalPersen = $tahunSekarang - 5;

        // total mahasiswa per angkatan
        $totalPerAngkatan = Mahasiswa::select(
            'tahun_masuk',
            DB::raw('count(*) as total')
        )
            ->whereBetween('tahun_masuk', [$tahunAwalPersen, $tahunSekarang])
            ->groupBy('tahun_masuk')
            ->pluck('total', 'tahun_masuk');

        // lulus tepat waktu
        $lulusTepatWaktu = Mahasiswa::select(
            'tahun_masuk',
            DB::raw('count(*) as total')
        )
            ->whereNotNull('tahun_lulus')
            ->whereNotNull('tahun_masuk')
            ->whereRaw('(tahun_lulus - tahun_masuk) <= 4')
            ->whereBetween('tahun_masuk', [$tahunAwalPersen, $tahunSekarang])
            ->groupBy('tahun_masuk')
            ->pluck('total', 'tahun_masuk');

        // belum lulus
        $belumLulus = Mahasiswa::select(
            'tahun_masuk',
            DB::raw('count(*) as total')
        )
            ->whereNull('tahun_lulus')
            ->whereBetween('tahun_masuk', [$tahunAwalPersen, $tahunSekarang])
            ->groupBy('tahun_masuk')
            ->pluck('total', 'tahun_masuk');

        $labelsPersen = [];
        $dataPersen = [];
        $dataBelumLulus = [];

        for ($i = $tahunAwalPersen; $i <= $tahunSekarang; $i++) {

            $total = $totalPerAngkatan[$i] ?? 0;
            $lulus = $lulusTepatWaktu[$i] ?? 0;
            $belum = $belumLulus[$i] ?? 0;

            $persen = $total > 0 ? round(($lulus / $total) * 100) : 0;

            $labelsPersen[] = (string) $i;

            $dataPersen[] = [
                'persen' => (int) $persen,
                'total' => (int) $total,
            ];

            $dataBelumLulus[] = (int) $belum;
        }

        $data['labelsPersen'] = $labelsPersen;
        $data['dataPersen'] = $dataPersen;
        $data['dataBelumLulus'] = $dataBelumLulus;

        return view('kaprodi.monitor.mhsKtw', $data);
    }

    public function mhsLms(Request $request)
    {
        $data['title'] = 'Mahasiswa dengan Lama Masa Studi';

        $status = $request->query('status', 'all');
        $angkatan = $request->query('angkatan');

        $data['selectedStatus'] = $status;
        $data['selectedAngkatan'] = $angkatan;

        $q = Mahasiswa::query()->whereNotNull('tahun_masuk');

        if ($status === 'lulus') {
            $q->whereNotNull('tahun_lulus');
        } elseif ($status === 'belum') {
            $q->whereNull('tahun_lulus');
        }

        if ($angkatan) {
            $q->where('tahun_masuk', $angkatan);
        }

        $mhs = $q->orderBy('tahun_masuk', 'asc')->get();

        // 🔥 ambil list angkatan untuk dropdown
        $data['listAngkatan'] = Mahasiswa::select('tahun_masuk')
            ->distinct()
            ->orderBy('tahun_masuk', 'desc')
            ->pluck('tahun_masuk');

        $today = Carbon::now();

        $data['mhsLmsData'] = $mhs->map(function ($m) use ($today) {

            $start = Carbon::createFromDate($m->tahun_masuk, $m->bln_masuk ?? 1, $m->tgl_masuk ?? 1);

            if ($m->tahun_lulus) {
                $end = Carbon::createFromDate($m->tahun_lulus, $m->bln_lulus ?? 1, $m->tgl_lulus ?? 1);
                $m->status_lulus = 'Lulus';
            } else {
                $end = $today;
                $m->status_lulus = 'Belum Lulus';
            }

            $diff = $start->diff($end);

            $m->lms = $diff->format('%y tahun %m bulan %d hari');

            // 🔥 LMS dalam angka (buat rata-rata)
            // $m->lms_tahun = $start->diffInDays($end) / 365;
            $m->lms_tahun = round($start->diffInDays($end) / 365.25, 2);

            return $m;
        });

        // 🔥 rata-rata LMS (per filter)
        $data['rataLms'] = round(
            $data['mhsLmsData']->avg('lms_tahun'),
            2
        );

        // 🔥 rata-rata LMS per angkatan
        $data['rataPerAngkatan'] = $data['mhsLmsData']
            ->groupBy('tahun_masuk')
            ->map(function ($items, $tahun) {
                return (object) [
                    'tahun_masuk' => $tahun,
                    'rata' => round($items->avg('lms_tahun'), 1),
                ];
            })
            ->sortByDesc('tahun_masuk')
            ->values();

        // 🔥 grafik 5 tahun terakhir
        $tahunSekarang = now()->year;
        $data['grafik'] = Mahasiswa::whereNotNull('tahun_lulus')
            ->whereBetween('tahun_lulus', [$tahunSekarang - 4, $tahunSekarang])
            ->selectRaw('tahun_lulus, AVG(tahun_lulus - tahun_masuk) as rata')
            ->groupBy('tahun_lulus')
            ->orderBy('tahun_lulus')
            ->get();
        // $data['grafik'] = $data['mhsLmsData']
        //     ->filter(function ($m) use ($tahunSekarang) {
        //         $tahun = $m->tahun_lulus ?? $tahunSekarang;
        //         return $tahun >= ($tahunSekarang - 4);
        //     })
        //     ->groupBy(function ($m) use ($tahunSekarang) {
        //         return $m->tahun_lulus ?? $tahunSekarang;
        //     })
        //     ->map(function ($items, $tahun) {
        //         return (object)[
        //             'tahun_lulus' => $tahun,
        //             'rata' => round($items->avg('lms_tahun'), 1)
        //         ];
        //     })
        //     ->sortBy('tahun_lulus')
        //     ->values();

        return view('kaprodi.monitor.mhsLms', $data);
    }

    public function repoMhs()
    {
        $data['title'] = 'Repositori Jurnal Mahasiswa';

        $data['rows'] = SeminarSidang::query()
            ->with(['mahasiswa', 'tugasAkhir'])
            ->where('tahapan_ta', 'Sidang Akhir')
            ->where('status_pendaftaran', 'Diterima')
            ->where('status_kelulusan', 'LULUS')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($item) {
                $lampiran = [];

                if (! empty($item->file_notulensi)) {
                    $decoded = json_decode($item->file_notulensi, true);
                    if (is_array($decoded)) {
                        $lampiran = $decoded;
                    }
                }

                $item->dokumen_jurnal = count($lampiran) > 0 ? $lampiran[1] : null;
                $item->bukti_submit_jurnal = count($lampiran) > 0 ? end($lampiran) : null;

                return $item;
            });

        return view('kaprodi.monitor.repoMhs', $data);
    }
}
