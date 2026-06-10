<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nim_nip' => ['required'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
                    'name' => $request->name,
                    'nim_nip' => $request->nim_nip,
                    'email' => $request->email,
                    // 'status' => "Tidak Aktif",
                    'password' => Hash::make($request->password,),
                ]);        
        // if (is_numeric($request->nim_nip)) {
        //     $user = User::create([
        //         'name' => $request->name,
        //         'nim_nip' => $request->nim_nip,
        //         'email' => $request->email,
        //         // 'status' => "Tidak Aktif",
        //         'password' => Hash::make($request->password,),
        //     ]); 
        //     if (Dosen::where('nip_nidk', $user->nim_nip)->exists()) {
        //         Dosen::where('nip_nidk', $user->nim_nip)->update(['user_id' => $user->id]);
        //     } else {
        //         Dosen::create([
        //             'nip_nidk' => $user->nim_nip,
        //             'nama_dosen' => $user->name,
        //             'user_id' => $user->id,
        //         ]);
        //     }
        //     $user->addRole('dosen');
        // } elseif (is_string($request->nim_nip)){
        //     $user = User::create([
        //         'name' => $request->name,
        //         'nim_nip' => $request->nim_nip,
        //         'email' => $request->email,
        //         // 'status' => "Tidak Aktif",
        //         'password' => Hash::make($request->password,),
        //     ]); 
        //     if (Mahasiswa::where('nim', $user->nim_nip)->exists()) {
        //         Mahasiswa::where('nim', $user->nim_nip)->update(['user_id' => $user->id]);
        //     } 
            // else {
            //     Mahasiswa::create([
            //         'nim' => $user->nim_nip,
            //         'nama_lengkap' => $user->name,
            //         'user_id' => $user->id,
            //     ]);
            // }
            // $user->addRole('mahasiswa');
        // }

        event(new Registered($user));

        // Auth::login($user);

        return redirect(RouteServiceProvider::LOGIN)->with('pesan', 'Akun berhasil didaftarkan, hubungi admin untuk mengaktifkan akun');
    }

}
