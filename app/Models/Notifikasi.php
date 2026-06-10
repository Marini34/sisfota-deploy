<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'dosen_id',
        'ref_id',
        'dibaca',
        'keterangan'
    ];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }
}
