<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class AdminPenggunaController extends Controller
{
    public function viewKelolaMahasiswa()
    {
        $mahasiswas = Mahasiswa::with('user')->has('user')->get();
        // $users = User::whereHas('roles', function($query) {
        //     $query->where('name', 'mahasiswa');
        // })->where('status', null)->get();               
        $users = User::where('status', null)->get();               
        return view('admin.pengguna.mahasiswa.index', compact('mahasiswas', 'users'));
    }

    public function terimaPengguna($id) {
        $user = User::where('id', $id)->first();
        if ($user) {
            $tahunMasuk = substr($user->nim_nip, 5, 2);
            $tahunMasuk = '20' . $tahunMasuk;
            $mahasiswa = Mahasiswa::create([
                            'nim' => $user->nim_nip,
                            'nama_lengkap' => $user->name,
                            'tahun_masuk' => $tahunMasuk,
                            'user_id' => $user->id,
                        ]);
            $user->addRole('mahasiswa');
            $user->status = "Aktif";
            $user->save();
            return redirect()->back()->with('success', 'Berhasil menambahkan pengguna mahasiswa baru');    
        } else {
            return redirect()->back()->with('fail', 'Gagal menambahkan pengguna mahasiswa baru');
        }
    }

    public function tolakPengguna($id) {
        $user = User::where('id', $id)->first();
        if ($user) {
            $user->delete();
            return redirect()->back()->with('success', 'Berhasil menghapus user');
        } else {
            return redirect()->back()->with('fail', 'Gagal menghapus user');
        }
    }

    public function viewDetailMahasiswa($id)
    {
        $mahasiswa = Mahasiswa::where('id', $id)->first();
        return view('admin.pengguna.mahasiswa.detail', compact('mahasiswa'));
    }

    public function storeMahasiswa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|max:255',
            'nim' => 'required|max:11',
            'jenis_kelamin' => 'required',
            'alamat' => 'max:255',
            'email' => 'required|email',
            'password' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('fail', 'Gagal menambahkan pengguna mahasiswa baru, pastikan semua bagian formulir telah terisi dan benar');
        } else {
            $user = User::create([
                'name' => $request->nama,
                'nim_nip' => $request->nim,
                'email' => $request->email,
                'status' => 'Aktif',
                'password' => Hash::make($request->password),
            ]);
            $tahunMasuk = substr($user->nim_nip, 5, 2);
            $tahunMasuk = '20' . $tahunMasuk;
            if (Mahasiswa::where('nim', $user->nim_nip)->exists()) {
                Mahasiswa::where('nim', $user->nim_nip)->update([
                    'user_id' => $user->id,
                    'tahun_masuk' => $tahunMasuk,
                    'slug' => Str::uuid()
                ]);
            } else {
                Mahasiswa::create([
                    'nim' => $user->nim_nip,
                    'nama_lengkap' => $user->name,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'tahun_masuk' => $tahunMasuk,
                    'alamat' => $request->alamat,
                    'user_id' => $user->id,
                ]);
            }
            $user->addRole('mahasiswa');
            return redirect()->back()->with('success', 'Berhasil menambahkan pengguna mahasiswa baru');
        }
    }

    public function viewEditMahasiswa($id)
    {
        $mahasiswa = Mahasiswa::where('id', $id)->first();
        return view('admin.pengguna.mahasiswa.ubah', compact('mahasiswa'));
    }

    public function storeEditMahasiswa(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|max:255',
            'nim' => 'required|max:11',
            'jenis_kelamin' => 'required',
            'status' => 'required',
            'alamat' => 'max:255',
            'email' => 'required|email',
            'password' => 'nullable|min:8|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('fail', 'Gagal mengubah data, pastikan semua format input telah benar');
        } else {
            $mahasiswa = Mahasiswa::where('id', $id)->first();
            $user = User::where('id', $mahasiswa->user_id)->first();

            $mahasiswa->nama_lengkap = $request->nama;
            $mahasiswa->nim = $request->nim;
            $mahasiswa->jenis_kelamin = $request->jenis_kelamin;
            $mahasiswa->tahun_masuk = '20' . substr($request->nim, 5, 2);
            $mahasiswa->alamat = $request->alamat;
            $mahasiswa->update();

            $user->name = $request->nama;
            $user->email = $request->email;
            $user->status = $request->status;
            if ($request->password !== null) {
                $user->password = Hash::make($request->password);
            }
            $user->update();

            return redirect()->route('admin.kelola.mahasiswa')->with('success', 'Berhasi mengubah pengguna mahasiswa');
        }
    }

    public function deleteMahasiswa($id)
    {
        $mahasiswa = Mahasiswa::where('id', $id)->first();
        $user = User::where('id', $mahasiswa->user_id)->first();
        $user->delete();
        $mahasiswa->delete();
        return redirect()->back()->with('success', 'Berhasi menghapus pengguna mahasiswa');
    }

    public function viewKelolaDosen()
    {
        $kaprodiId = DB::table('role_user')->where('role_id', 4)->pluck('user_id');
        $kaprodi = Dosen::where('user_id', $kaprodiId)->first();
        $dosens = Dosen::all()->except(1);
        $dekan = Dosen::where('id', 1)->first();
        return view('admin.pengguna.dosen.index', compact('dosens', 'kaprodi', 'dekan'));
    }

    public function storeDosen(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|max:255',
            'nip' => 'required|max:255',
            'email' => 'required|email',
            'password' => 'required|max:255',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->with('fail', 'Gagal menambahkan pengguna mahasiswa baru, pastikan semua bagian formulir telah terisi dan benar');
        } else {
            $user = User::create([
                'name' => $request->nama,
                'nim_nip' => $request->nip,
                'email' => $request->email,
                'status' => 'Aktif',
                'password' => Hash::make($request->password),
            ]);
            if (Dosen::where('nip_nidk', $user->nim_nip)->exists()) {
                Dosen::where('nip_nidk', $user->nim_nip)->update(['user_id' => $user->id]);
            } else {
                Dosen::create([
                    'nip_nidk' => $user->nim_nip,
                    'nama_dosen' => $user->name,
                    'user_id' => $user->id,
                ]);
            }
            $user->addRole('dosen');
            return redirect()->back()->with('success', 'Berhasi menambahkan pengguna mahasiswa baru');
        }
    }

    public function viewDetailDosen($id)
    {
        $dosen = Dosen::where('id', $id)->first();
        return view('admin.pengguna.dosen.detail', compact('dosen'));
    }

    public function viewEditDosen($id)
    {
        $dosen = Dosen::where('id', $id)->first();
        return view('admin.pengguna.dosen.ubah', compact('dosen'));
    }

    public function storeEditDosen(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|max:255',
            'nip' => 'required',
            'status' => 'required',
            'email' => 'required|email',
            'password' => 'nullable|min:8|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('fail', 'Gagal mengubah data, pastikan semua format input telah benar');
        } else {
            $dosen = Dosen::where('id', $id)->first();
            if ($dosen->user) {
                $user = User::where('id', $dosen->user_id)->first();
                $user->name = $request->nama;
                $user->email = $request->email;
                $user->nim_nip = $request->nip;
                $user->status = $request->status;
                if ($request->password !== null) {
                    $user->password = Hash::make($request->password);
                }
                $user->update();
            } else {
                $user = User::create([
                    'name' => $request->nama,
                    'nim_nip' => $request->nip,
                    'email' => $request->email,
                    'status' => 'Aktif',
                    'password' => Hash::make($request->password),
                ]);
                if (Dosen::where('nip_nidk', $user->nim_nip)->exists()) {
                    Dosen::where('nip_nidk', $user->nim_nip)->update(['user_id' => $user->id]);
                } else {
                    $dosen->user_id = $user->id;
                    $dosen->update();
                }
                $user->addRole('dosen');
            }

            $dosen->nama_dosen = $request->nama;
            $dosen->nip_nidk = $request->nip;
            $dosen->update();

            return redirect()->route('admin.kelola.dosen')->with('success', 'Berhasi mengubah pengguna dosen');
        }
    }

    public function deleteDosen($id)
    {
        $dosen = Dosen::where('id', $id)->first();
        $user = User::where('id', $dosen->user_id)->first();
        $dosen->delete();
        if ($user) {
            $user->delete();
        }
        return redirect()->back()->with('success', 'Berhasil menghapus pengguna Dosen');
    }

    public function gantiKaprodi(Request $request)
    {
        $kaprodi = DB::table('role_user')->where('role_id', 4)->pluck('user_id');
        DB::table('role_user')->whereIn('user_id', $kaprodi)->where('role_id', 4)->delete();

        $user_id = $request->kaprodi;
        DB::table('role_user')->insert([
            'role_id' => 4,
            'user_id' => $user_id,
            'user_type' => 'App\Models\User',
        ]);

        return redirect()->back()->with('success', 'Berhasil mengganti ketua program studi');
    }

    public function gantiDekan(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'namaDekan' => 'required|max:255',
            'nipDekan' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('fail', 'Gagal mengubah data, pastikan semua format input telah benar');
        } else {
            $dekan = Dosen::where('id', 1)->first();
            $dekan->nama_dosen = $request->namaDekan;
            $dekan->nip_nidk = $request->nipDekan;
            $dekan->save();

            return redirect()->back()->with('success', 'Berhasil mengganti Dekan Fakultas');
        }
    }
}
