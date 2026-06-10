<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Notifikasi;
use App\Models\ParameterPenilaian;
use App\Models\PassingGrade;
use App\Models\Penilaian;
use App\Models\SeminarSidang;
use App\Models\TugasAkhir;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DosenSemproController extends Controller
{
    // public function viewPenilaian()
    // {
    //     $dosenId = Dosen::where('nip_nidk', Auth::user()->nim_nip)->first()->id;
    //     $tugasAkhirIds = TugasAkhir::where('dosen_pembimbing_1_id', $dosenId)->orwhere('dosen_pembimbing_2_id', $dosenId)->orwhere('dosen_penguji_1_id', $dosenId)->orwhere('dosen_penguji_2_id', $dosenId)->get()->pluck('id');
    //     $seminarSidangs = SeminarSidang::whereIn('tugas_akhir_id', $tugasAkhirIds)
    //         ->where('tahapan_ta', 'Seminar Proposal')
    //         ->where('status_pendaftaran', 'Diterima')
    //         ->whereDoesntHave('penilaians', function ($query) use ($dosenId) {
    //             $query->where('dosen_id', $dosenId);
    //         })->whereDate('tanggal_pelaksanaan', '>=', Carbon::now()->subDays(7))
    //         ->get();
    //     return view('dosen.sempro.index', compact('seminarSidangs', 'dosenId'));
    // }
    public function dibatalkan()
    {
        $dosenId = Dosen::where('nip_nidk', Auth::user()->nim_nip)->first()->id;

        $data['title'] = 'Riwayat Pembatalan Seminar';

        $data['rows'] = SeminarSidang::with(['mahasiswa', 'tugasAkhir'])
            ->where('status_pendaftaran', 'Dibatalkan')
            ->whereHas('tugasAkhir', function ($q) use ($dosenId) {
                $q->where('dosen_pembimbing_1_id', $dosenId)
                    ->orWhere('dosen_pembimbing_2_id', $dosenId)
                    ->orWhere('dosen_penguji_1_id', $dosenId)
                    ->orWhere('dosen_penguji_2_id', $dosenId);
            })
            ->orderByDesc('updated_at')
            ->get();

        return view('dosen.sempro.dibatalkan', $data);
    }
    private function getDataForViewPenilaian($userId)
    {
        $dosen = Dosen::where('nip_nidk', $userId)->first();
        $dosenId = $dosen ? $dosen->id : null;

        if (!$dosenId) {
            return ['dosenId' => null, 'seminarSidangs' => collect()];
        }

        $tugasAkhirIds = TugasAkhir::where('dosen_pembimbing_1_id', $dosenId)
        ->orWhere('dosen_pembimbing_2_id', $dosenId)
        ->orWhere('dosen_penguji_1_id', $dosenId)
        ->orWhere('dosen_penguji_2_id', $dosenId)
        ->get()->pluck('id');

        $seminarSidangs = SeminarSidang::whereIn('tugas_akhir_id', $tugasAkhirIds)
            ->where('tahapan_ta', 'Seminar Proposal')
            ->where('status_pendaftaran', 'Diterima')
            ->whereDoesntHave('penilaians', function ($query) use ($dosenId) {
                $query->where('dosen_id', $dosenId);
            })
            // ->whereDate('tanggal_pelaksanaan', '>=', Carbon::now()->subDays(7))
            ->orderBy('tanggal_pelaksanaan', 'asc')
            ->get();

        return compact('dosenId', 'seminarSidangs');
    }

    public function viewPenilaian()
    {
        $data = $this->getDataForViewPenilaian(Auth::user()->nim_nip);

        if (!$data['dosenId']) {
            // Handle case where dosen is not found or return an appropriate view/message
            return redirect()->back()->withErrors('Dosen not found or unauthorized.');
        }

        return view('dosen.sempro.index', $data);
    }

    public function viewPenilaianDetail($slug)
    {
        $dosenId = Dosen::where('nip_nidk', Auth::user()->nim_nip)->first()->id;
        $aksesKaprodi = Auth::user()->hasRole('kaprodi');
        $aksesAdmin = Auth::user()->hasRole('admin');
        $sempro = SeminarSidang::where('slug', $slug)->where('tahapan_ta', 'Seminar Proposal')->first();
        return view('dosen.sempro.detail', compact('sempro', 'dosenId', 'aksesAdmin', 'aksesKaprodi'));
    }

    public function batalkan(Request $request, $id)
    {
        $request->validate([
            'alasan_dibatalkan' => 'required|string|max:1000'
        ]);

        $ss = SeminarSidang::with('tugasAkhir', 'mahasiswa')->findOrFail($id);

        $dosen = Dosen::where('nip_nidk', Auth::user()->nim_nip)->first();
        $dosenId = $dosen->id;

        // 🔥 validasi hak akses
        if (!in_array($dosenId, [
            $ss->tugasAkhir->dosen_pembimbing_1_id,
            $ss->tugasAkhir->dosen_pembimbing_2_id
        ])) {
            abort(403, 'Tidak memiliki akses');
        }

        // 🔥 format alasan lengkap
        $alasanLengkap = "Dibatalkan oleh: {$dosen->nama_dosen}\n" .
            "Dengan Alasan: {$request->alasan_dibatalkan}";

        $ss->update([
            'status_kelulusan' => 'TIDAK LULUS',
            'status_pendaftaran' => 'Dibatalkan',
            'alasan_dibatalkan' => $alasanLengkap
        ]);

        $ss->refresh();

        // 🔥 kirim notif
        $this->kirimNotifikasiPembatalan($ss, $dosen->nama_dosen);

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

    public function viewFormPenilaian($slug)
    {
        $sempro = SeminarSidang::where('slug', $slug)->first();
        $parameters = ParameterPenilaian::where('tahapan_ta', 'Seminar Proposal')->get();
        if ($parameters->count()) {
            $totalPersentase = $parameters->sum('persentase');
        } else {
            $totalPersentase = 0;
        }
        return view('dosen.sempro.nilai', compact('sempro', 'parameters', 'totalPersentase'));
    }

    public function storePenilaianSempro(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
            'nilai.*' => 'required|numeric|min:0|max:100',
            'catatan' => 'nullable|max:255',
        ]);

        $sempro = SeminarSidang::where('slug', $slug)->first();
        $parameters = ParameterPenilaian::where('tahapan_ta', 'Seminar Proposal')->get();
        $dosenId = Dosen::where('nip_nidk', Auth::user()->nim_nip)->first()->id;
        Log::channel('slack')->info(Auth::user()->name.' Melakukan penilaian sempro!');


        $existingPenilaian = Penilaian::where('seminar_sidang_id', $sempro->id)->where('dosen_id', $dosenId)->exists();

        if ($existingPenilaian) {
            return redirect(route('dosen.sempro.penilaian'))->with('fail', 'Penilaian sebelumnya sudah berhasil, edit untuk mengubah nilai');
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('fail', 'Gagal melakukan penilaian seminar proposal');
        } else {
            foreach ($parameters as $index => $parameter) {
                Penilaian::create([
                    'seminar_sidang_id' => $sempro->id,
                    'dosen_id' => $dosenId,
                    'mahasiswa_id' => $sempro->mahasiswa->id,
                    'parameter_penilaian_id' => $parameter->id,
                    'nilai' => $request->nilai[$index],
                ]);
            }

            $perhitungans = Penilaian::where('seminar_sidang_id', $sempro->id)
                ->where('dosen_id', $dosenId)
                ->get();
            $totalNilai = 0;

            foreach ($perhitungans as $index => $perhitungan) {
                $nilais = $perhitungan->nilai;
                $parameter = $perhitungan->parameter_penilaian->persentase / 100;
                $nilai[$index] = $nilais * $parameter;
                $totalNilai += $nilai[$index];
            }

            if ($sempro->tugas_akhir->dosen_pembimbing_1_id == $dosenId) {
                $sempro->total_nilai_pembimbing_1 = $totalNilai;
                if ($request->catatan != null) {
                    $sempro->catatan_ta_pembimbing_1 = $request->catatan;
                }
                $sempro->save();
            } elseif ($sempro->tugas_akhir->dosen_pembimbing_2_id == $dosenId) {
                $sempro->total_nilai_pembimbing_2 = $totalNilai;
                if ($request->catatan != null) {
                    $sempro->catatan_ta_pembimbing_2 = $request->catatan;
                }
                $sempro->save();
            } elseif ($sempro->tugas_akhir->dosen_penguji_1_id == $dosenId) {
                $sempro->total_nilai_penguji_1 = $totalNilai;
                if ($request->catatan != null) {
                    $sempro->catatan_ta_penguji_1 = $request->catatan;
                }
                $sempro->save();
            } elseif ($sempro->tugas_akhir->dosen_penguji_2_id == $dosenId) {
                $sempro->total_nilai_penguji_2 = $totalNilai;
                if ($request->catatan != null) {
                    $sempro->catatan_ta_penguji_2 = $request->catatan;
                }
                $sempro->save();
            }

            if ($sempro->total_nilai_pembimbing_1 !== null && $sempro->total_nilai_pembimbing_2 !== null && $sempro->total_nilai_penguji_1 !== null && $sempro->total_nilai_penguji_2 !== null) {
                $totalNilaiAkhir = round(($sempro->total_nilai_pembimbing_1 + $sempro->total_nilai_pembimbing_2 + $sempro->total_nilai_penguji_1 + $sempro->total_nilai_penguji_2) / 4, 2);
                $sempro->total_nilai_akhir = $totalNilaiAkhir;
                $sempro->save();
            } elseif ($sempro->total_nilai_pembimbing_1 == null && $sempro->total_nilai_pembimbing_2 !== null && $sempro->total_nilai_penguji_1 !== null && $sempro->total_nilai_penguji_2 !== null) {
                $totalNilaiAkhir = ($sempro->total_nilai_pembimbing_2 + $sempro->total_nilai_penguji_1 + $sempro->total_nilai_penguji_2) / 3;
                $sempro->total_nilai_akhir = number_format($totalNilaiAkhir, 2);
                $sempro->save();
            } elseif ($sempro->total_nilai_pembimbing_1 !== null && $sempro->total_nilai_pembimbing_2 == null && $sempro->total_nilai_penguji_1 !== null && $sempro->total_nilai_penguji_2 !== null) {
                $totalNilaiAkhir = ($sempro->total_nilai_pembimbing_1 + $sempro->total_nilai_penguji_1 + $sempro->total_nilai_penguji_2) / 3;
                $sempro->total_nilai_akhir = number_format($totalNilaiAkhir, 2);
                $sempro->save();
            } else {
                $sempro->total_nilai_akhir = null;
                $sempro->save();
            }

            $passingGrade = PassingGrade::where('tahapan_ta', 'Seminar Proposal')->first()->nilai;
            if ($sempro->total_nilai_akhir != null && $sempro->total_nilai_akhir >= $passingGrade) {
                $sempro->status_kelulusan = 'LULUS';
                $sempro->save();
            } elseif ($sempro->total_nilai_akhir != null && $sempro->total_nilai_akhir < $passingGrade) {
                $sempro->status_kelulusan = 'TIDAK LULUS';
                $sempro->save();
            } else {
                $sempro->status_kelulusan = 'Menunggu Keputusan';
                $sempro->save();
            }

            return redirect()->route('dosen.sempro.penilaian')->with('success', 'Berhasil melakukan penilaian Seminar Proposal');
        }
    }

    // public function viewRiwayat()
    // {
    //     $dosenId = Dosen::where('nip_nidk', Auth::user()->nim_nip)->first()->id;
    //     $tugasAkhirIds = TugasAkhir::where('dosen_pembimbing_1_id', $dosenId)->orwhere('dosen_pembimbing_2_id', $dosenId)->orwhere('dosen_penguji_1_id', $dosenId)->orwhere('dosen_penguji_2_id', $dosenId)->get()->pluck('id');
    //     $seminarSidangs = SeminarSidang::whereIn('tugas_akhir_id', $tugasAkhirIds)
    //         ->where('tahapan_ta', 'Seminar Proposal')
    //         ->where('status_pendaftaran', 'Diterima')
    //         ->where(function ($query) use ($dosenId) {
    //             $query
    //                 ->whereHas('penilaians', function ($subQuery) use ($dosenId) {
    //                     $subQuery->where('dosen_id', $dosenId);
    //                 })
    //                 ->orWhere(function ($subQuery) use ($dosenId) {
    //                     $subQuery
    //                         ->whereDoesntHave('penilaians', function ($nestedQuery) use ($dosenId) {
    //                             $nestedQuery->where('dosen_id', $dosenId);
    //                         })
    //                         ->whereDate('tanggal_pelaksanaan', '<', Carbon::now()->subDays(7));
    //                 });
    //         })
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     $sempros = SeminarSidang::whereIn('tugas_akhir_id', $tugasAkhirIds)->where('tahapan_ta', 'Seminar Hasil')->where('status_pendaftaran', 'Diterima')->get();
    //     $semprosIds = $sempros->pluck('tugas_akhir_id')->toArray();
    //     return view('dosen.sempro.riwayat', compact('seminarSidangs', 'dosenId', 'semprosIds'));
    // }

    private function getDataForViewRiwayat($userId)
    {
        $dosen = Dosen::where('nip_nidk', $userId)->first();
        $dosenId = $dosen ? $dosen->id : null;

        if (!$dosenId) {
            return ['dosenId' => null, 'seminarSidangs' => collect(), 'semprosIds' => []];
        }

        $tugasAkhirIds = TugasAkhir::where('dosen_pembimbing_1_id', $dosenId)->orWhere('dosen_pembimbing_2_id', $dosenId)->orWhere('dosen_penguji_1_id', $dosenId)->orWhere('dosen_penguji_2_id', $dosenId)->get()->pluck('id');

        $seminarSidangs = SeminarSidang::whereIn('tugas_akhir_id', $tugasAkhirIds)
            ->where('tahapan_ta', 'Seminar Proposal')
            ->where('status_pendaftaran', 'Diterima')
            ->where(function ($query) use ($dosenId) {
                $query
                    ->whereHas('penilaians', function ($subQuery) use ($dosenId) {
                        $subQuery->where('dosen_id', $dosenId);
                    })
                    ->orWhere(function ($subQuery) use ($dosenId) {
                        $subQuery
                            ->whereDoesntHave('penilaians', function ($nestedQuery) use ($dosenId) {
                                $nestedQuery->where('dosen_id', $dosenId);
                            })
                            ->whereDate('tanggal_pelaksanaan', '<', Carbon::now()->subDays(7));
                    });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $sempros = SeminarSidang::whereIn('tugas_akhir_id', $tugasAkhirIds)->where('tahapan_ta', 'Seminar Hasil')->where('status_pendaftaran', 'Diterima')->get();

        $semprosIds = $sempros->pluck('tugas_akhir_id')->toArray();

        return compact('dosenId', 'seminarSidangs', 'semprosIds');
    }

    public function viewRiwayat()
    {
        $data = $this->getDataForViewRiwayat(Auth::user()->nim_nip);

        if (!$data['dosenId']) {
            // Handle case where dosen is not found or return an appropriate view/message
            return redirect()->back()->withErrors('Dosen not found or unauthorized.');
        }

        return view('dosen.sempro.riwayat', $data);
    }

    public function viewEditNilai($slug)
    {
        $sempro = SeminarSidang::where('slug', $slug)->first();
        $dosenId = Dosen::where('nip_nidk', Auth::user()->nim_nip)->first()->id;
        $nilais = Penilaian::where('seminar_sidang_id', $sempro->id)
            ->where('dosen_id', $dosenId)
            ->get();
        $parameters = ParameterPenilaian::where('tahapan_ta', 'Seminar Proposal')->get();
        $semhas = SeminarSidang::where('tugas_akhir_id', $sempro->tugas_akhir_id)
            ->where('tahapan_ta', 'Seminar Hasil')
            ->where('status_pendaftaran', 'Diterima')
            ->orderBy('created_at', 'desc')
            ->first();
        if ($parameters->count()) {
            $totalPersentase = $parameters->sum('persentase');
        } else {
            $totalPersentase = 0;
        }
        return view('dosen.sempro.edit', compact('sempro', 'nilais', 'parameters', 'totalPersentase', 'dosenId', 'semhas'));
    }

    public function storeEditNilai(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
            'nilai.*' => 'required|numeric|min:0|max:100',
            'catatan' => 'nullable|max:255',
        ]);

        $sempro = SeminarSidang::where('slug', $slug)->first();
        $parameters = ParameterPenilaian::where('tahapan_ta', 'Seminar Proposal')->get();
        $dosenId = Dosen::where('nip_nidk', Auth::user()->nim_nip)->first()->id;
        $nilais = Penilaian::where('seminar_sidang_id', $sempro->id)
            ->where('dosen_id', $dosenId)
            ->get();
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('fail', 'Gagal melakukan penilaian seminar proposal');
        } else {
            foreach ($parameters as $index => $parameter) {
                if (isset($nilais[$index])) {
                    $nilais[$index]->nilai = $request->nilai[$index];
                    $nilais[$index]->save();
                } else {
                    Penilaian::create([
                        'seminar_sidang_id' => $sempro->id,
                        'dosen_id' => $dosenId,
                        'mahasiswa_id' => $sempro->mahasiswa->id,
                        'parameter_penilaian_id' => $parameter->id,
                        'nilai' => $request->nilai[$index],
                    ]);
                }
            }

            $perhitungans = Penilaian::where('seminar_sidang_id', $sempro->id)
                ->where('dosen_id', $dosenId)
                ->get();
            $totalNilai = 0;

            foreach ($perhitungans as $index => $perhitungan) {
                $nilais = $perhitungan->nilai;
                $parameter = $perhitungan->parameter_penilaian->persentase / 100;
                $nilai[$index] = $nilais * $parameter;
                $totalNilai += $nilai[$index];
            }

            if ($sempro->tugas_akhir->dosen_pembimbing_1_id == $dosenId) {
                $sempro->total_nilai_pembimbing_1 = $totalNilai;
                if ($request->catatan != null) {
                    $sempro->catatan_ta_pembimbing_1 = $request->catatan;
                }
                $sempro->save();
            } elseif ($sempro->tugas_akhir->dosen_pembimbing_2_id == $dosenId) {
                $sempro->total_nilai_pembimbing_2 = $totalNilai;
                if ($request->catatan != null) {
                    $sempro->catatan_ta_pembimbing_2 = $request->catatan;
                }
                $sempro->save();
            } elseif ($sempro->tugas_akhir->dosen_penguji_1_id == $dosenId) {
                $sempro->total_nilai_penguji_1 = $totalNilai;
                if ($request->catatan != null) {
                    $sempro->catatan_ta_penguji_1 = $request->catatan;
                }
                $sempro->save();
            } elseif ($sempro->tugas_akhir->dosen_penguji_2_id == $dosenId) {
                $sempro->total_nilai_penguji_2 = $totalNilai;
                if ($request->catatan != null) {
                    $sempro->catatan_ta_penguji_2 = $request->catatan;
                }
                $sempro->save();
            }

            if ($sempro->total_nilai_pembimbing_1 !== null && $sempro->total_nilai_pembimbing_2 !== null && $sempro->total_nilai_penguji_1 !== null && $sempro->total_nilai_penguji_2 !== null) {
                $totalNilaiAkhir = round(($sempro->total_nilai_pembimbing_1 + $sempro->total_nilai_pembimbing_2 + $sempro->total_nilai_penguji_1 + $sempro->total_nilai_penguji_2) / 4, 2);
                $sempro->total_nilai_akhir = $totalNilaiAkhir;
                $sempro->save();
            } elseif ($sempro->total_nilai_pembimbing_1 == null && $sempro->total_nilai_pembimbing_2 !== null && $sempro->total_nilai_penguji_1 !== null && $sempro->total_nilai_penguji_2 !== null) {
                $totalNilaiAkhir = ($sempro->total_nilai_pembimbing_2 + $sempro->total_nilai_penguji_1 + $sempro->total_nilai_penguji_2) / 3;
                $sempro->total_nilai_akhir = number_format($totalNilaiAkhir, 2);
                $sempro->save();
            } elseif ($sempro->total_nilai_pembimbing_1 !== null && $sempro->total_nilai_pembimbing_2 == null && $sempro->total_nilai_penguji_1 !== null && $sempro->total_nilai_penguji_2 !== null) {
                $totalNilaiAkhir = ($sempro->total_nilai_pembimbing_1 + $sempro->total_nilai_penguji_1 + $sempro->total_nilai_penguji_2) / 3;
                $sempro->total_nilai_akhir = number_format($totalNilaiAkhir, 2);
                $sempro->save();
            } else {
                $sempro->total_nilai_akhir = null;
                $sempro->save();
            }

            $passingGrade = PassingGrade::where('tahapan_ta', 'Seminar Proposal')->first()->nilai;
            if ($sempro->total_nilai_akhir != null && $sempro->total_nilai_akhir >= $passingGrade) {
                $sempro->status_kelulusan = 'LULUS';
                $sempro->save();
            } elseif ($sempro->total_nilai_akhir != null && $sempro->total_nilai_akhir < $passingGrade) {
                $sempro->status_kelulusan = 'TIDAK LULUS';
                $sempro->save();
            } else {
                $sempro->status_kelulusan = 'Menunggu Keputusan';
                $sempro->save();
            }

            return redirect()->route('dosen.sempro.riwayat')->with('success', 'Berhasil melakukan penilaian Seminar Proposal');
        }
    }

    public function batalkanPenilaian($slug)
    {
        $sempro = SeminarSidang::where('slug', $slug)->first();
        $parameters = ParameterPenilaian::where('tahapan_ta', 'Seminar Proposal')->get();
        $dosenId = Dosen::where('nip_nidk', Auth::user()->nim_nip)->first()->id;
        $nilais = Penilaian::where('seminar_sidang_id', $sempro->id)
            ->where('dosen_id', $dosenId)
            ->get();

        foreach ($parameters as $index => $parameter) {
            if ($nilais[$index]) {
                $nilais[$index]->nilai = null;
                $nilais[$index]->forceDelete();
            }
        }

        if ($sempro->tugas_akhir->dosen_pembimbing_1_id == $dosenId) {
            $sempro->total_nilai_pembimbing_1 = null;
            $sempro->catatan_ta_pembimbing_1 = null;
            $sempro->save();
        } elseif ($sempro->tugas_akhir->dosen_pembimbing_2_id == $dosenId) {
            $sempro->total_nilai_pembimbing_2 = null;
            $sempro->catatan_ta_pembimbing_2 = null;
            $sempro->save();
        } elseif ($sempro->tugas_akhir->dosen_penguji_1_id == $dosenId) {
            $sempro->total_nilai_penguji_1 = null;
            $sempro->catatan_ta_penguji_1 = null;
            $sempro->save();
        } elseif ($sempro->tugas_akhir->dosen_penguji_2_id == $dosenId) {
            $sempro->total_nilai_penguji_2 = null;
            $sempro->catatan_ta_penguji_2 = null;
            $sempro->save();
        }

        if ($sempro->total_nilai_pembimbing_1 !== null && $sempro->total_nilai_pembimbing_2 !== null && $sempro->total_nilai_penguji_1 !== null && $sempro->total_nilai_penguji_2 !== null) {
            $totalNilaiAkhir = round(($sempro->total_nilai_pembimbing_1 + $sempro->total_nilai_pembimbing_2 + $sempro->total_nilai_penguji_1 + $sempro->total_nilai_penguji_2) / 4, 2);
            $sempro->total_nilai_akhir = $totalNilaiAkhir;
            $sempro->save();
        } elseif ($sempro->total_nilai_pembimbing_1 == null && $sempro->total_nilai_pembimbing_2 !== null && $sempro->total_nilai_penguji_1 !== null && $sempro->total_nilai_penguji_2 !== null) {
            $totalNilaiAkhir = ($sempro->total_nilai_pembimbing_2 + $sempro->total_nilai_penguji_1 + $sempro->total_nilai_penguji_2) / 3;
            $sempro->total_nilai_akhir = number_format($totalNilaiAkhir, 2);
            $sempro->save();
        } elseif ($sempro->total_nilai_pembimbing_1 !== null && $sempro->total_nilai_pembimbing_2 == null && $sempro->total_nilai_penguji_1 !== null && $sempro->total_nilai_penguji_2 !== null) {
            $totalNilaiAkhir = ($sempro->total_nilai_pembimbing_1 + $sempro->total_nilai_penguji_1 + $sempro->total_nilai_penguji_2) / 3;
            $sempro->total_nilai_akhir = number_format($totalNilaiAkhir, 2);
            $sempro->save();
        } else {
            $sempro->total_nilai_akhir = null;
            $sempro->save();
        }

        $passingGrade = PassingGrade::where('tahapan_ta', 'Seminar Proposal')->first()->nilai;
        if ($sempro->total_nilai_akhir == null) {
            $sempro->status_kelulusan = 'Menunggu Keputusan';
        } elseif ($sempro->total_nilai_akhir !== null && $sempro->total_nilai_akhir >= $passingGrade) {
            $sempro->status_kelulusan = 'LULUS';
            $sempro->save();
        } elseif ($sempro->total_nilai_akhir !== null && $sempro->total_nilai_akhir < $passingGrade) {
            $sempro->status_kelulusan = 'TIDAK LULUS';
            $sempro->save();
        } else {
            $sempro->status_kelulusan = 'Menunggu Keputusan';
            $sempro->save();
        }

        return redirect()->route('dosen.sempro.riwayat')->with('success', 'Berhasil membatalkan penilaian');
    }

    public function viewRekap()
    {
        $sempros = SeminarSidang::with('mahasiswa', 'tugas_akhir')->where('tahapan_ta', 'Seminar Proposal')->whereIn('status_kelulusan', ['LULUS', 'TIDAK LULUS'])->orderBy('tanggal_pelaksanaan', 'desc')->get();
        return view('dosen.sempro.rekap', compact('sempros'));
    }
}
