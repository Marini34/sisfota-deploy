<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class DosenPengumumanController extends Controller
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
            $searchTerm = $this->convertToIndonesianMonth(request('search'));
            $pengumumans = Pengumuman::latest()
            ->where('roles_id', '2' || 'roles_id', null )
            ->orwhere('judul_pengumuman', 'like', '%' . request('search') . '%')
            ->orWhere('isi_pengumuman', 'like', '%' . request('search') . '%')
            ->orWhereRaw("DATE_FORMAT(created_at, '%d %M %Y') LIKE '%" . $searchTerm . "%'")
            ->get();
        } else {
            $pengumumans = Pengumuman::latest()->where('roles_id', '2')->orWhere('roles_id', null)->get();
        }
        return view('dosen.pengumuman.index', compact('pengumumans'));
    }
}
