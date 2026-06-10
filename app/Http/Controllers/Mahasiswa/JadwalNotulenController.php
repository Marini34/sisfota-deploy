<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\SeminarSidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalNotulenController extends Controller
{
    public function index()
    {
        $mahasiswaId = Mahasiswa::where('nim', Auth::user()->nim_nip)->value('id');

        $rows = SeminarSidang::with(['mahasiswa', 'tugasAkhir'])
            ->where('notulen_mahasiswa_id', $mahasiswaId)
            ->orderByDesc('tanggal_pelaksanaan')
            ->orderByDesc('created_at')
            ->get();

        $title = "Jadwal Notulen Saya";

        return view('mahasiswa.notulen.index', compact('rows', 'title'));
    }

    public function edit($slug)
    {
        $mahasiswaId = Mahasiswa::where('nim', Auth::user()->nim_nip)->value('id');

        $row = SeminarSidang::with(['mahasiswa', 'tugasAkhir'])
            ->where('slug', $slug)
            ->where('notulen_mahasiswa_id', $mahasiswaId)
            ->firstOrFail();

        $title = "Isi Catatan Notulen";

        return view('mahasiswa.notulen.edit', compact('row', 'title'));
    }

    public function update(Request $request, $slug)
    {
        $request->validate([
            'catatan_penguji_1' => 'required|string',
            'catatan_penguji_2' => 'required|string',
        ]);

        $mahasiswaId = Mahasiswa::where('nim', Auth::user()->nim_nip)->value('id');

        $row = SeminarSidang::where('slug', $slug)
            ->where('notulen_mahasiswa_id', $mahasiswaId)
            ->firstOrFail();

        $row->update([
            'catatan_ta_notulen_mahasiswa_id' => json_encode([
                'catatan_penguji_1' => $request->catatan_penguji_1,
                'catatan_penguji_2' => $request->catatan_penguji_2,
            ]),
            'status_validasi_notulen_mhs' => 'Menunggu',
        ]);

        return redirect()
            ->route('jadwal-notulen')
            ->with('success', 'Catatan notulen berhasil disimpan.');
    }
}
