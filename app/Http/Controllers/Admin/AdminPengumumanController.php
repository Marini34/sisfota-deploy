<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Pengumuman;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DateTime;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminPengumumanController extends Controller
{
    protected function convertToIndonesianMonth($dateString)
    {
        $indonesianMonths = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $englishMonths = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        return str_ireplace($indonesianMonths, $englishMonths, $dateString);
    }

    public function viewPengumuman()
    {
        if (request('search')) {
            if (strcasecmp(request('search'), 'mahasiswa') === 0) {
                $roles = 3;
            } elseif (strcasecmp(request('search'), 'dosen') === 0) {
                $roles = 2;
            } else {
                $roles = '';
            }
            $searchTerm = $this->convertToIndonesianMonth(request('search'));
            $pengumumans = Pengumuman::with('roles')->latest()
                ->where('judul_pengumuman', 'like', '%' . request('search') . '%')
                ->orWhere('isi_pengumuman', 'like', '%' . request('search') . '%')
                ->orWhere('roles_id', $roles)
                ->orWhereRaw("DATE_FORMAT(created_at, '%d %M %Y') LIKE '%" . $searchTerm . "%'")
                ->get();
        } else {
            $pengumumans = Pengumuman::with('roles')->latest()->get();
        }

        return view('admin.pengumuman.index', compact('pengumumans'));
    }

    public function storePengumuman(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|max:255',
            'isi' => 'required',
            'fileLampiran.*' => 'file',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('fail', 'Gagal menambah pengumuman baru');
        } else {
            $fileLampiran = [];
            if ($request->hasFile('fileLampiran')) {
                foreach ($request->file('fileLampiran') as $fileLainnya) {
                    $namaFile = $fileLainnya->getClientOriginalName();
                    $fileLainnya->storeAs($namaFile);
                    $fileLampiran[] .= $namaFile;
                }
            }

            $pengumuman = Pengumuman::create([
                'judul_pengumuman' => $request->judul, 
                'isi_pengumuman' => $request->isi,
                'roles_id' => $request->roles,
                'lampiran' => json_encode($fileLampiran),

            ]);

            $pengumuman->updated_at = null;
            $pengumuman->save(['timestamps' => false]);
            return redirect()->back()->with('success', 'Berhasil menambah pengumuman');
        }
    }

    public function viewEdit($id)
    {
        $pengumuman = Pengumuman::where('id', $id)->first();
        return view('admin.pengumuman.edit', compact('pengumuman'));
    }

    public function editPengumuman(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|max:255',
            'isiPengumuman' => 'required',
            'fileLampiran.*' => 'file',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('fail', 'Gagal mengubah pengumuman baru');
        } else {
            $pengumuman = Pengumuman::where('id', $id)->first();
            if($pengumuman->lampiran != null) {
                $lampiran = json_decode($pengumuman->lampiran);
                for ($i = 0; $i < count($lampiran); $i++) {
                    $fileLampiran[$i] = $lampiran[$i];
                }
                if ($request->hasFile('fileLampiran')) {
                    for ($i = 0; $i < count($lampiran); $i++) {
                        if (Storage::exists($lampiran[$i])) {
                            Storage::delete($lampiran[$i]);
                        }
                    }
                    $i = 0;
                    foreach ($request->file('fileLampiran') as $lampiranPengumuman) {
                        $namaFile = $lampiranPengumuman->getClientOriginalName();
                        $lampiranPengumuman->storeAs($namaFile);
                        $fileLampiran[$i++] = $namaFile;
                    }
                }
            } else {
                $fileLampiran = [];
                if ($request->hasFile('fileLampiran')) {
                    foreach ($request->file('fileLampiran') as $fileLainnya) {
                        $namaFile = $fileLainnya->getClientOriginalName();
                        $fileLainnya->storeAs($namaFile);
                        $fileLampiran[] .= $namaFile;
                    }
                    $pengumuman->lampiran = json_encode($fileLampiran);
                }
            }

            
            $pengumuman->judul_pengumuman = $request->judul;
            $pengumuman->roles_id = $request->roles;
            $pengumuman->isi_pengumuman = $request->isiPengumuman;
            $pengumuman->updated_at = now();
            $pengumuman->save();
            return redirect()->route('admin.pengumuman')->with('success', 'Berhasil mengubah pengumuman');
        }
    }

    public function deletePengumuman($id) 
    {
        $pengumuman = Pengumuman::where('id', $id)->first();
        if($pengumuman){
            $lampiran = json_decode($pengumuman->lampiran);
            for ($i = 0; $i < count($lampiran); $i++) {
                $fileLampiran[$i] = $lampiran[$i];
                if (Storage::exists($lampiran[$i])) {
                    Storage::move($lampiran[$i], 'Sampah/File Lampiran/' . $lampiran[$i]);
                }
            }
            $pengumuman->delete();
            return redirect()->back()->with('success', 'Berhasil menghapus pengumuman');
        } else {
            return redirect()->back()->with('fail', 'Gagal menghapus pengumuman');
        }
    }
}
