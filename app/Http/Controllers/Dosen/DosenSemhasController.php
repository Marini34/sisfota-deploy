<?php

namespace App\Http\Controllers\Dosen;

use Carbon\Carbon;
use App\Models\Dosen;
use App\Models\Penilaian;
use App\Models\TugasAkhir;
use App\Models\PassingGrade;
use Illuminate\Http\Request;
use App\Models\SeminarSidang;
use App\Models\ParameterPenilaian;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DosenSemhasController extends Controller
{
    // public function viewPenilaian()
    // {
    //     $dosenId = Dosen::where('nip_nidk', Auth::user()->nim_nip)->first()->id;
    //     $tugasAkhirIds = TugasAkhir::where('dosen_pembimbing_1_id', $dosenId)->orwhere('dosen_pembimbing_2_id', $dosenId)->orwhere('dosen_penguji_1_id', $dosenId)->orwhere('dosen_penguji_2_id', $dosenId)->get()->pluck('id');
    //     $seminarSidangs = SeminarSidang::whereIn('tugas_akhir_id', $tugasAkhirIds)
    //         ->where('tahapan_ta', 'Seminar Hasil')
    //         ->where('status_pendaftaran', 'Diterima')
    //         ->whereDoesntHave('penilaians', function ($query) use ($dosenId) {
    //             $query->where('dosen_id', $dosenId);
    //         })->whereDate('tanggal_pelaksanaan', '>=', Carbon::now()->subDays(7))
    //         ->get();
    //     return view('dosen.semhas.index', compact('seminarSidangs', 'dosenId'));
    // }

    private function getDataForViewPenilaian($userId)
    {
        $dosen = Dosen::where('nip_nidk', $userId)->first();
        $dosenId = $dosen ? $dosen->id : null;

        if (!$dosenId) {
            return ['dosenId' => null, 'seminarSidangs' => collect()];
        }

        $tugasAkhirIds = TugasAkhir::where('dosen_pembimbing_1_id', $dosenId)->orWhere('dosen_pembimbing_2_id', $dosenId)->orWhere('dosen_penguji_1_id', $dosenId)->orWhere('dosen_penguji_2_id', $dosenId)->get()->pluck('id');

        $seminarSidangs = SeminarSidang::whereIn('tugas_akhir_id', $tugasAkhirIds)
            ->where('tahapan_ta', 'Seminar Hasil')
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

        return view('dosen.semhas.index', $data);
    }

    public function viewPenilaianDetail($slug)
    {
        $dosenId = Dosen::where('nip_nidk', Auth::user()->nim_nip)->first()->id;
        $tugasAkhirIds = TugasAkhir::where('dosen_pembimbing_1_id', $dosenId)->orwhere('dosen_pembimbing_2_id', $dosenId)->orwhere('dosen_penguji_1_id', $dosenId)->orwhere('dosen_penguji_2_id', $dosenId)->get()->pluck('id');
        $semhas = SeminarSidang::where('slug', $slug)->first();
        return view('dosen.semhas.detail', compact('semhas', 'dosenId'));
    }

    public function viewFormPenilaian($slug)
    {
        $semhas = SeminarSidang::where('slug', $slug)->first();
        $parameters = ParameterPenilaian::where('tahapan_ta', 'Seminar Hasil')->get();
        if ($parameters->count()) {
            $totalPersentase = $parameters->sum('persentase');
        } else {
            $totalPersentase = 0;
        }
        return view('dosen.semhas.nilai', compact('semhas', 'parameters', 'totalPersentase'));
    }

    public function storePenilaianSemhas(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
            'nilai.*' => 'required|numeric|min:0|max:100',
            'catatan' => 'nullable|max:255',
        ]);

        $semhas = SeminarSidang::where('slug', $slug)->first();
        $parameters = ParameterPenilaian::where('tahapan_ta', 'Seminar Hasil')->get();
        $dosenId = Dosen::where('nip_nidk', Auth::user()->nim_nip)->first()->id;



        Log::channel('slack')->info(Auth::user()->name.' Melakukan penilaian semhas!');


        $existingPenilaian = Penilaian::where('seminar_sidang_id', $semhas->id)->where('dosen_id', $dosenId)->exists();

        if ($existingPenilaian) {
            return redirect(route('dosen.semhas.penilaian'))->with('fail', 'Penilaian sebelumnya sudah berhasil, edit untuk mengubah nilai');
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('fail', 'Gagal melakukan penilaian seminar hasil');
        } else {
            foreach ($parameters as $index => $parameter) {
                Penilaian::create([
                    'seminar_sidang_id' => $semhas->id,
                    'dosen_id' => $dosenId,
                    'mahasiswa_id' => $semhas->mahasiswa->id,
                    'parameter_penilaian_id' => $parameter->id,
                    'nilai' => $request->nilai[$index],
                ]);
            }

            # jika dosen id = 6
            if ($dosenId ==2) {
                // buat duplikasi
                if ($semhas->tugas_akhir->dosen_pembimbing_1_id == 6 || $semhas->tugas_akhir->dosen_pembimbing_2_id == 6 || $semhas->tugas_akhir->dosen_penguji_1_id  == 6 || $semhas->tugas_akhir->dosen_penguji_2_id == 6) {
                   $this->newStoreEditNilai($request,$slug,6);
                }
            }

            $perhitungans = Penilaian::where('seminar_sidang_id', $semhas->id)
                ->where('dosen_id', $dosenId)
                ->get();
            $totalNilai = 0;

            foreach ($perhitungans as $index => $perhitungan) {
                $nilais = $perhitungan->nilai;
                $parameter = $perhitungan->parameter_penilaian->persentase / 100;
                $nilai[$index] = $nilais * $parameter;
                $totalNilai += $nilai[$index];
            }

            if ($semhas->tugas_akhir->dosen_pembimbing_1_id == $dosenId) {
                $semhas->total_nilai_pembimbing_1 = $totalNilai;
                if ($request->catatan != null) {
                    $semhas->catatan_ta_pembimbing_1 = $request->catatan;
                }
                $semhas->save();
            } elseif ($semhas->tugas_akhir->dosen_pembimbing_2_id == $dosenId) {
                $semhas->total_nilai_pembimbing_2 = $totalNilai;
                if ($request->catatan != null) {
                    $semhas->catatan_ta_pembimbing_2 = $request->catatan;
                }
                $semhas->save();
            } elseif ($semhas->tugas_akhir->dosen_penguji_1_id == $dosenId) {
                $semhas->total_nilai_penguji_1 = $totalNilai;
                if ($request->catatan != null) {
                    $semhas->catatan_ta_penguji_1 = $request->catatan;
                }
                $semhas->save();
            } elseif ($semhas->tugas_akhir->dosen_penguji_2_id == $dosenId) {
                $semhas->total_nilai_penguji_2 = $totalNilai;
                if ($request->catatan != null) {
                    $semhas->catatan_ta_penguji_2 = $request->catatan;
                }
                $semhas->save();
            }

            if ($semhas->total_nilai_pembimbing_1 !== null && $semhas->total_nilai_pembimbing_2 !== null && $semhas->total_nilai_penguji_1 !== null && $semhas->total_nilai_penguji_2 !== null) {
                $totalNilaiAkhir = round(($semhas->total_nilai_pembimbing_1 + $semhas->total_nilai_pembimbing_2 + $semhas->total_nilai_penguji_1 + $semhas->total_nilai_penguji_2) / 4, 2);
                $semhas->total_nilai_akhir = $totalNilaiAkhir;
                $semhas->save();
            } elseif ($semhas->total_nilai_pembimbing_1 == null && $semhas->total_nilai_pembimbing_2 !== null && $semhas->total_nilai_penguji_1 !== null && $semhas->total_nilai_penguji_2 !== null) {
                $totalNilaiAkhir = ($semhas->total_nilai_pembimbing_2 + $semhas->total_nilai_penguji_1 + $semhas->total_nilai_penguji_2) / 3;
                $semhas->total_nilai_akhir = number_format($totalNilaiAkhir, 2);
                $semhas->save();
            } elseif ($semhas->total_nilai_pembimbing_1 !== null && $semhas->total_nilai_pembimbing_2 == null && $semhas->total_nilai_penguji_1 !== null && $semhas->total_nilai_penguji_2 !== null) {
                $totalNilaiAkhir = ($semhas->total_nilai_pembimbing_1 + $semhas->total_nilai_penguji_1 + $semhas->total_nilai_penguji_2) / 3;
                $semhas->total_nilai_akhir = number_format($totalNilaiAkhir, 2);
                $semhas->save();
            } else {
                $semhas->total_nilai_akhir = null;
                $semhas->save();
            }

            $passingGrade = PassingGrade::where('tahapan_ta', 'Seminar Hasil')->first()->nilai;
            if ($semhas->total_nilai_akhir !== null && $semhas->total_nilai_akhir >= $passingGrade) {
                $semhas->status_kelulusan = 'LULUS';
                $semhas->save();
            } elseif ($semhas->total_nilai_akhir !== null && $semhas->total_nilai_akhir < $passingGrade) {
                $semhas->status_kelulusan = 'TIDAK LULUS';
                $semhas->save();
            } else {
                $semhas->status_kelulusan = 'Menunggu Keputusan';
                $semhas->save();
            }

            return redirect()->route('dosen.semhas.penilaian')->with('success', 'Berhasil melakukan penilaian Seminar Hasil');
        }
    }

    // public function viewRiwayat()
    // {
    //     $dosenId = Dosen::where('nip_nidk', Auth::user()->nim_nip)->first()->id;
    //     $tugasAkhirIds = TugasAkhir::where('dosen_pembimbing_1_id', $dosenId)->orwhere('dosen_pembimbing_2_id', $dosenId)->orwhere('dosen_penguji_1_id', $dosenId)->orwhere('dosen_penguji_2_id', $dosenId)->get()->pluck('id');
    //     $seminarSidangs = SeminarSidang::whereIn('tugas_akhir_id', $tugasAkhirIds)
    //         ->where('tahapan_ta', 'Seminar Hasil')
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
    //     $semhases = SeminarSidang::whereIn('tugas_akhir_id', $tugasAkhirIds)->where('tahapan_ta', 'Sidang Akhir')->where('status_pendaftaran', 'Diterima')->get();
    //     $semhasIds = $semhases->pluck('tugas_akhir_id')->toArray();
    //     return view('dosen.semhas.riwayat', compact('seminarSidangs', 'dosenId', 'semhasIds'));
    // }

    private function getDataForViewRiwayat($userId, $tahapan1, $tahapan2)
    {
        $dosen = Dosen::where('nip_nidk', $userId)->first();
        $dosenId = $dosen ? $dosen->id : null;

        if (!$dosenId) {
            return ['dosenId' => null, 'seminarSidangs' => collect(), 'semhasIds' => []];
        }

        $tugasAkhirIds = TugasAkhir::where('dosen_pembimbing_1_id', $dosenId)->orWhere('dosen_pembimbing_2_id', $dosenId)->orWhere('dosen_penguji_1_id', $dosenId)->orWhere('dosen_penguji_2_id', $dosenId)->get()->pluck('id');

        $seminarSidangs = SeminarSidang::whereIn('tugas_akhir_id', $tugasAkhirIds)
            ->where('tahapan_ta', $tahapan1)
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

        $semhases = SeminarSidang::whereIn('tugas_akhir_id', $tugasAkhirIds)->where('tahapan_ta', $tahapan2)->where('status_pendaftaran', 'Diterima')->get();
        $semhasIds = $semhases->pluck('tugas_akhir_id')->toArray();

        return compact('dosenId', 'seminarSidangs', 'semhasIds');
    }

    public function viewRiwayat()
    {
        $data = $this->getDataForViewRiwayat(Auth::user()->nim_nip, 'Seminar Hasil', 'Sidang Akhir');

        if (!$data['dosenId']) {
            // Handle case where dosen is not found or return an appropriate view/message
            return redirect()->back()->withErrors('Dosen not found or unauthorized.');
        }

        return view('dosen.semhas.riwayat', $data);
    }

    public function viewEditNilai($slug)
    {
        $semhas = SeminarSidang::where('slug', $slug)->first();
        $dosenId = Dosen::where('nip_nidk', Auth::user()->nim_nip)->first()->id;
        $nilais = Penilaian::where('seminar_sidang_id', $semhas->id)
            ->where('dosen_id', $dosenId)
            ->get();
        $parameters = ParameterPenilaian::where('tahapan_ta', 'Seminar Hasil')->get();
        $sidang = SeminarSidang::where('tugas_akhir_id', $semhas->tugas_akhir_id)
            ->where('tahapan_ta', 'Sidang Akhir')
            ->where('status_pendaftaran', 'Diterima')
            ->orderBy('created_at', 'desc')
            ->first();
        if ($parameters->count()) {
            $totalPersentase = $parameters->sum('persentase');
        } else {
            $totalPersentase = 0;
        }
        return view('dosen.semhas.edit', compact('semhas', 'nilais', 'parameters', 'totalPersentase', 'dosenId', 'sidang'));
    }

    public function storeEditNilai(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
            'nilai.*' => 'required|numeric|min:0|max:100',
            'catatan' => 'nullable|max:255',
        ]);

        $semhas = SeminarSidang::where('slug', $slug)->first();
        $dosenId = Dosen::where('nip_nidk', Auth::user()->nim_nip)->first()->id;
        $parameters = ParameterPenilaian::where('tahapan_ta', 'Seminar Hasil')->get();
        $nilais = Penilaian::where('seminar_sidang_id', $semhas->id)
            ->where('dosen_id', $dosenId)
            ->get();
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('fail', 'Gagal melakukan penilaian seminar hasil');
        } else {
            foreach ($parameters as $index => $parameter) {
                if (isset($nilais[$index])) {
                    $nilais[$index]->nilai = $request->nilai[$index];
                    $nilais[$index]->save();
                } else {
                    Penilaian::create([
                        'seminar_sidang_id' => $semhas->id,
                        'dosen_id' => $dosenId,
                        'mahasiswa_id' => $semhas->mahasiswa->id,
                        'parameter_penilaian_id' => $parameter->id,
                        'nilai' => $request->nilai[$index],
                    ]);
                }
            }

            $perhitungans = Penilaian::where('seminar_sidang_id', $semhas->id)
                ->where('dosen_id', $dosenId)
                ->get();
            $totalNilai = 0;

            foreach ($perhitungans as $index => $perhitungan) {
                $nilais = $perhitungan->nilai;
                $parameter = $perhitungan->parameter_penilaian->persentase / 100;
                $nilai[$index] = $nilais * $parameter;
                $totalNilai += $nilai[$index];
            }

            if ($semhas->tugas_akhir->dosen_pembimbing_1_id == $dosenId) {
                $semhas->total_nilai_pembimbing_1 = $totalNilai;
                if ($request->catatan != null) {
                    $semhas->catatan_ta_pembimbing_1 = $request->catatan;
                }
                $semhas->save();
            } elseif ($semhas->tugas_akhir->dosen_pembimbing_2_id == $dosenId) {
                $semhas->total_nilai_pembimbing_2 = $totalNilai;
                if ($request->catatan != null) {
                    $semhas->catatan_ta_pembimbing_2 = $request->catatan;
                }
                $semhas->save();
            } elseif ($semhas->tugas_akhir->dosen_penguji_1_id == $dosenId) {
                $semhas->total_nilai_penguji_1 = $totalNilai;
                if ($request->catatan != null) {
                    $semhas->catatan_ta_penguji_1 = $request->catatan;
                }
                $semhas->save();
            } elseif ($semhas->tugas_akhir->dosen_penguji_2_id == $dosenId) {
                $semhas->total_nilai_penguji_2 = $totalNilai;
                if ($request->catatan != null) {
                    $semhas->catatan_ta_penguji_2 = $request->catatan;
                }
                $semhas->save();
            }

            if ($semhas->total_nilai_pembimbing_1 !== null && $semhas->total_nilai_pembimbing_2 !== null && $semhas->total_nilai_penguji_1 !== null && $semhas->total_nilai_penguji_2 !== null) {
                $totalNilaiAkhir = round(($semhas->total_nilai_pembimbing_1 + $semhas->total_nilai_pembimbing_2 + $semhas->total_nilai_penguji_1 + $semhas->total_nilai_penguji_2) / 4, 2);
                $semhas->total_nilai_akhir = $totalNilaiAkhir;
                $semhas->save();
            } elseif ($semhas->total_nilai_pembimbing_1 == null && $semhas->total_nilai_pembimbing_2 !== null && $semhas->total_nilai_penguji_1 !== null && $semhas->total_nilai_penguji_2 !== null) {
                $totalNilaiAkhir = ($semhas->total_nilai_pembimbing_2 + $semhas->total_nilai_penguji_1 + $semhas->total_nilai_penguji_2) / 3;
                $semhas->total_nilai_akhir = number_format($totalNilaiAkhir, 2);
                $semhas->save();
            } elseif ($semhas->total_nilai_pembimbing_1 !== null && $semhas->total_nilai_pembimbing_2 == null && $semhas->total_nilai_penguji_1 !== null && $semhas->total_nilai_penguji_2 !== null) {
                $totalNilaiAkhir = ($semhas->total_nilai_pembimbing_1 + $semhas->total_nilai_penguji_1 + $semhas->total_nilai_penguji_2) / 3;
                $semhas->total_nilai_akhir = number_format($totalNilaiAkhir, 2);
                $semhas->save();
            } else {
                $semhas->total_nilai_akhir = null;
                $semhas->save();
            }

            $passingGrade = PassingGrade::where('tahapan_ta', 'Seminar Hasil')->first()->nilai;
            if ($semhas->total_nilai_akhir !== null && $semhas->total_nilai_akhir >= $passingGrade) {
                $semhas->status_kelulusan = 'LULUS';
                $semhas->save();
            } elseif ($semhas->total_nilai_akhir !== null && $semhas->total_nilai_akhir < $passingGrade) {
                $semhas->status_kelulusan = 'TIDAK LULUS';
                $semhas->save();
            } else {
                $semhas->status_kelulusan = 'Menunggu Keputusan';
                $semhas->save();
            }

            return redirect()->route('dosen.semhas.riwayat')->with('success', 'Berhasil mengubah penilaian Seminar Hasil');
        }
    }

    public function newStoreEditNilai(Request $request, $slug, $dosenId)
    {
        $validator = Validator::make($request->all(), [
            'nilai.*' => 'required|numeric|min:0|max:100',
            'catatan' => 'nullable|max:255',
        ]);

        $semhas = SeminarSidang::where('slug', $slug)->first();
        $parameters = ParameterPenilaian::where('tahapan_ta', 'Seminar Hasil')->get();
        $nilais = Penilaian::where('seminar_sidang_id', $semhas->id)
            ->where('dosen_id', $dosenId)
            ->get();
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('fail', 'Gagal melakukan penilaian seminar hasil');
        } else {
            foreach ($parameters as $index => $parameter) {
                if (isset($nilais[$index])) {
                    $nilais[$index]->nilai = $request->nilai[$index];
                    $nilais[$index]->save();
                } else {
                    Penilaian::create([
                        'seminar_sidang_id' => $semhas->id,
                        'dosen_id' => $dosenId,
                        'mahasiswa_id' => $semhas->mahasiswa->id,
                        'parameter_penilaian_id' => $parameter->id,
                        'nilai' => $request->nilai[$index],
                    ]);
                }
            }

            $perhitungans = Penilaian::where('seminar_sidang_id', $semhas->id)
                ->where('dosen_id', $dosenId)
                ->get();
            $totalNilai = 0;

            foreach ($perhitungans as $index => $perhitungan) {
                $nilais = $perhitungan->nilai;
                $parameter = $perhitungan->parameter_penilaian->persentase / 100;
                $nilai[$index] = $nilais * $parameter;
                $totalNilai += $nilai[$index];
            }

            if ($semhas->tugas_akhir->dosen_pembimbing_1_id == $dosenId) {
                $semhas->total_nilai_pembimbing_1 = $totalNilai;
                if ($request->catatan != null) {
                    $semhas->catatan_ta_pembimbing_1 = $request->catatan;
                }
                $semhas->save();
            } elseif ($semhas->tugas_akhir->dosen_pembimbing_2_id == $dosenId) {
                $semhas->total_nilai_pembimbing_2 = $totalNilai;
                if ($request->catatan != null) {
                    $semhas->catatan_ta_pembimbing_2 = $request->catatan;
                }
                $semhas->save();
            } elseif ($semhas->tugas_akhir->dosen_penguji_1_id == $dosenId) {
                $semhas->total_nilai_penguji_1 = $totalNilai;
                if ($request->catatan != null) {
                    $semhas->catatan_ta_penguji_1 = $request->catatan;
                }
                $semhas->save();
            } elseif ($semhas->tugas_akhir->dosen_penguji_2_id == $dosenId) {
                $semhas->total_nilai_penguji_2 = $totalNilai;
                if ($request->catatan != null) {
                    $semhas->catatan_ta_penguji_2 = $request->catatan;
                }
                $semhas->save();
            }

            if ($semhas->total_nilai_pembimbing_1 !== null && $semhas->total_nilai_pembimbing_2 !== null && $semhas->total_nilai_penguji_1 !== null && $semhas->total_nilai_penguji_2 !== null) {
                $totalNilaiAkhir = round(($semhas->total_nilai_pembimbing_1 + $semhas->total_nilai_pembimbing_2 + $semhas->total_nilai_penguji_1 + $semhas->total_nilai_penguji_2) / 4, 2);
                $semhas->total_nilai_akhir = $totalNilaiAkhir;
                $semhas->save();
            } elseif ($semhas->total_nilai_pembimbing_1 == null && $semhas->total_nilai_pembimbing_2 !== null && $semhas->total_nilai_penguji_1 !== null && $semhas->total_nilai_penguji_2 !== null) {
                $totalNilaiAkhir = ($semhas->total_nilai_pembimbing_2 + $semhas->total_nilai_penguji_1 + $semhas->total_nilai_penguji_2) / 3;
                $semhas->total_nilai_akhir = number_format($totalNilaiAkhir, 2);
                $semhas->save();
            } elseif ($semhas->total_nilai_pembimbing_1 !== null && $semhas->total_nilai_pembimbing_2 == null && $semhas->total_nilai_penguji_1 !== null && $semhas->total_nilai_penguji_2 !== null) {
                $totalNilaiAkhir = ($semhas->total_nilai_pembimbing_1 + $semhas->total_nilai_penguji_1 + $semhas->total_nilai_penguji_2) / 3;
                $semhas->total_nilai_akhir = number_format($totalNilaiAkhir, 2);
                $semhas->save();
            } else {
                $semhas->total_nilai_akhir = null;
                $semhas->save();
            }

            $passingGrade = PassingGrade::where('tahapan_ta', 'Seminar Hasil')->first()->nilai;
            if ($semhas->total_nilai_akhir !== null && $semhas->total_nilai_akhir >= $passingGrade) {
                $semhas->status_kelulusan = 'LULUS';
                $semhas->save();
            } elseif ($semhas->total_nilai_akhir !== null && $semhas->total_nilai_akhir < $passingGrade) {
                $semhas->status_kelulusan = 'TIDAK LULUS';
                $semhas->save();
            } else {
                $semhas->status_kelulusan = 'Menunggu Keputusan';
                $semhas->save();
            }

            return redirect()->route('dosen.semhas.riwayat')->with('success', 'Berhasil mengubah penilaian Seminar Hasil');
        }
    }

    public function batalkanPenilaian($slug)
    {
        $semhas = SeminarSidang::where('slug', $slug)->first();
        $parameters = ParameterPenilaian::where('tahapan_ta', 'Seminar Hasil')->get();
        $dosenId = Dosen::where('nip_nidk', Auth::user()->nim_nip)->first()->id;
        $nilais = Penilaian::where('seminar_sidang_id', $semhas->id)
            ->where('dosen_id', $dosenId)
            ->get();
        foreach ($parameters as $index => $parameter) {
            if ($nilais) {
                $nilais[$index]->nilai = null;
                $nilais[$index]->forceDelete();
            }
        }

        if ($semhas->tugas_akhir->dosen_pembimbing_1_id == $dosenId) {
            $semhas->total_nilai_pembimbing_1 = null;
            $semhas->catatan_ta_pembimbing_1 = null;
            $semhas->save();
        } elseif ($semhas->tugas_akhir->dosen_pembimbing_2_id == $dosenId) {
            $semhas->total_nilai_pembimbing_2 = null;
            $semhas->catatan_ta_pembimbing_2 = null;
            $semhas->save();
        } elseif ($semhas->tugas_akhir->dosen_penguji_1_id == $dosenId) {
            $semhas->total_nilai_penguji_1 = null;
            $semhas->catatan_ta_penguji_1 = null;
            $semhas->save();
        } elseif ($semhas->tugas_akhir->dosen_penguji_2_id == $dosenId) {
            $semhas->total_nilai_penguji_2 = null;
            $semhas->catatan_ta_penguji_2 = null;
            $semhas->save();
        }

        if ($semhas->total_nilai_pembimbing_1 !== null && $semhas->total_nilai_pembimbing_2 !== null && $semhas->total_nilai_penguji_1 !== null && $semhas->total_nilai_penguji_2 !== null) {
            $totalNilaiAkhir = round(($semhas->total_nilai_pembimbing_1 + $semhas->total_nilai_pembimbing_2 + $semhas->total_nilai_penguji_1 + $semhas->total_nilai_penguji_2) / 4, 2);
            $semhas->total_nilai_akhir = $totalNilaiAkhir;
            $semhas->save();
        } elseif ($semhas->total_nilai_pembimbing_1 == null && $semhas->total_nilai_pembimbing_2 !== null && $semhas->total_nilai_penguji_1 !== null && $semhas->total_nilai_penguji_2 !== null) {
            $totalNilaiAkhir = ($semhas->total_nilai_pembimbing_2 + $semhas->total_nilai_penguji_1 + $semhas->total_nilai_penguji_2) / 3;
            $semhas->total_nilai_akhir = number_format($totalNilaiAkhir, 2);
            $semhas->save();
        } elseif ($semhas->total_nilai_pembimbing_1 !== null && $semhas->total_nilai_pembimbing_2 == null && $semhas->total_nilai_penguji_1 !== null && $semhas->total_nilai_penguji_2 !== null) {
            $totalNilaiAkhir = ($semhas->total_nilai_pembimbing_1 + $semhas->total_nilai_penguji_1 + $semhas->total_nilai_penguji_2) / 3;
            $semhas->total_nilai_akhir = number_format($totalNilaiAkhir, 2);
            $semhas->save();
        } else {
            $semhas->total_nilai_akhir = null;
            $semhas->save();
        }

        $passingGrade = PassingGrade::where('tahapan_ta', 'Seminar Hasil')->first()->nilai;
        if ($semhas->total_nilai_akhir == null) {
            $semhas->status_kelulusan = 'Menunggu Keputusan';
        } elseif ($semhas->total_nilai_akhir !== null && $semhas->total_nilai_akhir >= $passingGrade) {
            $semhas->status_kelulusan = 'LULUS';
            $semhas->save();
        } elseif ($semhas->total_nilai_akhir !== null && $semhas->total_nilai_akhir < $passingGrade) {
            $semhas->status_kelulusan = 'TIDAK LULUS';
            $semhas->save();
        } else {
            $semhas->status_kelulusan = 'Menunggu Keputusan';
            $semhas->save();
        }

        return redirect()->route('dosen.semhas.riwayat')->with('success', 'Berhasil membatalkan penilaian');
    }
}
