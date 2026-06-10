<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SocialiteController extends Controller
{
    public function redirect()
{
    try {
        return Socialite::driver('google')
            ->stateless()
            ->redirect();
    } catch (\Exception $e) {
        Log::error('Google redirect error: ' . $e->getMessage());
        return redirect('/login')->with([
            'error_title' => 'Gagal Login',
            'error_message' => 'Terjadi kesalahan saat mengarahkan ke Google. Silakan coba lagi.'
        ]);
    }
}

public function callback()
{
    try {
        $socialUser = Socialite::driver('google')->stateless()->user();

        if (empty($socialUser->email)) {
            return redirect('/login')->with([
                'error_type'  => 'google',
                'error_title' => 'Gagal Login',
                'error_text'  => 'Akun Google tidak menyediakan alamat email.'
            ]);
        }

        $user = User::where('email', $socialUser->email)->first();




        if (!$user) {
            return redirect('/login')->with([
                'error_type'  => 'google',
                'error_title' => 'Akun Google Belum Terdaftar',
                'error_text'  => 'Email Google Anda belum terdaftar di sistem SIMTA. Hubungi admin untuk pendaftaran akun.'
            ]);
        }

        // $saved = $user->forceFill([
        //     'google_id'            => $socialUser->id,
        //     'google_token'         => $socialUser->token,
        //     'google_refresh_token' => $socialUser->refreshToken, 
        // ])->save();


        Auth::login($user, true);
        return redirect()->intended('/dashboard');

    } catch (\Exception $e) {
        Log::error('Google callback error: ' . $e->getMessage());
        $errorText = 'Periksa kembali akun Google atau hubungi admin.';
        if (str_contains($e->getMessage(), 'invalid_grant')) {
            $errorText = 'Sesi login Google kadaluarsa.';
        } elseif (str_contains($e->getMessage(), 'access_denied')) {
            $errorText = 'Anda membatalkan proses login Google.';
        }
        return redirect('/login')->with([
            'error_type'  => 'google',
            'error_title' => 'Gagal Login',
            'error_text'  => $errorText
        ]);
    }
}
}