<?php

namespace App\Http\Controllers\Kaprodi;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\ParameterPenilaian;
use App\Http\Controllers\Controller;
use App\Models\PassingGrade;
use App\Models\Penilaian;
use Illuminate\Support\Facades\Validator;

class KaprodiParameterController extends Controller
{
    public function viewParameterSempro()
    {
        $passingGrade = PassingGrade::where('tahapan_ta', 'Seminar Proposal')->first();
        $parameters = ParameterPenilaian::where('tahapan_ta', 'Seminar Proposal')->get();
        if ($parameters->count()) {
            $totalPersentase = $parameters->sum('persentase'); 
        } else {
            $totalPersentase = 0;
        } 
        return view('admin.parameter.sempro.index', compact('parameters', 'totalPersentase', 'passingGrade'));
    }

    public function editPassingGradeSempro(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'passingGrade' => 'required|numeric'
        ]);
        $passingGrade = PassingGrade::where('tahapan_ta', 'Seminar Proposal')->first();
        if ($passingGrade) {
            $passingGrade->nilai = $request->passingGrade;
            $passingGrade->save();
            return redirect()->back()->with('success', 'Berhasil mengubah nilai standar kelulusan sempro');
        } else {
            return redirect()->back()->with('fail', 'Gagal mengubah nilai standar kelulusan sempro');
        }
    }

    public function storeParameterSempro(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|max:255|unique:parameter_penilaian,nama_parameter,NULL,id,tahapan_ta,Seminar Proposal',
            'deskripsi' => 'required',
            'persentase' => 'required|numeric'
        ]);
        $parameters = ParameterPenilaian::where('tahapan_ta', 'Seminar Proposal')->get();
        $totalPersentase = $parameters->sum('persentase');
        if ($validator->fails()) {
            return redirect()->back()->with('fail', 'Gagal menambahkan parameter penilaian');
        } elseif ($totalPersentase + $request->persentase > 100) {
            return redirect()->back()->with('fail', 'Gagal menambahkan parameter penilaian, total bobot tidak boleh lebih dari 100% (bobot tersisa '. 100 - $totalPersentase .'%)');
        } else {
            ParameterPenilaian::create([
                'tahapan_ta' => 'Seminar Proposal',
                'nama_parameter' => $request->nama,
                'deskripsi_parameter' => $request->deskripsi,
                'persentase' => $request->persentase
            ]);

            return redirect()->back()->with('success', 'Berhasil menambahkan parameter penilaian');
        }

    }

    public function viewEditParameterSempro($id)
    {
        $parameters = ParameterPenilaian::where('tahapan_ta', 'Seminar Proposal')->get();
        $parameter = ParameterPenilaian::where('id', $id)->first();
        if ($parameters->count()) {
            $totalPersentase = $parameters->sum('persentase') - $parameter->persentase; 
        } else {
            $totalPersentase = 0;
        } 
        return view('admin.parameter.sempro.edit', compact('parameters','parameter', 'totalPersentase'));
    }

    public function editParameterSempro(Request $request, $id) 
    {
        $validator = Validator::make($request->all(), [
            'editNama' => 'required|max:255', Rule::unique('parameter_penilaian')->ignore($id),
            'editDeskripsi' => 'required',
            'editPersentase' => 'required|numeric'
        ]);
        $parameter = ParameterPenilaian::where('id', $id)->first();
        $parameters = ParameterPenilaian::where('tahapan_ta', 'Seminar Proposal')->get();
        $totalPersentase = $parameters->sum('persentase') - $parameter->persentase;
        if ($validator->fails()) {
            return redirect()->back()->with('fail', 'Gagal mengedit parameter penilaian');
        } elseif ($totalPersentase + $request->EditPersentase > 100) {
            return redirect()->back()->with('fail', 'Gagal memngedit parameter penilaian, total bobot tidak boleh lebih dari 100% (bobot tersisa '. $totalPersentase .')');
        } else {
            $parameter->nama_parameter = $request->editNama;
            $parameter->deskripsi_parameter = $request->editDeskripsi;
            $parameter->persentase = $request->editPersentase;
            $parameter->update();

            return redirect()->route('admin.parameter.sempro')->with('success', 'Berhasil mengedit parameter penilaian');
        }

    }

    public function delete($id) 
    {
        $parameter = ParameterPenilaian::where('id', $id)->first();
        if ($parameter) {
            $penilaians = Penilaian::where('parameter_penilaian_id', $parameter->id)->get();
            foreach ($penilaians as $penilaian) {
                $penilaian->delete();
            }
            ParameterPenilaian::find($parameter->id)->delete();
            return redirect()->back()->with('success', 'Berhasil menghapus parameter penilaian');
        } else {
            return redirect()->back()->with('fail', 'Gagal meghapus parameter penilaian');
        }
    }

    public function viewParameterSemhas()
    {
        $passingGrade = PassingGrade::where('tahapan_ta', 'Seminar Hasil')->first();
        $parameters = ParameterPenilaian::where('tahapan_ta', 'Seminar Hasil')->get();
        if ($parameters->count()) {
            $totalPersentase = $parameters->sum('persentase'); 
        } else {
            $totalPersentase = 0;
        } 
        return view('admin.parameter.semhas.index', compact('parameters', 'totalPersentase', 'passingGrade'));
    }

    public function editPassingGradeSemhas(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'passingGrade' => 'required|numeric'
        ]);
        $passingGrade = PassingGrade::where('tahapan_ta', 'Seminar Hasil')->first();
        if ($passingGrade) {
            $passingGrade->nilai = $request->passingGrade;
            $passingGrade->save();
            return redirect()->back()->with('success', 'Berhasil mengubah nilai standar kelulusan semhas');
        } else {
            return redirect()->back()->with('fail', 'Gagal mengubah nilai standar kelulusan semhas');
        }
    }

    public function storeParameterSemhas(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|max:255|unique:parameter_penilaian,nama_parameter,NULL,id,tahapan_ta,Seminar Hasil',
            'deskripsi' => 'required',
            'persentase' => 'required|numeric'
        ]);
        $parameters = ParameterPenilaian::where('tahapan_ta', 'Seminar Hasil')->get();
        $totalPersentase = $parameters->sum('persentase');
        if ($validator->fails()) {
            return redirect()->back()->with('fail', 'Gagal menambahkan parameter penilaian');
        } elseif ($totalPersentase + $request->persentase > 100) {
            return redirect()->back()->with('fail', 'Gagal menambahkan parameter penilaian, total bobot tidak boleh lebih dari 100% (bobot tersisa '. 100 - $totalPersentase .'%)');
        } else {
            ParameterPenilaian::create([
                'tahapan_ta' => 'Seminar Hasil',
                'nama_parameter' => $request->nama,
                'deskripsi_parameter' => $request->deskripsi,
                'persentase' => $request->persentase
            ]);

            return redirect()->back()->with('success', 'Berhasil menambahkan parameter penilaian');
        }

    }

    public function viewEditParameterSemhas($id)
    {
        $parameters = ParameterPenilaian::where('tahapan_ta', 'Seminar Hasil')->get();
        $parameter = ParameterPenilaian::where('id', $id)->first();
        if ($parameters->count()) {
            $totalPersentase = $parameters->sum('persentase') - $parameter->persentase; 
        } else {
            $totalPersentase = 0;
        } 
        return view('admin.parameter.semhas.edit', compact('parameters','parameter', 'totalPersentase'));
    }

    public function editParameterSemhas(Request $request, $id) 
    {
        $validator = Validator::make($request->all(), [
            'editNama' => 'required|max:255', Rule::unique('parameter_penilaian')->ignore($id),
            'editDeskripsi' => 'required',
            'editPersentase' => 'required|numeric'
        ]);
        $parameter = ParameterPenilaian::where('id', $id)->first();
        $parameters = ParameterPenilaian::where('tahapan_ta', 'Seminar Hasil')->get();
        $totalPersentase = $parameters->sum('persentase') - $parameter->persentase;
        if ($validator->fails()) {
            return redirect()->back()->with('fail', 'Gagal mengedit parameter penilaian');
        } elseif ($totalPersentase + $request->EditPersentase > 100) {
            return redirect()->back()->with('fail', 'Gagal memngedit parameter penilaian, total bobot tidak boleh lebih dari 100% (bobot tersisa '. $totalPersentase .')');
        } else {
            $parameter->nama_parameter = $request->editNama;
            $parameter->deskripsi_parameter = $request->editDeskripsi;
            $parameter->persentase = $request->editPersentase;
            $parameter->update();

            return redirect()->route('admin.parameter.semhas')->with('success', 'Berhasil mengedit parameter penilaian');
        }

    }

    public function viewParameterSidang()
    {
        $passingGrade = PassingGrade::where('tahapan_ta', 'Sidang Akhir')->first();
        $skripsiParameters = ParameterPenilaian::where('tahapan_ta', 'Skripsi')->get();
        $artikelParameters = ParameterPenilaian::where('tahapan_ta', 'Artikel')->get();
        $presentasiParameters = ParameterPenilaian::where('tahapan_ta', 'Presentasi')->get();
        if ($skripsiParameters->count()) {
            $totalPersentaseSkripsi = $skripsiParameters->sum('persentase'); 
        } else {
            $totalPersentaseSkripsi = 0;
        }
        
        if ($artikelParameters->count()) {
            $totalPersentaseArtikel = $artikelParameters->sum('persentase'); 
        } else {
            $totalPersentaseArtikel = 0;
        }

        if ($presentasiParameters->count()) {
            $totalPersentasePresentasi = $presentasiParameters->sum('persentase'); 
        } else {
            $totalPersentasePresentasi = 0;
        }

        return view('admin.parameter.sidang.index', compact('skripsiParameters', 'artikelParameters', 'presentasiParameters', 'totalPersentaseSkripsi', 'totalPersentaseArtikel', 'totalPersentasePresentasi', 'passingGrade'));
    }

    public function storeParameterSkripsi(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'namaSkripsi' => 'required|max:255|unique:parameter_penilaian,nama_parameter,NULL,id,tahapan_ta,Skripsi',
            'deskripsiSkripsi' => 'required',
            'persentaseSkripsi' => 'required|numeric',
        ]);

        $parameters = ParameterPenilaian::where('tahapan_ta', 'Skripsi')->get();
        $totalPersentase = $parameters->sum('persentase');
        if ($validator->fails()) {
            return redirect()->back()->with('fail', 'Gagal menambahkan parameter penilaian');
        } elseif ($totalPersentase + $request->persentase > 100) {
            return redirect()->back()->with('fail', 'Gagal menambahkan parameter penilaian, total bobot tidak boleh lebih dari 100% (bobot tersisa '. 100 - $totalPersentase .'%)');
        } else {
            ParameterPenilaian::create([
                'tahapan_ta' => 'Skripsi',
                'nama_parameter' => $request->namaSkripsi,
                'deskripsi_parameter' => $request->deskripsiSkripsi,
                'persentase' => $request->persentaseSkripsi
            ]);

            return redirect()->back()->with('success', 'Berhasil menambahkan parameter penilaian');
        }
    }

    public function viewEditParameterSidang($id)
    {
        $parameter = ParameterPenilaian::where('id', $id)->first();
        $tahapanTa = $parameter->tahapan_ta;
        $parameters = ParameterPenilaian::where('tahapan_ta', $tahapanTa)->get();
        if ($parameters->count()) {
            $totalPersentase = $parameters->sum('persentase') - $parameter->persentase; 
        } else {
            $totalPersentase = 0;
        } 
        return view('admin.parameter.sidang.edit', compact('parameters','parameter', 'totalPersentase'));
    }

    public function editParameterSidang(Request $request, $id) 
    {
        $validator = Validator::make($request->all(), [
            'editNama' => 'required|max:255', Rule::unique('parameter_penilaian')->ignore($id),
            'editDeskripsi' => 'required',
            'editPersentase' => 'required|numeric'
        ]);
        $parameter = ParameterPenilaian::where('id', $id)->first();
        $parameters = ParameterPenilaian::where('tahapan_ta', $parameter->tahapan_ta)->get();
        $totalPersentase = $parameters->sum('persentase') - $parameter->persentase;
        if ($validator->fails()) {
            return redirect()->back()->with('fail', 'Gagal mengedit parameter penilaian');
        } elseif ($totalPersentase + $request->EditPersentase > 100) {
            return redirect()->back()->with('fail', 'Gagal memngedit parameter penilaian, total bobot tidak boleh lebih dari 100% (bobot tersisa '. $totalPersentase .')');
        } else {
            $parameter->nama_parameter = $request->editNama;
            $parameter->deskripsi_parameter = $request->editDeskripsi;
            $parameter->persentase = $request->editPersentase;
            $parameter->update();

            return redirect()->route('admin.parameter.sidang')->with('success', 'Berhasil mengedit parameter penilaian');
        }
    }

    public function editPassingGradeSidang(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'passingGrade' => 'required|numeric'
        ]);
        $passingGrade = PassingGrade::where('tahapan_ta', 'Sidang Akhir')->first();
        if ($passingGrade) {
            $passingGrade->nilai = $request->passingGrade;
            $passingGrade->save();
            return redirect()->back()->with('success', 'Berhasil mengubah nilai standar kelulusan Sidang Akhir');
        } else {
            return redirect()->back()->with('fail', 'Gagal mengubah nilai standar kelulusan sidang akhir');
        }
    }

    public function storeParameterArtikel(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'namaArtikel' => 'required|max:255|unique:parameter_penilaian,nama_parameter,NULL,id,tahapan_ta,Artikel',
            'deskripsiArtikel' => 'required',
            'persentaseArtikel' => 'required|numeric'
        ]);

        $parameters = ParameterPenilaian::where('tahapan_ta', 'Artikel')->get();
        $totalPersentase = $parameters->sum('persentase');
        if ($validator->fails()) {
            return redirect()->back()->with('fail', 'Gagal menambahkan parameter penilaian Artikel Ilmiah');
        } elseif ($totalPersentase + $request->persentase > 100) {
            return redirect()->back()->with('fail', 'Gagal menambahkan parameter penilaian, total bobot tidak boleh lebih dari 100% (bobot tersisa '. 100 - $totalPersentase .'%)');
        } else {
            ParameterPenilaian::create([
                'tahapan_ta' => 'Artikel',
                'nama_parameter' => $request->namaArtikel,
                'deskripsi_parameter' => $request->deskripsiArtikel,
                'persentase' => $request->persentaseArtikel
            ]);

            return redirect()->back()->with('success', 'Berhasil menambahkan parameter penilaian');
        }
    }

    public function storeParameterPresentasi(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'namaPresentasi' => 'required|max:255|unique:parameter_penilaian,nama_parameter,NULL,id,tahapan_ta,Artikel',
            'deskripsiPresentasi' => 'required',
            'persentasePresentasi' => 'required|numeric'
        ]);
        $parameters = ParameterPenilaian::where('tahapan_ta', 'Artikel')->get();
        $totalPersentase = $parameters->sum('persentase');
        if ($validator->fails()) {
            return redirect()->back()->with('fail', 'Gagal menambahkan parameter penilaian');
        } elseif ($totalPersentase + $request->persentase > 100) {
            return redirect()->back()->with('fail', 'Gagal menambahkan parameter penilaian, total bobot tidak boleh lebih dari 100% (bobot tersisa '. 100 - $totalPersentase .'%)');
        } else {
            ParameterPenilaian::create([
                'tahapan_ta' => 'Presentasi',
                'nama_parameter' => $request->namaPresentasi,
                'deskripsi_parameter' => $request->deskripsiPresentasi,
                'persentase' => $request->persentasePresentasi
            ]);

            return redirect()->back()->with('success', 'Berhasil menambahkan parameter penilaian');
        }
    }
}
