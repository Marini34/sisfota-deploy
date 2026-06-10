<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MahasiswaController extends Controller
{
    public function loginMahasiswa(Request $request)
    {
        $apiEndpoint = 'https://presensi.untan.ac.id/api/v2/mahasiswa/login-mahasiswa';

        try {
            $response = Http::post($apiEndpoint, [
                'email' => $request->input('email'),
                'password' => $request->input('password'),
            ]);

            // Manipulasi atau gunakan $response sesuai kebutuhan
            $data = $response->json();

            return response()->json($data, $response->status());
        } catch (\Exception $e) {
            // Tangani kesalahan jika ada
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
