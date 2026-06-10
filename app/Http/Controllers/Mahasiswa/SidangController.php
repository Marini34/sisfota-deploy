<?php

namespace App\Http\Controllers\Mahasiswa;

use Carbon\Carbon;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\TugasAkhir;
use Illuminate\Http\Request;
use App\Models\SeminarSidang;
use App\Models\RekamBimbingan;
use App\Http\Controllers\Controller;
use App\Models\Makul;
use App\Models\StatusTranskrip;
use App\Models\TranskripNilai;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SidangController extends Controller
{
    // public function viewIndex()
    // {
    //     $mahasiswa = Mahasiswa::where('nim', Auth::user()->nim_nip)->first()->id;

    //     $statusTranskrip = StatusTranskrip::where('mahasiswa_id', $mahasiswa)->first();

    //     $seminarSidang = SeminarSidang::select('*')->where('mahasiswa_id', $mahasiswa)->latest()->first();
    //     $dosen = Dosen::all()->except(1);
    //     $seminarSidangs = SeminarSidang::where('mahasiswa_id', $mahasiswa)->where('tahapan_ta', 'Sidang Akhir')->get();
    //     $juduls = SeminarSidang::with('tugas_akhir')->where('mahasiswa_id', $mahasiswa)->where('tahapan_ta', 'Seminar Hasil')->where('status_kelulusan', 'LULUS')->get();
    //     $dosenPembimbing = TugasAkhir::where('mahasiswa_id', $mahasiswa)->latest()->first();
    //     $rekamBimbingans1 = 0;
    //     $rekamBimbingans2 = 0;
    //     if ($dosenPembimbing) {
    //         if ($dosenPembimbing->dosen_pembimbing_1_id !== null) {
    //             $rekamBimbingans1 = RekamBimbingan::where('mahasiswa_id', $mahasiswa)
    //                 ->where('pembimbing_id', $dosenPembimbing->dosen_pembimbing_1_id)
    //                 ->where('status_rekam_bimbingan', 'Diterima')
    //                 ->get()
    //                 ->count();
    //         }
    //         if ($dosenPembimbing->dosen_pembimbing_2_id !== null) {
    //             $rekamBimbingans2 = RekamBimbingan::where('mahasiswa_id', $mahasiswa)
    //                 ->where('pembimbing_id', $dosenPembimbing->dosen_pembimbing_2_id)
    //                 ->where('status_rekam_bimbingan', 'Diterima')
    //                 ->get()
    //                 ->count();
    //         }
    //     }
    //     if ($statusTranskrip && $statusTranskrip->status_transkrip == 'Diterima') {
    //         return view('mahasiswa.sidang.index', compact('seminarSidang', 'seminarSidangs', 'dosen', 'juduls', 'rekamBimbingans1', 'rekamBimbingans2'));
    //     } else {
    //         $transkrips = TranskripNilai::where('mahasiswa_id', $mahasiswa)->get();
    //         $makuls = Makul::orderBy('nama_makul', 'asc')->get();

    //         $jumlahSks = 0;
    //         $jumlahMutu = 0;
    //         $jumlahMakul = 0;
    //         $ipk = [];
    //         foreach ($transkrips as $index => $transkrip) {
    //             if ($transkrip->makul) {
    //                 $sks[$index] = $transkrip->makul->sks;
    //                 $jumlahSks += $sks[$index];

    //                 $jumlahMakul += 1;

    //                 switch ($transkrip->nilai) {
    //                     case 'A':
    //                         $nilai = 4;
    //                         break;
    //                     case 'B+':
    //                         $nilai = 3.5;
    //                         break;
    //                     case 'B':
    //                         $nilai = 3.0;
    //                         break;
    //                     case 'C+':
    //                         $nilai = 2.5;
    //                         break;
    //                     case 'C':
    //                         $nilai = 2.0;
    //                         break;
    //                     case 'D+':
    //                         $nilai = 1.5;
    //                         break;
    //                     case 'D':
    //                         $nilai = 1.0;
    //                         break;
    //                     case 'E':
    //                         $nilai = 0;
    //                         break;
    //                     default:
    //                         $nilai = null;
    //                         break;
    //                 }

    //                 if ($nilai !== null) {
    //                     $ipk[$index] = $nilai * $sks[$index];
    //                     $jumlahMutu += $ipk[$index];
    //                 }
    //             }
    //         }

    //         if ($jumlahSks > 0) {
    //             $ipkAkhir = $jumlahMutu / $jumlahSks;
    //         } else {
    //             $ipkAkhir = 0;
    //         }

    //         return view('mahasiswa.sidang.transkrip', compact('seminarSidang', 'seminarSidangs', 'dosen', 'juduls', 'rekamBimbingans1', 'rekamBimbingans2', 'transkrips', 'makuls', 'jumlahSks', 'ipkAkhir', 'statusTranskrip', 'jumlahMutu', 'jumlahMakul'));
    //     }
    // }

    private function getDataForViewIndex($userId)
    {
        $mahasiswa = Mahasiswa::where('nim', $userId)->first();
        $mahasiswaId = $mahasiswa ? $mahasiswa->id : null;

        if (!$mahasiswaId) {
            return [
                'seminarSidang' => null,
                'seminarSidangs' => collect(),
                'dosen' => Dosen::all()->except(1),
                'juduls' => collect(),
                'rekamBimbingans1' => 0,
                'rekamBimbingans2' => 0,
                'mahasiswaId' => null,
                'statusTranskrip' => null,
                'transkrips' => collect(),
                'makuls' => collect(),
                'jumlahSks' => 0,
                'jumlahMutu' => 0,
                'jumlahMakul' => 0,
                'ipkAkhir' => 0,
            ];
        }

        $seminarSidang = SeminarSidang::where('mahasiswa_id', $mahasiswaId)->latest()->first();
        $seminarSidangs = SeminarSidang::where('mahasiswa_id', $mahasiswaId)->where('tahapan_ta', 'Sidang Akhir')->get();
        $juduls = SeminarSidang::with('tugas_akhir')->where('mahasiswa_id', $mahasiswaId)->where('tahapan_ta', 'Seminar Hasil')->where('status_kelulusan', 'LULUS')->get();
        $dosenPembimbing = TugasAkhir::where('mahasiswa_id', $mahasiswaId)->latest()->first();
        $statusTranskrip = StatusTranskrip::where('mahasiswa_id', $mahasiswaId)->first();

        $rekamBimbingans1 = 0;
        $rekamBimbingans2 = 0;

        if ($dosenPembimbing) {
            if ($dosenPembimbing->dosen_pembimbing_1_id !== null) {
                $rekamBimbingans1 = RekamBimbingan::where('mahasiswa_id', $mahasiswaId)
                    ->where('pembimbing_id', $dosenPembimbing->dosen_pembimbing_1_id)
                    ->where('status_rekam_bimbingan', 'Diterima')
                    ->count();
            }
            if ($dosenPembimbing->dosen_pembimbing_2_id !== null) {
                $rekamBimbingans2 = RekamBimbingan::where('mahasiswa_id', $mahasiswaId)
                    ->where('pembimbing_id', $dosenPembimbing->dosen_pembimbing_2_id)
                    ->where('status_rekam_bimbingan', 'Diterima')
                    ->count();
            }
        }

        $transkrips = TranskripNilai::where('mahasiswa_id', $mahasiswaId)->get();
        $makuls = Makul::orderBy('nama_makul', 'asc')->get();

        $jumlahSks = 0;
        $jumlahMutu = 0;
        $jumlahMakul = 0;
        $ipk = [];

        foreach ($transkrips as $index => $transkrip) {
            if ($transkrip->makul) {
                $sks[$index] = $transkrip->makul->sks;
                $jumlahSks += $sks[$index];
                $jumlahMakul += 1;

                switch ($transkrip->nilai) {
                    case 'A':
                        $nilai = 4;
                        break;
                    case 'B+':
                        $nilai = 3.5;
                        break;
                    case 'B':
                        $nilai = 3.0;
                        break;
                    case 'C+':
                        $nilai = 2.5;
                        break;
                    case 'C':
                        $nilai = 2.0;
                        break;
                    case 'D+':
                        $nilai = 1.5;
                        break;
                    case 'D':
                        $nilai = 1.0;
                        break;
                    case 'E':
                        $nilai = 0;
                        break;
                    default:
                        $nilai = null;
                        break;
                }

                if ($nilai !== null) {
                    if ($nilai !== null) {
                        $ipk[$index] = $nilai * $sks[$index];
                        $jumlahMutu += $ipk[$index];
                    }
                }
            }
        }

        $ipkAkhir = $jumlahSks > 0 ? $jumlahMutu / $jumlahSks : 0;

        return [
            'seminarSidang' => $seminarSidang,
            'seminarSidangs' => $seminarSidangs,
            'dosen' => Dosen::all()->except(1),
            'juduls' => $juduls,
            'rekamBimbingans1' => $rekamBimbingans1,
            'rekamBimbingans2' => $rekamBimbingans2,
            'mahasiswaId' => $mahasiswaId,
            'statusTranskrip' => $statusTranskrip,
            'transkrips' => $transkrips,
            'makuls' => $makuls,
            'jumlahSks' => $jumlahSks,
            'jumlahMutu' => $jumlahMutu,
            'jumlahMakul' => $jumlahMakul,
            'ipkAkhir' => $ipkAkhir,
        ];
    }

    public function viewIndex()
    {
        $data = $this->getDataForViewIndex(Auth::user()->nim_nip);
        Log::channel('slack')->info(Auth::user()->name . ' Mengakses halaman sidang!');


        if ($data['statusTranskrip'] && $data['statusTranskrip']->status_transkrip == 'Diterima') {
            return view('mahasiswa.sidang.index', $data);
        } else {
            return view('mahasiswa.sidang.transkrip', $data);
        }
    }
    public function storePendaftaran(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|max:255',
            'dokumenSidang' => 'required|file|mimes:pdf',
            'artikel' => 'required|file|mimes:pdf',
            'syaratSidang' => 'required|file|mimes:pdf',
            'accJadwalSidang' => 'required|file|mimes:pdf',
            'fileLainnya.*' => 'file'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('fail', 'Pendaftaran gagal, pastikan semua bagian formulir telah terisi dan file sesuai format');
        }

        $fileLampiran = [];
        $dokumen_ta = null;

        /*
    ========================
    DOKUMEN SIDANG (utama)
    ========================
    */

        if ($request->hasFile('dokumenSidang')) {
            $file = $request->file('dokumenSidang');
            $namaFile = uniqid() . '_' . $file->getClientOriginalName();
            $file->storeAs('sidang', $namaFile, 'public');

            $dokumen_ta = 'sidang/' . $namaFile;
        }

        /*
    ========================
    FILE LAMPIRAN
    ========================
    */

        $lampiranFields = [
            'artikel',
            'syaratSidang',
            'accJadwalSidang'
        ];

        foreach ($lampiranFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $namaFile = uniqid() . '_' . $file->getClientOriginalName();
                $file->storeAs('sidang/lampiran', $namaFile, 'public');
                $fileLampiran[] = 'sidang/lampiran/' . $namaFile;
            }
        }

        /*
    ========================
    FILE LAINNYA (MULTIPLE)
    ========================
    */

        if ($request->hasFile('fileLainnya')) {
            foreach ($request->file('fileLainnya') as $file) {
                $namaFile = uniqid() . '_' . $file->getClientOriginalName();
                $file->storeAs('sidang/lampiran', $namaFile, 'public');

                $fileLampiran[] = $namaFile;
            }
        }

        /*
    ========================
    SIMPAN DATABASE
    ========================
    */

        $mhs_id = Mahasiswa::where('nim', Auth::user()->nim_nip)->value('id');

        SeminarSidang::create([
            'mahasiswa_id' => $mhs_id,
            'tugas_akhir_id' => $request->judul,
            'tahapan_ta' => 'Sidang Akhir',
            'dokumen_ta' => $dokumen_ta,
            'file_lampiran' => json_encode($fileLampiran),
        ]);

        return redirect()->back()->with('success', 'Pendaftaran berhasil, silahkan menunggu verifikasi admin');
    }

    public function viewUbah($slug)
    {
        $sidang = SeminarSidang::where('slug', $slug)->first();
        if ($sidang) {
            if ($sidang->status_pendaftaran == 'Diterima') {
                return redirect()->route('view.sidang');
            } else {
                if (Auth::user()->id == $sidang->mahasiswa->user_id) {
                    $mahasiswa = Mahasiswa::where('nim', Auth::user()->nim_nip)->first()->id;
                    $juduls = SeminarSidang::with('tugas_akhir')->where('mahasiswa_id', $mahasiswa)->where('tahapan_ta', 'Seminar Hasil')->where('status_kelulusan', 'LULUS')->get();
                    return view('mahasiswa.sidang.ubah', compact('sidang', 'juduls'));
                } else {
                    abort(403, 'Biar Ape Sih? Biar Keren? Iye iye kaulah yang paling keren');
                }
            }
        } else {
            abort(404);
        }
    }

    public function storeUbah(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'max:255',
            'dokumenSidang' => 'file|mimes:pdf',
            'artikel' => 'file|mimes:pdf',
            'syaratSidang' => 'file|mimes:pdf',
            'accJadwalSidang' => 'file|mimes:pdf',
            'fileLainnya.*' => 'file',
            // 'bebasLabMipa' => 'file|mimes:jpeg,jpg,png,pdf',
            // 'bebasLabNonMipa' => 'file|mimes:jpeg,jpg,png,pdf',
            // 'bebasPerpustakaanMipa' => 'file|mimes:jpeg,jpg,png,pdf',
            // 'bebasPerpustakaanUntan' => 'file|mimes:jpeg,jpg,png,pdf',
            // 'bebasPerpustakaanWilayah' => 'file|mimes:jpeg,jpg,png,pdf',
            // 'SKPembimbing' => 'file|pdf',
            // 'SKPenguji' => 'file|pdf',
            // 'buktiTutep' => 'file|mimes:jpeg,jpg,png,pdf',
            // 'suratSidang' => 'file|mimes:jpeg,jpg,png,pdf',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('fail', 'Gagal melakukan perubahan, pastikan semua bagian formulir telah terisi dan file yang diupload sudah sesuai dengan format yang ditentukan');
        } else {
            $seminarSidang = SeminarSidang::where('slug', $slug)->first();
            $lampiran = json_decode($seminarSidang->file_lampiran);
            $dokumen_ta = '';
            for ($i = 0; $i < count($lampiran); $i++) {
                $fileLampiran[$i] = $lampiran[$i];
            }

            if ($request->hasFile('dokumenSidang')) {
                Storage::delete($seminarSidang->dokumen_ta);
                $dokumenSidang = $request->file('dokumenSidang');
                $namaFile = uniqid() . '_' . $dokumenSidang->getClientOriginalName();
                $dokumenSidang->storeAs($namaFile);
                $dokumen_ta = $namaFile;
                $seminarSidang->dokumen_ta = $dokumen_ta;
                $seminarSidang->save();
            }

            if ($request->hasFile('artikel')) {
                if (Storage::exists($lampiran[1])) {
                    Storage::delete($lampiran[1]);
                }
                $accJadwalSidang = $request->file('artikel');
                $namaFile = uniqid() . '_' . $accJadwalSidang->getClientOriginalName();
                $accJadwalSidang->storeAs($namaFile);
                $fileLampiran[1] = $namaFile;
            }

            if ($request->hasFile('syaratSidang')) {
                if (Storage::exists($lampiran[2])) {
                    Storage::delete($lampiran[2]);
                }
                $accJadwalSidang = $request->file('syaratSidang');
                $namaFile = uniqid() . '_' . $accJadwalSidang->getClientOriginalName();
                $accJadwalSidang->storeAs($namaFile);
                $fileLampiran[2] = $namaFile;
            }

            if ($request->hasFile('accJadwalSidang')) {
                if (Storage::exists($lampiran[3])) {
                    Storage::delete($lampiran[3]);
                }
                $accJadwalSidang = $request->file('accJadwalSidang');
                $namaFile = uniqid() . '_' . $accJadwalSidang->getClientOriginalName();
                $accJadwalSidang->storeAs($namaFile);
                $fileLampiran[3] = $namaFile;
            }


            // if ($request->hasFile('bebasLabMipa')) {
            //     if (Storage::exists($lampiran[3])) {
            //         Storage::delete($lampiran[3]);
            //     }
            //     $bebasLabMipa = $request->file('bebasLabMipa');
            //     $namaFile = uniqid() . '_' . $bebasLabMipa->getClientOriginalName();
            //     $bebasLabMipa->storeAs($namaFile);
            //     $fileLampiran[3] = $namaFile;
            // }

            // if ($request->hasFile('bebasLabNonMipa')) {
            //     if (Storage::exists($lampiran[4])) {
            //         Storage::delete($lampiran[4]);
            //     }
            //     $bebasLabNonMipa = $request->file('bebasLabNonMipa');
            //     $namaFile = uniqid() . '_' . $bebasLabNonMipa->getClientOriginalName();
            //     $bebasLabNonMipa->storeAs($namaFile);
            //     $fileLampiran[4] = $namaFile;
            // }

            // if ($request->hasFile('bebasPerpustakaanMipa')) {
            //     if (Storage::exists($lampiran[5])) {
            //         Storage::delete($lampiran[5]);
            //     }
            //     $bebasPerpustakaanMipa = $request->file('bebasPerpustakaanMipa');
            //     $namaFile = uniqid() . '_' . $bebasPerpustakaanMipa->getClientOriginalName();
            //     $bebasPerpustakaanMipa->storeAs($namaFile);
            //     $fileLampiran[5] = $namaFile;
            // }

            // if ($request->hasFile('bebasPerpustakaanUntan')) {
            //     if (Storage::exists($lampiran[6])) {
            //         Storage::delete($lampiran[6]);
            //     }
            //     $bebasPerpustakaanUntan = $request->file('bebasPerpustakaanUntan');
            //     $namaFile = uniqid() . '_' . $bebasPerpustakaanUntan->getClientOriginalName();
            //     $bebasPerpustakaanUntan->storeAs($namaFile);
            //     $fileLampiran[6] = $namaFile;
            // }

            // if ($request->hasFile('bebasPerpustakaanWilayah')) {
            //     if (Storage::exists($lampiran[7])) {
            //         Storage::delete($lampiran[7]);
            //     }
            //     $bebasPerpustakaanWilayah = $request->file('bebasPerpustakaanWilayah');
            //     $namaFile = uniqid() . '_' . $bebasPerpustakaanWilayah->getClientOriginalName();
            //     $bebasPerpustakaanWilayah->storeAs($namaFile);
            //     $fileLampiran[7] = $namaFile;
            // }

            // if ($request->hasFile('SKPembimbing')) {
            //     if (Storage::exists($lampiran[8])) {
            //         Storage::delete($lampiran[8]);
            //     }
            //     $SKPembimbing = $request->file('SKPembimbing');
            //     $namaFile = uniqid() . '_' . $SKPembimbing->getClientOriginalName();
            //     $SKPembimbing->storeAs($namaFile);
            //     $fileLampiran[8] = $namaFile;
            // }

            // if ($request->hasFile('SKPenguji')) {
            //     if (Storage::exists($lampiran[9])) {
            //         Storage::delete($lampiran[9]);
            //     }
            //     $SKPenguji = $request->file('SKPenguji');
            //     $namaFile = uniqid() . '_' . $SKPenguji->getClientOriginalName();
            //     $SKPenguji->storeAs($namaFile);
            //     $fileLampiran[9] = $namaFile;
            // }

            // if ($request->hasFile('buktiTutep')) {
            //     if (Storage::exists($lampiran[10])) {
            //         Storage::delete($lampiran[10]);
            //     }
            //     $buktiTutep = $request->file('buktiTutep');
            //     $namaFile = uniqid() . '_' . $buktiTutep->getClientOriginalName();
            //     $buktiTutep->storeAs($namaFile);
            //     $fileLampiran[10] = $namaFile;
            // }

            // if ($request->hasFile('suratSidang')) {
            //     if (Storage::exists($lampiran[11])) {
            //         Storage::delete($lampiran[11]);
            //     }
            //     $suratSidang = $request->file('suratSidang');
            //     $namaFile = uniqid() . '_' . $suratSidang->getClientOriginalName();
            //     $suratSidang->storeAs($namaFile);
            //     $fileLampiran[11] = $namaFile;
            // }

            if ($request->hasFile('fileLainnya')) {
                for ($i = 4; $i < count($lampiran); $i++) {
                    if (Storage::exists($lampiran[$i])) {
                        Storage::delete($lampiran[$i]);
                    }
                }
                $i = 4;
                foreach ($request->file('fileLainnya') as $fileLainnya) {
                    $namaFile = uniqid() . '_' . $fileLainnya->getClientOriginalName();
                    $fileLainnya->storeAs($namaFile);
                    $fileLampiran[$i++] = $namaFile;
                }
            }

            $seminarSidang->tugas_akhir_id = $request->judul;
            $seminarSidang->file_lampiran = json_encode($fileLampiran);
            $seminarSidang->status_pendaftaran = 'Menunggu Verifikasi';
            $seminarSidang->save();

            return redirect()->route('view.sidang')->with('success', 'Pendaftaran Berhasil Diubah');
        }
    }

    public function delete($slug)
    {
        $seminarSidang = SeminarSidang::where('slug', $slug)->first();
        if ($seminarSidang) {
            if ($seminarSidang->status_kelulusan == 'Diterima') {
                return redirect()->route('view.sidang');
            } else {
                $lampiran = json_decode($seminarSidang->file_lampiran);
                for ($i = 0; $i < count($lampiran); $i++) {
                    $fileLampiran[$i] = $lampiran[$i];
                }

                if (Storage::exists($seminarSidang->dokumen_ta)) {
                    Storage::delete($seminarSidang->dokumen_ta);
                }

                for ($i = 0; $i < count($lampiran); $i++) {
                    if (Storage::exists($lampiran[$i])) {
                        Storage::delete($lampiran[$i]);
                    }
                }

                SeminarSidang::withTrashed()
                    ->find($seminarSidang->id)
                    ->forceDelete();

                return redirect()->route('view.sidang')->with('success', 'Data telah dihapus');
            }
        } else {
            return redirect()->route('view.sidang')->with('fail', 'Gagal menghapus data');
        }
    }

    public function uploadFileTA(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
            'revisiArtikel' => 'nullable|file|mimes:pdf',
            'skpl' => 'nullable|file|mimes:pdf',
            'code' => 'nullable|file|mimes:zip',
            'SKPembimbing' => 'nullable|file|mimes:pdf',
            'SKPengujiSempro' => 'nullable|file|mimes:pdf',
            'SKPengujiSemhas' => 'nullable|file|mimes:pdf',
            'lembarPengesahan' => 'nullable|file|mimes:pdf',
            'undanganSemhas' => 'nullable|file|mimes:pdf',
            'undanganSidang' => 'nullable|file|mimes:pdf',
            'buktiSubmitJurnal' => 'nullable|file|mimes:pdf',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('fail', 'Gagal menyimpan, pastikan format file anda sudah benar');
        }

        $seminarSidang = SeminarSidang::where('slug', $slug)->firstOrFail();

        // index 0..10
        $fileAkhir = array_fill(0, 11, null);

        $fileTypes = [
            1 => 'revisiArtikel',
            2 => 'skpl',
            3 => 'code',
            4 => 'SKPembimbing',
            5 => 'SKPengujiSempro',
            6 => 'SKPengujiSemhas',
            7 => 'lembarPengesahan',
            8 => 'undanganSemhas',
            9 => 'undanganSidang',
            10 => 'buktiSubmitJurnal',
        ];

        foreach ($fileTypes as $index => $fileType) {
            if ($request->hasFile($fileType)) {
                $file = $request->file($fileType);
                $namaFile = uniqid() . '_' . $file->getClientOriginalName();

                // simpan ke storage/app/public/sidang/notulensi
                $path = $file->storeAs('sidang/notulensi', $namaFile, 'public');

                $fileAkhir[$index] = $path;
            }
        }

        $seminarSidang->file_notulensi = json_encode($fileAkhir);
        $seminarSidang->save();

        return redirect()->back()->with('success', 'Berhasil Menyimpan');
    }


    public function ubahFileTA(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
            'ubahRevisiArtikel' => 'nullable|file|mimes:pdf',
            'ubahSkpl' => 'nullable|file|mimes:pdf',
            'ubahCode' => 'nullable|file|mimes:zip',
            'ubahSKPembimbing' => 'nullable|file|mimes:pdf',
            'ubahSKPengujiSempro' => 'nullable|file|mimes:pdf',
            'ubahSKPengujiSemhas' => 'nullable|file|mimes:pdf',
            'ubahLembarPengesahan' => 'nullable|file|mimes:pdf',
            'ubahUndanganSemhas' => 'nullable|file|mimes:pdf',
            'ubahUndanganSidang' => 'nullable|file|mimes:pdf',
            'ubahRevisibuktiSubmitJurnal' => 'nullable|file|mimes:pdf',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('fail', 'Gagal menyimpan, pastikan format file anda sudah benar');
        }

        $seminarSidang = SeminarSidang::where('slug', $slug)->firstOrFail();
        $fileAkhir = json_decode($seminarSidang->file_notulensi, true) ?? array_fill(0, 11, null);

        $mapping = [
            1 => 'ubahRevisiArtikel',
            2 => 'ubahSkpl',
            3 => 'ubahCode',
            4 => 'ubahSKPembimbing',
            5 => 'ubahSKPengujiSempro',
            6 => 'ubahSKPengujiSemhas',
            7 => 'ubahLembarPengesahan',
            8 => 'ubahUndanganSemhas',
            9 => 'ubahUndanganSidang',
            10 => 'ubahRevisibuktiSubmitJurnal',
        ];

        foreach ($mapping as $index => $inputName) {
            if ($request->hasFile($inputName)) {
                // hapus file lama kalau ada
                if (!empty($fileAkhir[$index]) && Storage::disk('public')->exists($fileAkhir[$index])) {
                    Storage::disk('public')->delete($fileAkhir[$index]);
                }

                $file = $request->file($inputName);
                $namaFile = uniqid() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('sidang/notulensi', $namaFile, 'public');

                $fileAkhir[$index] = $path;
            }
        }

        $seminarSidang->file_notulensi = json_encode($fileAkhir);
        $seminarSidang->save();

        return redirect()->back()->with('success', 'Berhasil Diubah');
    }

    public function viewHasil($slug)
    {
        $sidang = SeminarSidang::with('tugas_akhir')->where('slug', $slug)->where('tahapan_ta', 'Sidang Akhir')->first();
        $semhas = SeminarSidang::where('tugas_akhir_id', $sidang->tugas_akhir_id)
            ->where('tahapan_ta', 'Seminar Hasil')
            ->where('status_kelulusan', 'LULUS')
            ->latest()
            ->first();
        if ($sidang) {
            if ($sidang->status_kelulusan == 'LULUS' || $sidang->status_kelulusan == 'TIDAK LULUS') {
                if (Auth::user()->id == $sidang->mahasiswa->user_id) {
                    return view('mahasiswa.sidang.hasil', compact(['sidang', 'semhas']));
                } else {
                    abort(403, 'Privacy lah cuy');
                }
            } else {
                return redirect()->route('view.sidang');
            }
        } else {
            abort(404);
        }
    }

    public function viewDetail($slug)
    {
        Carbon::setLocale('id');

        $sidang = SeminarSidang::with(['tugas_akhir', 'mahasiswa'])
            ->where('slug', $slug)
            ->where('tahapan_ta', 'Sidang Akhir')
            ->firstOrFail();

        abort_unless(Auth::id() == $sidang->mahasiswa->user_id, 403);

        $fileTranskrip = StatusTranskrip::where('mahasiswa_id', $sidang->mahasiswa_id)
            ->value('file_transkrip'); // null-safe

        return view('mahasiswa.sidang.detail', compact('sidang', 'fileTranskrip'));
    }
}
