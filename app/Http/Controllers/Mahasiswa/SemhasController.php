<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\TugasAkhir;
use Illuminate\Http\Request;
use App\Models\SeminarSidang;
use App\Models\RekamBimbingan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SemhasController extends Controller
{
    // public function viewIndex()
    // {
    //     $mahasiswa = Mahasiswa::where('nim', Auth::user()->nim_nip)->first()->id;
    //     $seminarSidang = SeminarSidang::select('*')->where('mahasiswa_id', $mahasiswa)->latest()->first();
    //     $seminarSidangs = SeminarSidang::where('mahasiswa_id', $mahasiswa)->where('tahapan_ta', 'Seminar Hasil')->get();
    //     $dosen = Dosen::all()->except(1);
    //     $juduls = SeminarSidang::with('tugas_akhir')->where('mahasiswa_id', $mahasiswa)->where('tahapan_ta', 'Seminar Proposal')->where('status_kelulusan', 'LULUS')->get();
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
    //     return view('mahasiswa.semhas.index', compact('seminarSidang', 'seminarSidangs', 'dosen', 'juduls', 'rekamBimbingans1', 'rekamBimbingans2'));
    // }

    private function getDataForViewIndex($userId)
    {
        $mahasiswa = Mahasiswa::where('nim', $userId)->first();
        $mahasiswaId = $mahasiswa ? $mahasiswa->id : null;

        if (!$mahasiswaId) {
            return [
                'mahasiswaId' => $mahasiswaId,
                'seminarSidang' => null,
                'seminarSidangs' => collect(),
                'dosen' => Dosen::all()->except(1),
                'juduls' => collect(),
                'rekamBimbingans1' => 0,
                'rekamBimbingans2' => 0,
            ];
        }

        $seminarSidang = SeminarSidang::where('mahasiswa_id', $mahasiswaId)->latest()->first();

        $seminarSidangs = SeminarSidang::where('mahasiswa_id', $mahasiswaId)->where('tahapan_ta', 'Seminar Hasil')->get();

        $juduls = SeminarSidang::with('tugas_akhir')->where('mahasiswa_id', $mahasiswaId)->where('tahapan_ta', 'Seminar Proposal')->where('status_kelulusan', 'LULUS')->get();

        $dosenPembimbing = TugasAkhir::where('mahasiswa_id', $mahasiswaId)->latest()->first();

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

        return [
            'seminarSidang' => $seminarSidang,
            'seminarSidangs' => $seminarSidangs,
            'dosen' => Dosen::all()->except(1),
            'juduls' => $juduls,
            'rekamBimbingans1' => $rekamBimbingans1,
            'rekamBimbingans2' => $rekamBimbingans2,
            'mahasiswaId' => $mahasiswaId,
        ];
    }

    public function viewIndex()
    {
        $data = $this->getDataForViewIndex(Auth::user()->nim_nip);

        if (!$data['mahasiswaId']) {
            // Handle case where mahasiswa is not found or return an appropriate view/message
            return redirect()->back()->withErrors('Mahasiswa not found or unauthorized.');
        }
        Log::channel('slack')->info(Auth::user()->name . ' Mengakses halaman daftar semhas!');
        $data['dataMhsNotulen'] = Mahasiswa::all()->except(1);

        return view('mahasiswa.semhas.index', $data);
    }

    public function storePendaftaran(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|max:255',
            'notulen_mahasiswa_id' => 'required',
            'dokumenSemhas' => 'required|file|mimes:pdf',
            'accJadwal' => 'required|file|mimes:pdf',
            'fileLainnya.*' => 'file',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('fail', 'Pendaftaran gagal, pastikan semua bagian formulir telah terisi dan file yang diupload sudah sesuai dengan format yang ditentukan');
        } else {
            $dokumen_ta = '';
            $fileLampiran[] = '';

            // Handle dokumenProposal
            if ($request->hasFile('dokumenSemhas')) {
                $dokumenSemhas = $request->file('dokumenSemhas');
                $namaFile = uniqid() . '_' . $dokumenSemhas->getClientOriginalName();
                $dokumenSemhas->storeAs($namaFile);
                $dokumen_ta = $namaFile;
            }

            if ($request->hasFile('accJadwal')) {
                $accJadwal = $request->file('accJadwal');
                $namaFile = uniqid() . '_' . $accJadwal->getClientOriginalName();
                $accJadwal->storeAs($namaFile);
                $fileLampiran[] .= $namaFile;
            }

            // Handle fileLainnya (multiple files)

            if ($request->hasFile('fileLainnya')) {
                foreach ($request->file('fileLainnya') as $fileLainnya) {
                    $namaFile = uniqid() . '_' . $fileLainnya->getClientOriginalName();
                    $fileLainnya->storeAs($namaFile);
                    $fileLampiran[] .= $namaFile;
                }
            }

            $mhs_id = Mahasiswa::where('nim', Auth::user()->nim_nip)->first()->id;

            SeminarSidang::create([
                'mahasiswa_id' => $mhs_id,
                'notulen_mahasiswa_id' => $request->notulen_mahasiswa_id,
                'tugas_akhir_id' => $request->judul,
                'tahapan_ta' => 'Seminar Hasil',
                'dokumen_ta' => $dokumen_ta,
                'file_lampiran' => json_encode($fileLampiran),
            ]);

            return redirect()->back()->with('success', 'Pendaftaran Berhasil, silahkan menunggu admin untuk memverifikasi pendaftaran kamu');
        }
    }

    public function viewUbah($slug)
    {
        $semhas = SeminarSidang::where('slug', $slug)->first();
        if ($semhas) {
            if ($semhas->status_pendaftaran == 'Diterima') {
                return redirect()->route('view.semhas');
            } else {
                if (Auth::user()->id == $semhas->mahasiswa->user_id) {
                    $mahasiswa = Mahasiswa::where('nim', Auth::user()->nim_nip)->first()->id;
                    $juduls = SeminarSidang::with('tugas_akhir')->where('mahasiswa_id', $mahasiswa)->where('tahapan_ta', 'Seminar Proposal')->where('status_kelulusan', 'LULUS')->get();
                    return view('mahasiswa.semhas.ubah', compact('semhas', 'juduls'));
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
            'judul' => 'required|max:255',
            'notulen_mahasiswa_id' => 'required',
            'dokumenSemhas' => 'file|mimes:pdf',
            'accJadwal' => 'file|mimes:pdf',
            'fileLainnya.*' => 'file',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('fail', 'Pendaftaran gagal, pastikan semua bagian formulir telah terisi dan file yang diupload sudah sesuai dengan format yang ditentukan');
        } else {
            $seminarSidang = SeminarSidang::where('slug', $slug)->first();
            $lampiran = json_decode($seminarSidang->file_lampiran);
            $dokumen_ta = '';
            for ($i = 0; $i < count($lampiran); $i++) {
                $fileLampiran[$i] = $lampiran[$i];
            }

            if ($request->hasFile('dokumenSemhas')) {
                Storage::delete($seminarSidang->dokumen_ta);
                $dokumenSemhas = $request->file('dokumenSemhas');
                $namaFile = uniqid() . '_' . $dokumenSemhas->getClientOriginalName();
                $dokumenSemhas->storeAs($namaFile);
                $dokumen_ta = $namaFile;
                $seminarSidang->dokumen_ta = $dokumen_ta;
                $seminarSidang->save();
            }

            if ($request->hasFile('accJadwal')) {
                if (Storage::exists($lampiran[1])) {
                    Storage::delete($lampiran[1]);
                }
                $accJadwal = $request->file('accJadwal');
                $namaFile = uniqid() . '_' . $accJadwal->getClientOriginalName();
                $accJadwal->storeAs($namaFile);
                $fileLampiran[1] = $namaFile;
            }

            if ($request->hasFile('fileLainnya')) {
                for ($i = 2; $i < count($lampiran); $i++) {
                    if (Storage::exists($lampiran[$i])) {
                        Storage::delete($lampiran[$i]);
                    }
                }
                $i = 2;
                foreach ($request->file('fileLainnya') as $fileLainnya) {
                    $namaFile = uniqid() . '_' . $fileLainnya->getClientOriginalName();
                    $fileLainnya->storeAs($namaFile);
                    $fileLampiran[$i++] = $namaFile;
                }
            }

            $seminarSidang->tugas_akhir_id = $request->judul;
            $seminarSidang->notulen_mahasiswa_id = $request->notulen_mahasiswa_id;
            $seminarSidang->status_pendaftaran = 'Menunggu Verifikasi';
            $seminarSidang->file_lampiran = json_encode($fileLampiran);
            $seminarSidang->save();

            return redirect()->route('view.semhas')->with('success', 'Pendaftaran Berhasil Diubah');
        }
    }

    public function uploadRevisi(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
            'fileRevisi' => 'required|file|mimes:pdf',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('fail', 'Gagal menyimpan, pastikan format file anda sudah benar');
        } else {
            $dokumen_revisi = '';
            $seminarSidang = SeminarSidang::where('slug', $slug)->first();
            if ($request->hasFile('fileRevisi')) {
                $fileRevisi = $request->file('fileRevisi');
                $namaFile = uniqid() . '_' . $fileRevisi->getClientOriginalName();
                $fileRevisi->storeAs($namaFile);
                $dokumen_revisi = $namaFile;
            }
            $seminarSidang->file_revisi = $dokumen_revisi;
            $seminarSidang->save();

            return redirect()->back()->withErrors($validator)->withInput()->with('success', 'Berhasil Menyimpan');
        }
    }

    public function ubahRevisi(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
            'fileRevisi' => 'required|file|mimes:pdf',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('fail', 'Gagal menyimpan, pastikan format file anda sudah benar');
        } else {
            $dokumen_revisi = '';
            $seminarSidang = SeminarSidang::where('slug', $slug)->first();
            if ($request->hasFile('fileRevisi')) {
                Storage::delete($seminarSidang->file_revisi);
                $fileRevisi = $request->file('fileRevisi');
                $namaFile = uniqid() . '_' . $fileRevisi->getClientOriginalName();
                $fileRevisi->storeAs($namaFile);
                $dokumen_revisi = $namaFile;
            }
            $seminarSidang->file_revisi = $dokumen_revisi;
            $seminarSidang->save();

            return redirect()->back()->withErrors($validator)->withInput()->with('success', 'Berhasil Diubah');
        }
    }

    public function delete($slug)
    {
        $seminarSidang = SeminarSidang::where('slug', $slug)->first();
        if ($seminarSidang) {
            if ($seminarSidang->status_kelulusan == 'Diterima') {
                return redirect()->route('view.semhas');
            } else {
                $lampiran = json_decode($seminarSidang->file_lampiran);
                for ($i = 0; $i < count($lampiran); $i++) {
                    $fileLampiran[$i] = $lampiran[$i];
                }

                for ($i = 0; $i < count($lampiran); $i++) {
                    if (Storage::exists($lampiran[$i])) {
                        Storage::delete($lampiran[$i]);
                    }
                }

                if (Storage::exists($seminarSidang->dokumen_ta)) {
                    Storage::delete($seminarSidang->dokumen_ta);
                }

                SeminarSidang::withTrashed()
                    ->find($seminarSidang->id)
                    ->forceDelete();

                return redirect()->route('view.semhas')->with('success', 'Data telah dihapus');
            }
        } else {
            return redirect()->route('view.semhas')->with('fail', 'Gagal menghapus data');
        }
    }

    public function viewHasil($slug)
    {
        $semhas = SeminarSidang::with('tugas_akhir')->where('slug', $slug)->where('tahapan_ta', 'Seminar Hasil')->first();
        if ($semhas) {
            if ($semhas->status_kelulusan == 'LULUS' || $semhas->status_kelulusan == 'TIDAK LULUS') {
                if (Auth::user()->id == $semhas->mahasiswa->user_id) {
                    return view('mahasiswa.semhas.hasil', compact('semhas'));
                } else {
                    abort(403, 'Privacy lah cuy');
                }
            } else {
                return redirect()->route('view.semhas');
            }
        } else {
            abort(404);
        }
    }

    public function viewDetail($slug)
    {
        $semhas = SeminarSidang::with('tugas_akhir')->where('slug', $slug)->where('tahapan_ta', 'Seminar Hasil')->first();
        if ($semhas) {
            if (Auth::user()->id == $semhas->mahasiswa->user_id) {
                return view('mahasiswa.semhas.detail', compact('semhas'));
            } else {
                abort(403);
            }
        } else {
            abort(404);
        }
    }
}
