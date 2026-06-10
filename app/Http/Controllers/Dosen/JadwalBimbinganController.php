<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\JadwalBimbingan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalBimbinganController extends Controller
{
    private function getDosenId()
    {
        return Dosen::where('user_id', Auth::id())->value('id');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dosenId = $this->getDosenId();

        $data['title'] = 'Data Jadwal Bimbingan';

        $data['rows'] = JadwalBimbingan::where('dosen_id', $dosenId)
            ->orderByDesc('tanggal')
            ->orderBy('jam_mulai')
            ->get();

        return view('dosen.jadwal.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['title'] = 'Tambah Jadwal Bimbingan';

        return view('dosen.jadwal.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'kuota' => 'required|integer|min:1',
            'lokasi' => 'required',
        ]);

        JadwalBimbingan::create([
            'dosen_id' => $this->getDosenId(),
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'kuota' => $request->kuota,
            'lokasi' => $request->lokasi,
            'terisi' => 0,
        ]);

        return redirect()
            ->route('jadwal-bimbingan.index')
            ->with('success', 'Jadwal berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JadwalBimbingan $jadwalBimbingan)
    {
        $data['title'] = 'Edit Jadwal Bimbingan';
        $data['row'] = $jadwalBimbingan;

        return view('dosen.jadwal.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JadwalBimbingan $jadwalBimbingan)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'kuota' => 'required|integer|min:1',
            'lokasi' => 'required',
        ]);

        $jadwalBimbingan->update([
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'kuota' => $request->kuota,
            'lokasi' => $request->lokasi,
        ]);

        return redirect()
            ->route('jadwal-bimbingan.index')
            ->with('success', 'Jadwal berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JadwalBimbingan $jadwalBimbingan)
    {
        $jadwalBimbingan->delete();

        return redirect()
            ->route('jadwal-bimbingan.index')
            ->with('success', 'Jadwal berhasil dihapus');
    }
}
