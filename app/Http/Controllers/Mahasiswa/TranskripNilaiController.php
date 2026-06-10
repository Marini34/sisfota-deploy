<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Models\Makul;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\TranskripNilai;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\StatusTranskrip;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TranskripNilaiController extends Controller
{
    public function cariMakul() 
    {
        $data = Makul::where('nama_makul', 'LIKE', '%'.request('q').'%')->paginate();
        Log::channel('slack')->info(Auth::user()->name.' Mengakses halaman transkrip!');


        return response()->json($data);
    }

    public function storeTranskrip(Request $request) 
    {
        $mahasiswaId = Mahasiswa::where('nim', Auth::user()->nim_nip)->first()->id;
        $validator = Validator::make($request->all(), [
            'makul' => [
                'required',
                'numeric',
                Rule::unique('transkrip_nilais', 'makul_id')
                    ->where(function ($query) use ($mahasiswaId) {
                        return $query->where('mahasiswa_id', $mahasiswaId);
                    })],
            'nilai' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('fail', 'Gagal Menambahkan Transkrip Nilai');
        } else {
            TranskripNilai::create([
                'mahasiswa_id' => $mahasiswaId,
                'makul_id' => $request->makul,
                'nilai' => $request->nilai
            ]);

            return redirect()->back()->with('success', 'Berhasil Menambahkan Transkrip Nilai');
        }
    }

    public function storeMakul(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'kodeMakul' => 'required|max:30|unique:makuls,kode_makul,NULL,id',
            'namaMakul' => 'required|max:100|unique:makuls,nama_makul,NULL,id',
            'sks' => 'required',
            'nilaiBaru' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('fail', 'Gagal Menambahkan Transkrip Nilai');
        } else {
            $mahasiswaId = Mahasiswa::where('nim', Auth::user()->nim_nip)->first()->id;

            $makulBaru = Makul::create([
                'kode_makul' => $request->kodeMakul,
                'nama_makul' => $request->namaMakul,
                'sks' => $request->sks,
            ]);

            TranskripNilai::create([
                'mahasiswa_id' => $mahasiswaId,
                'makul_id' => $makulBaru->id,
                'nilai' => $request->nilaiBaru
            ]);

            return redirect()->back()->with('success', 'Berhasil Menambahkan Transkrip Nilai');
        }
    }

    public function konfirmasiTranskrip(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'fileTranskrip' => 'required|file|mimes:pdf'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('fail', 'Pastikan format dokumen sudah benar');
        } else {
            $mahasiswaId = Mahasiswa::where('nim', Auth::user()->nim_nip)->first()->id;
            $statusTranskrip = StatusTranskrip::where('mahasiswa_id', $mahasiswaId)->first();
            
            if ($request->hasFile('fileTranskrip')) {
                $transkrip = $request->file('fileTranskrip');
                $namaFile = uniqid() . '_' . $transkrip->getClientOriginalName();
                $transkrip->storeAs($namaFile);
            }
            
            if ($statusTranskrip) {
                if ($request->hasFile('fileTranskrip')) {
                    $statusTranskrip->file_transkrip = $namaFile;
                }
                $statusTranskrip->status_transkrip = "Menunggu Verifikasi";
                $statusTranskrip->jumlah_makul = $request->jumlahMakul;
                $statusTranskrip->jumlah_mutu = $request->jumlahMutu;
                $statusTranskrip->jumlah_sks = $request->jumlahSks;
                $statusTranskrip->ipk = $request->ipk;
                $statusTranskrip->save();
            } else {
                StatusTranskrip::create([
                    'mahasiswa_id' => $mahasiswaId,
                    'file_transkrip' => $namaFile,
                    'jumlah_makul' => $request->jumlahMakul,
                    'jumlah_mutu' => $request->jumlahMutu,
                    'jumlah_sks' => $request->jumlahSks,
                    'ipk' => $request->ipk,
                    'status_transkrip' => "Menunggu Verifikasi",
                ]);
            }
            return redirect()->back()->with('success', 'Berhasil Mengirim Transkrip');
        };
    }

    public function editTranskrip(Request $request, $uuid) 
    {
        $mahasiswaId = Mahasiswa::where('nim', Auth::user()->nim_nip)->first()->id;
        $statusTranskrip = StatusTranskrip::where('mahasiswa_id', $mahasiswaId)->first();
        $transkrip = TranskripNilai::where('uuid', $uuid)->first();
        $validator = Validator::make($request->all(), [
            'editMakul' => [
                'required',
                'numeric',
                Rule::unique('transkrip_nilais', 'makul_id')
                    ->ignore($transkrip->id)
                    ->where(function ($query) use ($mahasiswaId) {
                        return $query->where('mahasiswa_id', $mahasiswaId);
                    })],
            'editNilai' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('fail', 'Gagal Mengedit Transkrip Nilai');
        } else {
            if ($transkrip) {
                if ($statusTranskrip) {
                    $statusTranskrip->status_transkrip = "Menunggu Verifikasi";
                    $statusTranskrip->jumlah_makul = $request->jumlahMakul;
                    $statusTranskrip->jumlah_mutu = $request->jumlahMutu;
                    $statusTranskrip->jumlah_sks = $request->jumlahSks;
                    $statusTranskrip->ipk = $request->ipk;
                    $statusTranskrip->save();
                }
                $transkrip->makul_id = $request->editMakul;
                $transkrip->nilai = $request->editNilai;
                $transkrip->nilai = $request->editNilai;
                $transkrip->save();

                return redirect()->back()->with('success', 'Berhasil Mengedit Transkrip');
            } else {
                return redirect()->back()->with('fail', 'Gagal Mengedit Transkrip');
            }
        }
    }

    public function hapusTranskrip($uuid) 
    {
        $transkrip = TranskripNilai::where('uuid', $uuid)->first();
        if ($transkrip) {
            $transkrip->delete();
            return redirect()->back()->with('success', 'Berhasil Menghapus salah satu data transkrip');
        } else {
            return redirect()->back()->with('fail', 'Gagal Menghapus Transkrip');
        }
    }
}
