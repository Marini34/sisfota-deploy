<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;

class NotifikasiController extends Controller
{
    //
    public function markAsRead($id)
    {
        $notif = Notifikasi::findOrFail($id);

        $notif->update([
            'dibaca' => true,
        ]);

        return response()->json([
            'success' => true,
        ]);
    }

    public function dibacaDosen($id)
    {
        $dosenId = auth()->user()->dosen->id;

        $notif = Notifikasi::where('id', $id)
            ->where('dosen_id', $dosenId)
            ->first();

        if (! $notif) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $notif->update([
            'dibaca' => 1,
        ]);

        return response()->json(['success' => true]);
    }
}
