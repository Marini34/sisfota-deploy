<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalBimbingan extends Model
{
    //
    protected $table = 'jadwal_bimbingans';

    protected $fillable = [
        'dosen_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'kuota',
        'terisi',
        'lokasi',
    ];

    protected $casts = [
        'tanggal' => 'date',
        // 'jam_mulai' => 'datetime:H:i',
        // 'jam_selesai' => 'datetime:H:i',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
    */

    public function isFull()
    {
        return $this->terisi >= $this->kuota;
    }

    public function sisaKuota()
    {
        return $this->kuota - $this->terisi;
    }
}
