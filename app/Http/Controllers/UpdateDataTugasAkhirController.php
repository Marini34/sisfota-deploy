<?php

namespace App\Http\Controllers;

use App\Models\TugasAkhir;
use App\Models\SeminarSidang;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TugasAkhirExport;

class UpdateDataTugasAkhirController extends Controller
{
    public function index(Request $request)
    {
        $query = Mahasiswa::query()
            ->leftJoin('tugas_akhir', 'mahasiswa.id', '=', 'tugas_akhir.mahasiswa_id')
            ->leftJoin('seminar_sidang', function($join) {
                $join->on('tugas_akhir.id', '=', 'seminar_sidang.tugas_akhir_id')
                     ->where('seminar_sidang.tahapan_ta', 'Sidang Akhir')
                     ->where('seminar_sidang.status_pendaftaran', 'Diterima');
            })
            ->select(
                'mahasiswa.id as mahasiswa_id', // Key for edit link
                'tugas_akhir.id as tugas_akhir_id',
                'mahasiswa.nim', 
                'mahasiswa.nama_lengkap', 
                'tugas_akhir.judul',
                'seminar_sidang.dokumen_ta', 
                'seminar_sidang.id as seminar_id'
            )
            ->orderBy('mahasiswa.nim', 'asc');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('mahasiswa.nim', 'like', "%{$search}%")
                  ->orWhere('mahasiswa.nama_lengkap', 'like', "%{$search}%");
            });
        }

        $data = $query->paginate(10);

        return view('update-data-ta.index', compact('data'));
    }

    public function edit($id)
    {
        // $id is now mahasiswa_id
        $mahasiswa = Mahasiswa::findOrFail($id);
        
        // Find existing TA or create instance
        $ta = TugasAkhir::where('mahasiswa_id', $id)->first();
        
        // Prepare data object for view to mimic previous structure or pass separate
        // Let's pass a custom object or just the models.
        // The view expects $data as an object with properties.
        
        $data = (object) [
            'id' => $mahasiswa->id, // Passing mahasiswa_id for the form action
            'nim' => $mahasiswa->nim,
            'nama_lengkap' => $mahasiswa->nama_lengkap,
            'judul' => $ta ? $ta->judul : null,
            'abstrak' => $ta ? $ta->abstrak : null,
            'dokumen_ta' => null
        ];

        if ($ta) {
            $seminar = SeminarSidang::where('tugas_akhir_id', $ta->id)
                        ->where('tahapan_ta', 'Sidang Akhir')
                        // ->where('status_pendaftaran', 'Diterima') // Maybe we don't enforce this for viewing/editing?
                        ->first();
            if ($seminar) {
                $data->dokumen_ta = $seminar->dokumen_ta;
            }
        }

        return view('update-data-ta.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        // $id is mahasiswa_id
        $request->validate([
            'judul' => 'required|string|max:255',
            'abstrak' => 'nullable|string',
            'file_ta' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $mahasiswa = Mahasiswa::findOrFail($id);

        // Update or Create Tugas Akhir
        $ta = TugasAkhir::where('mahasiswa_id', $mahasiswa->id)->first();

        if ($ta) {
            $ta->update([
                'judul' => $request->judul,
                'abstrak' => $request->abstrak,
            ]);
        } else {
            $ta = TugasAkhir::create([
                'mahasiswa_id' => $mahasiswa->id,
                'judul' => $request->judul,
                'abstrak' => $request->abstrak,
            ]);
        }
        
        // Ensure slug is set if it was a new record or title changed (simple check)
        if (!$ta->slug) {
             $ta->slug = \Illuminate\Support\Str::slug($request->judul) . '-' . $ta->id;
             $ta->save();
        }

        if ($request->hasFile('file_ta')) {
            // We need a SeminarSidang record to attach the file to. 
            // If it doesn't exist, should we create it?
            // The requirement implies managing the file.
            // If specific "Sidang Akhir" record doesn't exist, we might need to create it 
            // OR find any existing one? 
            // Let's assume we create/update 'Sidang Akhir' record.
            
            $path = $request->file('file_ta')->store('dokumen_ta', 'public');

            $seminar = SeminarSidang::where('tugas_akhir_id', $ta->id)
                ->where('tahapan_ta', 'Sidang Akhir')
                ->first();

            if ($seminar) {
                $seminar->update([
                    'mahasiswa_id' => $mahasiswa->id,
                    'status_pendaftaran' => 'Diterima',
                    'dokumen_ta' => $path,
                ]);
            } else {
                SeminarSidang::create([
                    'tugas_akhir_id' => $ta->id,
                    'tahapan_ta' => 'Sidang Akhir',
                    'mahasiswa_id' => $mahasiswa->id,
                    'status_pendaftaran' => 'Diterima',
                    'dokumen_ta' => $path,
                ]);
            }
        }

        return redirect()->route('update-data-ta.index')->with('success', 'Data Tugas Akhir berhasil diperbarui.');
    }

    public function export()
    {
        // Simple export implementation inline or via class
        return Excel::download(new TugasAkhirExport, 'data_tugas_akhir.xlsx');
    }
}
