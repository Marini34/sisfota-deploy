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

class DosenSidangController extends Controller
{
    public function viewPenilaian()
    {
        $dosenId = Dosen::where('nip_nidk', Auth::user()->nim_nip)->first()->id;
        $tugasAkhirIds = TugasAkhir::where('dosen_pembimbing_1_id', $dosenId)->orwhere('dosen_pembimbing_2_id', $dosenId)->orwhere('dosen_penguji_1_id', $dosenId)->orwhere('dosen_penguji_2_id', $dosenId)->get()->pluck('id');
        $seminarSidangs = SeminarSidang::whereIn('tugas_akhir_id', $tugasAkhirIds)
            ->where('tahapan_ta', 'Sidang Akhir')
            ->where('status_pendaftaran', 'Diterima')
            ->whereDoesntHave('penilaians', function ($query) use ($dosenId) {
                $query->where('dosen_id', $dosenId);
            })
            // ->whereDate('tanggal_pelaksanaan', '>=', Carbon::now()->subDays(7))
            ->orderBy('tanggal_pelaksanaan', 'asc')
            ->get();
        return view('dosen.sidang.index', compact('seminarSidangs', 'dosenId'));
    }

    public function viewPenilaianDetail($slug)
    {
        $dosenId = Dosen::where('nip_nidk', Auth::user()->nim_nip)->first()->id;
        $sidang = SeminarSidang::where('slug', $slug)->first();
        return view('dosen.sidang.detail', compact('sidang', 'dosenId'));
    }

    public function publishNilai($slug, $status = true)
    {
        $dosenId = Dosen::where('nip_nidk', Auth::user()->nim_nip)->first()->id;
        $sidang = SeminarSidang::where('slug', $slug)->first();
        $sidang->is_publish = $status;
        $sidang->save();
        return redirect()->route('dosen.sidang.penilaian.detail', ['slug' => $slug]);
    }

    public function viewFormPenilaian($slug)
    {
        $sidang = SeminarSidang::where('slug', $slug)->first();
        $skripsiParameters = ParameterPenilaian::where('tahapan_ta', 'Skripsi')->get();
        $artikelParameters = ParameterPenilaian::where('tahapan_ta', 'Artikel')->get();
        $presentasiParameters = ParameterPenilaian::where('tahapan_ta', 'Presentasi')->get();

        $totalPersentaseSkripsi = $skripsiParameters->count() ? $skripsiParameters->sum('persentase') : 0;
        $totalPersentaseArtikel = $artikelParameters->count() ? $artikelParameters->sum('persentase') : 0;
        $totalPersentasePresentasi = $presentasiParameters->count() ? $presentasiParameters->sum('persentase') : 0;

        return view('dosen.sidang.nilai', compact('sidang', 'skripsiParameters', 'artikelParameters', 'presentasiParameters', 'totalPersentaseSkripsi', 'totalPersentaseArtikel', 'totalPersentasePresentasi'));
    }

    public function storePenilaianSidang(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
            'nilaiSkripsi.*' => 'required|numeric|min:0|max:100',
            'nilaiArtikel.*' => 'required|numeric|min:0|max:100',
            'nilaiPresentasi.*' => 'required|numeric|min:0|max:100',
            'totalNilaiKeseluruhan' => 'required|numeric|min:0|max:100',
            'catatan' => 'nullable|max:500',
        ]);

        $sidang = SeminarSidang::where('slug', $slug)->first();
        $skripsiParameters = ParameterPenilaian::where('tahapan_ta', 'Skripsi')->get();
        $artikelParameters = ParameterPenilaian::where('tahapan_ta', 'Artikel')->get();
        $presentasiParameters = ParameterPenilaian::where('tahapan_ta', 'Presentasi')->get();
        $dosenId = Dosen::where('nip_nidk', Auth::user()->nim_nip)->first()->id;
        Log::channel('slack')->info(Auth::user()->name.' Melakukan penilaian sidang!');


        $existingPenilaian = Penilaian::where('seminar_sidang_id', $sidang->id)->where('dosen_id', $dosenId)->exists();

        if ($existingPenilaian) {
            return redirect(route('dosen.sidang.penilaian'))->with('fail', 'Penilaian sebelumnya sudah berhasil, edit untuk mengubah nilai');
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('fail', 'Gagal melakukan penilaian sidang akhir');
        } else {
            foreach ($skripsiParameters as $index => $skripsiParameter) {
                Penilaian::create([
                    'seminar_sidang_id' => $sidang->id,
                    'dosen_id' => $dosenId,
                    'mahasiswa_id' => $sidang->mahasiswa->id,
                    'parameter_penilaian_id' => $skripsiParameter->id,
                    'nilai' => $request->nilaiSkripsi[$index],
                ]);
            }

            foreach ($artikelParameters as $index => $artikelParameter) {
                Penilaian::create([
                    'seminar_sidang_id' => $sidang->id,
                    'dosen_id' => $dosenId,
                    'mahasiswa_id' => $sidang->mahasiswa->id,
                    'parameter_penilaian_id' => $artikelParameter->id,
                    'nilai' => $request->nilaiArtikel[$index],
                ]);
            }

            foreach ($presentasiParameters as $index => $presentasiParameter) {
                Penilaian::create([
                    'seminar_sidang_id' => $sidang->id,
                    'dosen_id' => $dosenId,
                    'mahasiswa_id' => $sidang->mahasiswa->id,
                    'parameter_penilaian_id' => $presentasiParameter->id,
                    'nilai' => $request->nilaiPresentasi[$index],
                ]);
            }

            // $perhitungans = Penilaian::where('seminar_sidang_id', $sidang->id)->get();
            // $perhitunganSkripsi = Penilaian::whereIn('parameter_penilaian_id', $skripsiParameters->pluck('id'))->where('seminar_sidang_id', $sidang->id)->get();
            // $totalNilaiSkripsi = 0;

            // $perhitunganArtikel = Penilaian::whereIn('parameter_penilaian_id', $artikelParameters->pluck('id'))->where('seminar_sidang_id', $sidang->id)->get();
            // $totalNilaiArtikel = 0;

            // $perhitunganPresentasi = Penilaian::whereIn('parameter_penilaian_id', $presentasiParameters->pluck('id'))->where('seminar_sidang_id', $sidang->id)->get();;
            // $totalNilaiPresentasi = 0;

            // foreach ($perhitunganSkripsi as $index => $perhitunganSkripsi) {
            //     $nilais = $perhitunganSkripsi->nilai;
            //     $parameter = $perhitunganSkripsi->parameter_penilaian->persentase / 100;
            //     $nilai[$index] = $nilais * $parameter;
            //     $totalNilaiSkripsi += $nilai[$index];
            // }

            // foreach ($perhitunganArtikel as $index => $perhitunganArtikel) {
            //     $nilais = $perhitunganArtikel->nilai;
            //     $parameter = $perhitunganArtikel->parameter_penilaian->persentase / 100;
            //     $nilai[$index] = $nilais * $parameter;
            //     $totalNilaiArtikel += $nilai[$index];
            // }

            // foreach ($perhitunganPresentasi as $index => $perhitunganPresentasi) {
            //     $nilais = $perhitunganPresentasi->nilai;
            //     $parameter = $perhitunganPresentasi->parameter_penilaian->persentase / 100;
            //     $nilai[$index] = $nilais * $parameter;
            //     $totalNilaiPresentasi += $nilai[$index];
            // }

            // $totalNilai = ($totalNilaiSkripsi + $totalNilaiArtikel + $totalNilaiPresentasi) / 3;

            $totalNilai = $request->totalNilaiKeseluruhan;

            if ($sidang->tugas_akhir->dosen_pembimbing_1_id == $dosenId) {
                $sidang->total_nilai_pembimbing_1 = $totalNilai;
                if ($request->catatan != null) {
                    $sidang->catatan_ta_pembimbing_1 = $request->catatan;
                }
                $sidang->save();
            } elseif ($sidang->tugas_akhir->dosen_pembimbing_2_id == $dosenId) {
                $sidang->total_nilai_pembimbing_2 = $totalNilai;
                if ($request->catatan != null) {
                    $sidang->catatan_ta_pembimbing_2 = $request->catatan;
                }
                $sidang->save();
            } elseif ($sidang->tugas_akhir->dosen_penguji_1_id == $dosenId) {
                $sidang->total_nilai_penguji_1 = $totalNilai;
                if ($request->catatan != null) {
                    $sidang->catatan_ta_penguji_1 = $request->catatan;
                }
                $sidang->save();
            } elseif ($sidang->tugas_akhir->dosen_penguji_2_id == $dosenId) {
                $sidang->total_nilai_penguji_2 = $totalNilai;
                if ($request->catatan != null) {
                    $sidang->catatan_ta_penguji_2 = $request->catatan;
                }
                $sidang->save();
            }

            if ($sidang->total_nilai_pembimbing_1 !== null && $sidang->total_nilai_pembimbing_2 !== null && $sidang->total_nilai_penguji_1 !== null && $sidang->total_nilai_penguji_2 !== null) {
                $totalNilaiAkhir = round(($sidang->total_nilai_pembimbing_1 + $sidang->total_nilai_pembimbing_2 + $sidang->total_nilai_penguji_1 + $sidang->total_nilai_penguji_2) / 4, 2);
                $sidang->total_nilai_akhir = $totalNilaiAkhir;
                $sidang->save();
            } elseif ($sidang->total_nilai_pembimbing_1 == null && $sidang->total_nilai_pembimbing_2 !== null && $sidang->total_nilai_penguji_1 !== null && $sidang->total_nilai_penguji_2 !== null) {
                $totalNilaiAkhir = ($sidang->total_nilai_pembimbing_2 + $sidang->total_nilai_penguji_1 + $sidang->total_nilai_penguji_2) / 3;
                $sidang->total_nilai_akhir = number_format($totalNilaiAkhir, 2);
                $sidang->save();
            } elseif ($sidang->total_nilai_pembimbing_1 !== null && $sidang->total_nilai_pembimbing_2 == null && $sidang->total_nilai_penguji_1 !== null && $sidang->total_nilai_penguji_2 !== null) {
                $totalNilaiAkhir = ($sidang->total_nilai_pembimbing_1 + $sidang->total_nilai_penguji_1 + $sidang->total_nilai_penguji_2) / 3;
                $sidang->total_nilai_akhir = number_format($totalNilaiAkhir, 2);
                $sidang->save();
            } else {
                $sidang->total_nilai_akhir = null;
                $sidang->save();
            }

            $passingGrade = PassingGrade::where('tahapan_ta', 'Sidang Akhir')->first()->nilai;
            if ($sidang->total_nilai_akhir !== null && $sidang->total_nilai_akhir >= $passingGrade) {
                $sidang->status_kelulusan = 'LULUS';
                $sidang->save();
            } elseif ($sidang->total_nilai_akhir !== null && $sidang->total_nilai_akhir < $passingGrade) {
                $sidang->status_kelulusan = 'TIDAK LULUS';
                $sidang->save();
            } else {
                $sidang->status_kelulusan = 'Menunggu Keputusan';
                $sidang->save();
            }

            return redirect()->route('dosen.sidang.penilaian')->with('success', 'Berhasil melakukan penilaian Sidang Akhir');
        }
    }

    public function viewRiwayat()
    {
        $dosenId = Dosen::where('nip_nidk', Auth::user()->nim_nip)->first()->id;
        $tugasAkhirIds = TugasAkhir::where('dosen_pembimbing_1_id', $dosenId)->orwhere('dosen_pembimbing_2_id', $dosenId)->orwhere('dosen_penguji_1_id', $dosenId)->orwhere('dosen_penguji_2_id', $dosenId)->get()->pluck('id');
        $seminarSidangs = SeminarSidang::whereIn('tugas_akhir_id', $tugasAkhirIds)
            ->where('tahapan_ta', 'Sidang Akhir')
            ->where('status_pendaftaran', 'Diterima')
            ->where(function ($query) use ($dosenId) {
                $query->whereHas('penilaians', function ($subQuery) use ($dosenId) {
                    $subQuery->where('dosen_id', $dosenId);
                })
                ->orWhere(function ($subQuery) use ($dosenId) {
                    $subQuery->whereDoesntHave('penilaians', function ($nestedQuery) use ($dosenId) {
                        $nestedQuery->where('dosen_id', $dosenId);
                    })
                    ->whereDate('tanggal_pelaksanaan', '<', Carbon::now()->subDays(7));
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();
        $semhases = SeminarSidang::whereIn('tugas_akhir_id', $tugasAkhirIds)->where('tahapan_ta', 'Seminar Hasil')->where('status_pendaftaran', 'Diterima')->get();
        $semhasIds = $semhases->pluck('tugas_akhir_id')->toArray();
        return view('dosen.sidang.riwayat', compact('seminarSidangs', 'dosenId', 'semhasIds'));
    }

    public function viewEditNilai($slug)
    {
        $sidang = SeminarSidang::where('slug', $slug)->first();
        $dosenId = Dosen::where('nip_nidk', Auth::user()->nim_nip)->first()->id;

        $parameterSkripsiIds = ParameterPenilaian::where('tahapan_ta', 'Skripsi')->get()->pluck('id');
        $parameterArtikelIds = ParameterPenilaian::where('tahapan_ta', 'Artikel')->get()->pluck('id');
        $parameterPresentasiIds = ParameterPenilaian::where('tahapan_ta', 'Presentasi')->get()->pluck('id');

        $skripsiNilais = Penilaian::whereIn('parameter_penilaian_id', $parameterSkripsiIds)
            ->where('seminar_sidang_id', $sidang->id)
            ->where('dosen_id', $dosenId)
            ->get();
        $artikelNilais = Penilaian::whereIn('parameter_penilaian_id', $parameterArtikelIds)
            ->where('seminar_sidang_id', $sidang->id)
            ->where('dosen_id', $dosenId)
            ->get();
        $presentasiNilais = Penilaian::whereIn('parameter_penilaian_id', $parameterPresentasiIds)
            ->where('seminar_sidang_id', $sidang->id)
            ->where('dosen_id', $dosenId)
            ->get();

        $skripsiParameters = ParameterPenilaian::where('tahapan_ta', 'Skripsi')->get();
        $artikelParameters = ParameterPenilaian::where('tahapan_ta', 'Artikel')->get();
        $presentasiParameters = ParameterPenilaian::where('tahapan_ta', 'Presentasi')->get();

        $totalPersentaseSkripsi = $skripsiParameters->count() ? $skripsiParameters->sum('persentase') : 0;
        $totalPersentaseArtikel = $artikelParameters->count() ? $artikelParameters->sum('persentase') : 0;
        $totalPersentasePresentasi = $presentasiParameters->count() ? $presentasiParameters->sum('persentase') : 0;

        return view('dosen.sidang.edit', compact('sidang', 'skripsiNilais', 'artikelNilais', 'presentasiNilais', 'skripsiParameters', 'artikelParameters', 'presentasiParameters', 'totalPersentaseSkripsi', 'totalPersentaseArtikel', 'totalPersentasePresentasi', 'dosenId'));
    }

    public function storeEditNilai(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
            'nilaiSkripsi.*' => 'required|numeric|min:0|max:100',
            'nilaiArtikel.*' => 'required|numeric|min:0|max:100',
            'nilaiPresentasi.*' => 'required|numeric|min:0|max:100',
            'catatan' => 'nullable|max:500',
        ]);

        $sidang = SeminarSidang::where('slug', $slug)->first();

        $skripsiParameters = ParameterPenilaian::where('tahapan_ta', 'Skripsi')->get();
        $artikelParameters = ParameterPenilaian::where('tahapan_ta', 'Artikel')->get();
        $presentasiParameters = ParameterPenilaian::where('tahapan_ta', 'Presentasi')->get();

        $dosenId = Dosen::where('nip_nidk', Auth::user()->nim_nip)->first()->id;

        $nilaiSkripsis = Penilaian::whereIn('parameter_penilaian_id', $skripsiParameters->pluck('id'))
            ->where('seminar_sidang_id', $sidang->id)
            ->where('dosen_id', $dosenId)
            ->get();
        $nilaiArtikels = Penilaian::whereIn('parameter_penilaian_id', $artikelParameters->pluck('id'))
            ->where('seminar_sidang_id', $sidang->id)
            ->where('dosen_id', $dosenId)
            ->get();
        $nilaiPresentasis = Penilaian::whereIn('parameter_penilaian_id', $presentasiParameters->pluck('id'))
            ->where('seminar_sidang_id', $sidang->id)
            ->where('dosen_id', $dosenId)
            ->get();
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('fail', 'Gagal melakukan penilaian sidang akhir');
        } else {
            foreach ($skripsiParameters as $index => $skripsiParameter) {
                if (isset($nilaiSkripsis[$index])) {
                    $nilaiSkripsis[$index]->nilai = $request->nilaiSkripsi[$index];
                    $nilaiSkripsis[$index]->save();
                } else {
                    Penilaian::create([
                        'seminar_sidang_id' => $sidang->id,
                        'dosen_id' => $dosenId,
                        'mahasiswa_id' => $sidang->mahasiswa->id,
                        'parameter_penilaian_id' => $skripsiParameter->id,
                        'nilai' => $request->nilaiSkripsi[$index],
                    ]);
                }
            }

            foreach ($artikelParameters as $index => $artikelParameter) {
                if (isset($nilaiArtikels[$index])) {
                    $nilaiArtikels[$index]->nilai = $request->nilaiArtikel[$index];
                    $nilaiArtikels[$index]->save();
                } else {
                    Penilaian::create([
                        'seminar_sidang_id' => $sidang->id,
                        'dosen_id' => $dosenId,
                        'mahasiswa_id' => $sidang->mahasiswa->id,
                        'parameter_penilaian_id' => $artikelParameter->id,
                        'nilai' => $request->nilaiSkripsi[$index],
                    ]);
                }
            }

            foreach ($presentasiParameters as $index => $presentasiParameter) {
                if (isset($nilaiPresentasis[$index])) {
                    $nilaiPresentasis[$index]->nilai = $request->nilaiPresentasi[$index];
                    $nilaiPresentasis[$index]->save();
                } else {
                    Penilaian::create([
                        'seminar_sidang_id' => $sidang->id,
                        'dosen_id' => $dosenId,
                        'mahasiswa_id' => $sidang->mahasiswa->id,
                        'parameter_penilaian_id' => $presentasiParameter->id,
                        'nilai' => $request->nilaiSkripsi[$index],
                    ]);
                }
            }

            $totalNilai = $request->totalNilaiKeseluruhan;

            if ($sidang->tugas_akhir->dosen_pembimbing_1_id == $dosenId) {
                $sidang->total_nilai_pembimbing_1 = $totalNilai;
                if ($request->catatan != null) {
                    $sidang->catatan_ta_pembimbing_1 = $request->catatan;
                }
                $sidang->save();
            } elseif ($sidang->tugas_akhir->dosen_pembimbing_2_id == $dosenId) {
                $sidang->total_nilai_pembimbing_2 = $totalNilai;
                if ($request->catatan != null) {
                    $sidang->catatan_ta_pembimbing_2 = $request->catatan;
                }
                $sidang->save();
            } elseif ($sidang->tugas_akhir->dosen_penguji_1_id == $dosenId) {
                $sidang->total_nilai_penguji_1 = $totalNilai;
                if ($request->catatan != null) {
                    $sidang->catatan_ta_penguji_1 = $request->catatan;
                }
                $sidang->save();
            } elseif ($sidang->tugas_akhir->dosen_penguji_2_id == $dosenId) {
                $sidang->total_nilai_penguji_2 = $totalNilai;
                if ($request->catatan != null) {
                    $sidang->catatan_ta_penguji_2 = $request->catatan;
                }
                $sidang->save();
            }

            if ($sidang->total_nilai_pembimbing_1 !== null && $sidang->total_nilai_pembimbing_2 !== null && $sidang->total_nilai_penguji_1 !== null && $sidang->total_nilai_penguji_2 !== null) {
                $totalNilaiAkhir = round(($sidang->total_nilai_pembimbing_1 + $sidang->total_nilai_pembimbing_2 + $sidang->total_nilai_penguji_1 + $sidang->total_nilai_penguji_2) / 4, 2);
                $sidang->total_nilai_akhir = $totalNilaiAkhir;
                $sidang->save();
            } elseif ($sidang->total_nilai_pembimbing_1 == null && $sidang->total_nilai_pembimbing_2 !== null && $sidang->total_nilai_penguji_1 !== null && $sidang->total_nilai_penguji_2 !== null) {
                $totalNilaiAkhir = ($sidang->total_nilai_pembimbing_2 + $sidang->total_nilai_penguji_1 + $sidang->total_nilai_penguji_2) / 3;
                $sidang->total_nilai_akhir = number_format($totalNilaiAkhir, 2);
                $sidang->save();
            } elseif ($sidang->total_nilai_pembimbing_1 !== null && $sidang->total_nilai_pembimbing_2 == null && $sidang->total_nilai_penguji_1 !== null && $sidang->total_nilai_penguji_2 !== null) {
                $totalNilaiAkhir = ($sidang->total_nilai_pembimbing_1 + $sidang->total_nilai_penguji_1 + $sidang->total_nilai_penguji_2) / 3;
                $sidang->total_nilai_akhir = number_format($totalNilaiAkhir, 2);
                $sidang->save();
            } else {
                $sidang->total_nilai_akhir = null;
                $sidang->save();
            }

            $passingGrade = PassingGrade::where('tahapan_ta', 'Sidang Akhir')->first()->nilai;
            if ($sidang->total_nilai_akhir !== null && $sidang->total_nilai_akhir >= $passingGrade) {
                $sidang->status_kelulusan = 'LULUS';
                $sidang->save();
            } elseif ($sidang->total_nilai_akhir !== null && $sidang->total_nilai_akhir < $passingGrade) {
                $sidang->status_kelulusan = 'TIDAK LULUS';
                $sidang->save();
            } else {
                $sidang->status_kelulusan = 'Menunggu Keputusan';
                $sidang->save();
            }

            return redirect()->route('dosen.sidang.riwayat')->with('success', 'Berhasil melakukan penilaian Sidang Akhir');
        }
    }

    public function batalkanPenilaian($slug)
    {
        $sidang = SeminarSidang::where('slug', $slug)->first();
        $dosenId = Dosen::where('nip_nidk', Auth::user()->nim_nip)->first()->id;
        $nilais = Penilaian::where('seminar_sidang_id', $sidang->id)->where('dosen_id', $dosenId)->get();

        foreach ($nilais as $index => $nilai) {
            $nilais[$index]->forceDelete();
        }

        if ($sidang->tugas_akhir->dosen_pembimbing_1_id == $dosenId) {
            $sidang->total_nilai_pembimbing_1 = null;
            $sidang->catatan_ta_pembimbing_1 = null;
            $sidang->save();
        } elseif ($sidang->tugas_akhir->dosen_pembimbing_2_id == $dosenId) {
            $sidang->total_nilai_pembimbing_2 = null;
            $sidang->catatan_ta_pembimbing_2 = null;
            $sidang->save();
        } elseif ($sidang->tugas_akhir->dosen_penguji_1_id == $dosenId) {
            $sidang->total_nilai_penguji_1 = null;
            $sidang->catatan_ta_penguji_1 = null;
            $sidang->save();
        } elseif ($sidang->tugas_akhir->dosen_penguji_2_id == $dosenId) {
            $sidang->total_nilai_penguji_2 = null;
            $sidang->catatan_ta_penguji_2 = null;
            $sidang->save();
        }

        if ($sidang->total_nilai_pembimbing_1 !== null && $sidang->total_nilai_pembimbing_2 !== null && $sidang->total_nilai_penguji_1 !== null && $sidang->total_nilai_penguji_2 !== null) {
            $totalNilaiAkhir = round(($sidang->total_nilai_pembimbing_1 + $sidang->total_nilai_pembimbing_2 + $sidang->total_nilai_penguji_1 + $sidang->total_nilai_penguji_2) / 4, 2);
            $sidang->total_nilai_akhir = $totalNilaiAkhir;
            $sidang->save();
        } elseif ($sidang->total_nilai_pembimbing_1 == null && $sidang->total_nilai_pembimbing_2 !== null && $sidang->total_nilai_penguji_1 !== null && $sidang->total_nilai_penguji_2 !== null) {
            $totalNilaiAkhir = ($sidang->total_nilai_pembimbing_2 + $sidang->total_nilai_penguji_1 + $sidang->total_nilai_penguji_2) / 3;
            $sidang->total_nilai_akhir = number_format($totalNilaiAkhir, 2);
            $sidang->save();
        } elseif ($sidang->total_nilai_pembimbing_1 !== null && $sidang->total_nilai_pembimbing_2 == null && $sidang->total_nilai_penguji_1 !== null && $sidang->total_nilai_penguji_2 !== null) {
            $totalNilaiAkhir = ($sidang->total_nilai_pembimbing_1 + $sidang->total_nilai_penguji_1 + $sidang->total_nilai_penguji_2) / 3;
            $sidang->total_nilai_akhir = number_format($totalNilaiAkhir, 2);
            $sidang->save();
        } else {
            $sidang->total_nilai_akhir = null;
            $sidang->save();
        }

        $passingGrade = PassingGrade::where('tahapan_ta', 'Sidang Akhir')->first()->nilai;
        if ($sidang->total_nilai_akhir == null) {
            $sidang->status_kelulusan = 'Menunggu Keputusan';
        } elseif ($sidang->total_nilai_akhir !== null && $sidang->total_nilai_akhir >= $passingGrade) {
            $sidang->status_kelulusan = 'LULUS';
            $sidang->save();
        } elseif ($sidang->total_nilai_akhir !== null && $sidang->total_nilai_akhir < $passingGrade) {
            $sidang->status_kelulusan = 'TIDAK LULUS';
            $sidang->save();
        } else {
            $sidang->status_kelulusan = 'Menunggu Keputusan';
            $sidang->save();
        }

        return redirect()->route('dosen.sidang.riwayat')->with('success', 'Berhasil membatalkan penilaian');
    }
}
