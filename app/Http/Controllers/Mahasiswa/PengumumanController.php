<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Models\Pengumuman;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class PengumumanController extends Controller
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
            ->where('roles_id', '3' || 'roles_id', null)
            ->orwhere('judul_pengumuman', 'like', '%' . request('search') . '%')
            ->orWhere('isi_pengumuman', 'like', '%' . request('search') . '%')
            ->orWhereRaw("DATE_FORMAT(created_at, '%d %M %Y') LIKE '%" . $searchTerm . "%'")
            ->get();
        } else {
            $pengumumans = Pengumuman::latest()->where('roles_id', '3')->orWhere('roles_id', null)->get();
        }
        Log::channel('slack')->info(auth()->user()->name.' Mengakses halaman pengumuman!');
        return view('mahasiswa.pengumuman', compact('pengumumans'));
    }
}
