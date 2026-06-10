<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Notifikasi;
use App\Models\SeminarSidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KaprodiPenjadwalanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['title'] = 'Penentuan Penguji';

        $data['penjadwalanData'] = SeminarSidang::query()
            ->with(['mahasiswa', 'tugasAkhir'])
            ->where('tahapan_ta', 'Seminar Proposal')
            ->where('status_pendaftaran', 'Diterima')
            ->whereNotNull('tugas_akhir_id')
            ->whereHas('tugasAkhir', function ($q) {
                $q->whereNull('deleted_at');
            })
            // 🔥 URUTKAN: yang belum ada tanggal dulu
            ->orderByRaw('tanggal_pelaksanaan IS NOT NULL')
            // ->orderBy('tanggal_pelaksanaan', 'asc')
            // ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return view('kaprodi.penjadwalan.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['title'] = 'Tentukan Penguji Mahasiswa';

        $data['ss'] = SeminarSidang::with(['mahasiswa', 'tugasAkhir'])
            ->findOrFail($id);

        // list dosen untuk dropdown penguji
        $excludeIds = collect([
            $data['ss']->tugasAkhir->dosen_pembimbing_1_id,
            $data['ss']->tugasAkhir->dosen_pembimbing_2_id,
        ])->filter();

        $data['dosenList'] = Dosen::query()
            ->whereNotIn('id', $excludeIds)
            ->orderBy('nama_dosen')
            ->get();

        return view('kaprodi.penjadwalan.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $ss = SeminarSidang::with('tugasAkhir')->findOrFail($id);
        $validated = $request->validate([
            'dosen_penguji_1_id' => ['required', 'exists:dosen,id'],
            'dosen_penguji_2_id' => ['required', 'exists:dosen,id', 'different:dosen_penguji_1_id'],

            // 'tanggal_pelaksanaan' => ['required', 'date'],
            // 'jam_pelaksanaan' => ['required'], // kalau tipe time, bisa: date_format:H:i
            // 'tempat_pelaksanaan' => ['required', 'string', 'max:255'],
        ], [
            'dosen_penguji_2_id.different' => 'Penguji 2 tidak boleh sama dengan Penguji 1.',
        ]);

        DB::transaction(function () use ($validated, $ss) {
            // 1) update tugas_akhir (penguji)
            $ss->tugasAkhir()->update([
                'dosen_penguji_1_id' => $validated['dosen_penguji_1_id'],
                'dosen_penguji_2_id' => $validated['dosen_penguji_2_id'],
            ]);

            // 2) update seminar_sidang (jadwal)
            // $ss->update([
            //     'tanggal_pelaksanaan' => $validated['tanggal_pelaksanaan'],
            //     'jam_pelaksanaan' => $validated['jam_pelaksanaan'],
            //     'tempat_pelaksanaan' => $validated['tempat_pelaksanaan'],
            // ]);
        });
        // 🔥 reload biar data terbaru keambil
        $ss->refresh();

        // 🔥 baru kirim notifikasi
        $this->kirimNotifikasiSeminar($ss);

        return redirect()
            ->route('penjadwalan.index')
            ->with('success', 'Penjadwalan berhasil disimpan.');
    }

    private function kirimNotifikasiSeminar($ss)
    {
        // 🔥 pastikan relasi tersedia
        $ss->loadMissing(['tugasAkhir', 'mahasiswa']);

        $ta = $ss->tugasAkhir;
        $mhs = $ss->mahasiswa;

        // ❗ safety check
        if (! $ta || ! $mhs) {
            return;
        }

        // 🔥 kumpulkan dosen terkait
        $dosenIds = collect([
            $ta->dosen_pembimbing_1_id,
            $ta->dosen_pembimbing_2_id,
            $ta->dosen_penguji_1_id,
            $ta->dosen_penguji_2_id,
        ])
            ->filter()   // buang null
            ->unique();  // hilangkan duplikat

        // 🔥 format notif (lebih rapi)
        $keterangan = trim(
            "Jadwal {$ss->tahapan_ta}\n".
                "Tanggal : {$ss->tanggal_pelaksanaan}\n".
                "Jam     : {$ss->jam_pelaksanaan}\n".
                "Tempat  : {$ss->tempat_pelaksanaan}\n\n".
                "Mahasiswa: {$mhs->nama_lengkap} ({$mhs->nim})"
        );

        // 🔥 insert notif
        foreach ($dosenIds as $dosenId) {
            Notifikasi::create([
                'dosen_id' => $dosenId,
                'ref_id' => $ss->id,
                'keterangan' => $keterangan,
                'dibaca' => false,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
