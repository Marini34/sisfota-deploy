<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusTranskrip extends Model
{
    use HasFactory;
    protected $table = 'status_transkrip';
    protected $fillable = [
        'mahasiswa_id',
        'file_transkrip',
        'jumlah_makul',
        'jumlah_mutu',
        'jumlah_sks',
        'ipk',
        'status_transkrip',
        'alasan_penolakan'
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }
}
