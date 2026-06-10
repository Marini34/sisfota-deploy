<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\SeminarSidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidasiNotulenController extends Controller
{
    //
    private function getDosenId()
    {
        return Dosen::where('user_id', Auth::id())->value('id');
    }

    public function index()
    {
        $dosenId = $this->getDosenId();

        $data['title'] = 'Validasi Notulen Mahasiswa';

        $data['rows'] = SeminarSidang::query()
            ->with(['mahasiswa', 'tugasAkhir', 'notulenMahasiswa'])
            ->whereHas('tugasAkhir', function ($q) use ($dosenId) {
                $q->where('dosen_PA_id', $dosenId);
            })
            ->whereNotNull('notulen_mahasiswa_id')
            ->orderByRaw("
                CASE
                    WHEN status_validasi_notulen_mhs = 'Menunggu' THEN 1
                    WHEN status_validasi_notulen_mhs = 'Ditolak' THEN 2
                    WHEN status_validasi_notulen_mhs = 'Disetujui' THEN 3
                    ELSE 4
                END
            ")
            ->orderByDesc('tanggal_pelaksanaan')
            ->get();

        return view('dosen.validasi-notulen.index', $data);
    }

    public function update(Request $request, $slug)
    {
        $dosenId = $this->getDosenId();

        $request->validate([
            'status_validasi_notulen_mhs' => 'required|in:Menunggu,Disetujui,Ditolak',
        ]);

        $row = SeminarSidang::query()
            ->where('slug', $slug)
            ->whereHas('tugasAkhir', function ($q) use ($dosenId) {
                $q->where('dosen_PA_id', $dosenId);
            })
            ->firstOrFail();

        $row->update([
            'status_validasi_notulen_mhs' => $request->status_validasi_notulen_mhs,
        ]);

        return redirect()
            ->route('dosen.validasi-notulen')
            ->with('success', 'Status validasi notulen berhasil diperbarui.');
    }

    public function detail($slug)
    {
        $dosenId = Dosen::where('user_id', Auth::id())->value('id');

        $row = SeminarSidang::with([
            'mahasiswa',
            'notulenMahasiswa',
            'tugasAkhir'
        ])
            ->where('slug', $slug)
            ->whereHas('tugasAkhir', function ($q) use ($dosenId) {
                $q->where('dosen_PA_id', $dosenId);
            })
            ->firstOrFail();

        return view('dosen.validasi-notulen.detail', compact('row'));
    }
}
