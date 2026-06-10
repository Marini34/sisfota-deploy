<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\SeminarSidang;
use App\Models\TugasAkhir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SemproController extends Controller
{
    public function viewIndex()
    {
        $mahasiswa = Mahasiswa::where('nim', Auth::user()->nim_nip)->first()->id;
        $seminarSidang = SeminarSidang::select('*')->where('mahasiswa_id', $mahasiswa)->where('tahapan_ta', 'Seminar Proposal')->get();
        $notulensi = SeminarSidang::where('notulen_mahasiswa_id', $mahasiswa)
            ->where('mahasiswa_id', '!=', $mahasiswa)
            ->where('status_validasi_notulen_mhs', 'Disetujui')
            ->first();
        $dosen = Dosen::all()->except(1);
        $dosen1 = Dosen::all()->except([1, 8]);
        Log::channel('slack')->info(Auth::user()->name . ' Mengakses halaman daftar sempro!');
        $dataMhsNotulen = Mahasiswa::all()->except(1);

        return view('mahasiswa.sempro.index', compact('seminarSidang', 'dosen', 'dosen1', 'notulensi', 'dataMhsNotulen'));
    }

    public function storePendaftaran(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|max:255|unique:tugas_akhir,judul',
            'no_hp' => 'required|max:255',
            'notulen_mahasiswa_id' => 'required',
            'studi_kasus' => 'required|max:255',
            'metode' => 'required|max:255',
            'dosenPA' => 'required',
            'dospem1' => 'required',
            'dospem2' => 'required',
            'dokumenProposal' => 'required|file|mimes:pdf',
            'buktiHadir' => 'required|file|mimes:jpeg,jpg,png,pdf',
            'buktiNotulen' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx',
            'lirs' => 'required|file|mimes:pdf',
            'fileLainnya.*' => 'file',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('fail', 'Pendaftaran gagal, pastikan semua bagian formulir telah terisi dan file yang diupload sudah sesuai dengan format yang ditentukan');
        } else {
            $dokumen_ta = '';
            $fileLampiran[] = '';

            // Handle dokumenProposal
            if ($request->hasFile('dokumenProposal')) {
                $dokumenProposal = $request->file('dokumenProposal');
                $namaFile = uniqid() . '_' . $dokumenProposal->getClientOriginalName();
                $dokumenProposal->storeAs('public', $namaFile);
                $dokumen_ta = $namaFile;
            }

            // Handle buktiHadir
            if ($request->hasFile('buktiHadir')) {
                $buktiHadir = $request->file('buktiHadir');
                $namaFile = uniqid() . '_' . $buktiHadir->getClientOriginalName();
                $buktiHadir->storeAs('public', $namaFile);
                $fileLampiran[] .= $namaFile;
            }

            // Handle buktiNotulen
            if ($request->hasFile('buktiNotulen')) {
                $buktiNotulen = $request->file('buktiNotulen');
                $namaFile = uniqid() . '_' . $buktiNotulen->getClientOriginalName();
                $buktiNotulen->storeAs('public', $namaFile);
                $fileLampiran[] .= $namaFile;
            }

            // Handle lirs
            if ($request->hasFile('lirs')) {
                $lirs = $request->file('lirs');
                $namaFile = uniqid() . '_' . $lirs->getClientOriginalName();
                $lirs->storeAs('public', $namaFile);
                $fileLampiran[] .= $namaFile;
            }

            // Handle lainnya (multiple files)
            if ($request->hasFile('fileLainnya')) {
                foreach ($request->file('fileLainnya') as $fileLainnya) {
                    $namaFile = uniqid() . '_' . $fileLainnya->getClientOriginalName();
                    $fileLainnya->storeAs('public', $namaFile);
                    $fileLampiran[] .= $namaFile;
                }
            }

            $mhs_id = Mahasiswa::where('nim', Auth::user()->nim_nip)->first()->id;
            $tugasAkhir = TugasAkhir::create([
                'mahasiswa_id' => $mhs_id,
                'judul' => $request->judul,
                'no_hp' => $request->no_hp,
                'studi_kasus' => $request->studi_kasus,
                'metode' => $request->metode,
                'dosen_PA_id' => $request->dosenPA,
                'dosen_pembimbing_1_id' => $request->dospem1,
                'dosen_pembimbing_2_id' => $request->dospem2,
            ]);

            SeminarSidang::create([
                'mahasiswa_id' => $mhs_id,
                'notulen_mahasiswa_id' => $request->notulen_mahasiswa_id,
                'tugas_akhir_id' => $tugasAkhir->id,
                'dokumen_ta' => $dokumen_ta,
                'tahapan_ta' => 'Seminar Proposal',
                'file_lampiran' => json_encode($fileLampiran),
            ]);

            return redirect()->back()->with('success', 'Pendaftaran Berhasil, silahkan menunggu admin untuk memverifikasi pendaftaran kamu');
        }
    }

    public function viewDetail($slug)
    {
        $sempro = SeminarSidang::where('slug', $slug)->where('tahapan_ta', 'Seminar Proposal')->first();
        if ($sempro) {
            if (Auth::user()->id == $sempro->mahasiswa->user_id) {
                return view('mahasiswa.sempro.detail', compact('sempro'));
            } else {
                abort(403);
            }
        } else {
            abort(404);
        }
    }

    public function viewUbah($slug)
    {
        $dosen = Dosen::all()->except(1);
        $sempro = SeminarSidang::where('slug', $slug)->first();
        if ($sempro) {
            if ($sempro->status_pendaftaran == 'Diterima') {
                return redirect()->route('view.sempro');
            } else {
                if (Auth::user()->id == $sempro->mahasiswa->user_id) {
                    return view('mahasiswa.sempro.ubah', compact('sempro', 'dosen'));
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
            'no_hp' => 'required|max:255',
            'studi_kasus' => 'required|max:255',
            'metode' => 'required|max:255',
            'dosenPA' => 'required',
            'dospem1' => 'required',
            'dospem2' => 'required',
            'proposal' => 'file|mimes:pdf',
            'hadirSeminar' => 'file|mimes:jpeg,jpg,png,pdf',
            'buktiNotulensi' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx',
            'buktiLirs' => 'file|mimes:pdf',
            'lainnya.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('fail', 'Gagal melakukan perubahan, pastikan semua bagian formulir telah terisi dan file yang diupload sudah sesuai dengan format yang ditentukan');
        } else {
            $seminarSidang = SeminarSidang::where('slug', $slug)->first();
            $tugasAkhirId = $seminarSidang->tugas_akhir_id;
            $tugasAkhir = TugasAkhir::find($tugasAkhirId);
            $lampiran = json_decode($seminarSidang->file_lampiran);
            $dokumen_ta = '';
            for ($i = 0; $i < count($lampiran); $i++) {
                $fileLampiran[$i] = $lampiran[$i];
            }

            // Handle dokumenProposal
            if ($request->hasFile('proposal')) {
                Storage::delete($seminarSidang->dokumen_ta);
                $dokumenProposal = $request->file('proposal');
                $namaFile = uniqid() . '_' . $dokumenProposal->getClientOriginalName();
                $dokumenProposal->storeAs('public', $namaFile);
                $dokumen_ta = $namaFile;
                $seminarSidang->dokumen_ta = $dokumen_ta;
                $seminarSidang->save();
            }

            // Handle buktiHadir
            if ($request->hasFile('hadirSeminar')) {
                if (Storage::exists($lampiran[1])) {
                    Storage::delete($lampiran[1]);
                }
                $buktiHadir = $request->file('hadirSeminar');
                $namaFile = uniqid() . '_' . $buktiHadir->getClientOriginalName();
                $buktiHadir->storeAs('public', $namaFile);
                $fileLampiran[1] = $namaFile;
            }

            // Handle buktiNotulen
            if ($request->hasFile('buktiNotulensi')) {
                if (Storage::exists($lampiran[2])) {
                    Storage::delete($lampiran[2]);
                }
                $buktiNotulen = $request->file('buktiNotulensi');
                $namaFile = uniqid() . '_' . $buktiNotulen->getClientOriginalName();
                $buktiNotulen->storeAs('public', $namaFile);
                $fileLampiran[2] = $namaFile;
            }

            // Handle lirs
            if ($request->hasFile('buktiLirs')) {
                if (Storage::exists($lampiran[3])) {
                    Storage::delete($lampiran[3]);
                }
                $lirs = $request->file('buktiLirs');
                $namaFile = uniqid() . '_' . $lirs->getClientOriginalName();
                $lirs->storeAs('LIRS', $namaFile);
                $fileLampiran[3] = $namaFile;
            }

            // Handle lainnya (multiple files)
            if ($request->hasFile('lainnya')) {
                for ($i = 4; $i < count($lampiran); $i++) {
                    if (Storage::exists($lampiran[$i])) {
                        Storage::delete($lampiran[$i]);
                    }
                }
                $i = 4;
                foreach ($request->file('lainnya') as $fileLainnya) {
                    $namaFile = uniqid() . '_' . $fileLainnya->getClientOriginalName();
                    $fileLainnya->storeAs('public', $namaFile);
                    $fileLampiran[$i++] = $namaFile;
                }
            }

            $tugasAkhir->judul = $request->judul;
            $tugasAkhir->no_hp = $request->no_hp;
            $tugasAkhir->studi_kasus = $request->studi_kasus;
            $tugasAkhir->metode = $request->metode;
            $tugasAkhir->dosen_PA_id = $request->dosenPA;
            $tugasAkhir->dosen_pembimbing_1_id = $request->dospem1;
            $tugasAkhir->dosen_pembimbing_2_id = $request->dospem2;
            $tugasAkhir->save();

            $seminarSidang->file_lampiran = json_encode($fileLampiran);
            $seminarSidang->status_pendaftaran = 'Menunggu Verifikasi';
            $seminarSidang->save();

            return redirect()->route('view.sempro')->with('success', 'Pendaftaran Berhasil Diubah');
        }
    }

    public function delete($slug)
    {
        $seminarSidang = SeminarSidang::where('slug', $slug)->first();

        if ($seminarSidang) {
            if ($seminarSidang->status_kelulusan == 'Diterima') {
                return redirect()->route('view.sempro');
            } else {
                $lampiran = json_decode($seminarSidang->file_lampiran);
                for ($i = 0; $i < count($lampiran); $i++) {
                    $fileLampiran[$i] = $lampiran[$i];
                }

                if (Storage::exists($lampiran[1])) {
                    Storage::delete($lampiran[1]);
                }
                if (Storage::exists($lampiran[2])) {
                    Storage::delete($lampiran[2]);
                }
                if (Storage::exists($lampiran[3])) {
                    Storage::delete($lampiran[3]);
                }
                for ($i = 4; $i < count($lampiran); $i++) {
                    if (Storage::exists($lampiran[$i])) {
                        Storage::delete($lampiran[$i]);
                    }
                }
                Storage::delete($seminarSidang->dokumen_ta);
                $tugasAkhirId = $seminarSidang->tugas_akhir_id;
                SeminarSidang::withTrashed()
                    ->find($seminarSidang->id)
                    ->forceDelete();
                TugasAkhir::withTrashed()->find($tugasAkhirId)->forceDelete();

                return redirect()->route('view.sempro')->with('success', 'Data telah dihapus');
            }
        } else {
            return redirect()->route('view.sempro')->with('fail', 'Gagal menghapus data');
        }
    }

    public function uploadVideo(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
            'video' => 'required|url|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('fail', 'Gagal menyimpan, pastikan link sudah benar (contoh: https://www.youtube.com/linkVideoAnda)');
        } else {
            $seminarSidang = SeminarSidang::where('slug', $slug)->first();
            $seminarSidang->link_video = $request->video;
            $seminarSidang->save();

            $sempros = SeminarSidang::where('slug', $slug)->get();

            return redirect()->back()->withErrors($validator)->withInput()->with('success', 'Berhasil');
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
                $fileRevisi->storeAs('public', $namaFile);
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
                $fileRevisi->storeAs('public', $namaFile);
                $dokumen_revisi = $namaFile;
            }
            $seminarSidang->file_revisi = $dokumen_revisi;
            $seminarSidang->save();

            return redirect()->back()->withErrors($validator)->withInput()->with('success', 'Berhasil Diubah');
        }
    }

    public function viewHasil($slug)
    {
        $sempro = SeminarSidang::with('tugas_akhir')->where('slug', $slug)->where('tahapan_ta', 'Seminar Proposal')->first();
        if ($sempro) {
            if ($sempro->status_kelulusan == 'LULUS' || $sempro->status_kelulusan == 'TIDAK LULUS') {
                if (Auth::user()->id == $sempro->mahasiswa->user_id) {
                    return view('mahasiswa.sempro.hasil', compact('sempro'));
                } else {
                    abort(403, 'Privacy lah cuy');
                }
            } else {
                return redirect()->route('view.sempro');
            }
        } else {
            abort(404);
        }
    }
}
