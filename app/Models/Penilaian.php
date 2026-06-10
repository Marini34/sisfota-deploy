<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penilaian extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'penilaian';
    protected $fillable = [
        'seminar_sidang_id',
        'dosen_id',
        'mahasiswa_id',
        'parameter_penilaian_id',
        'nilai'
    ];
    //relasi seminar_sidang
    public function seminar_sidang(): BelongsTo
    {
        return $this->belongsTo(SeminarSidang::class, 'seminar_sidang_id');
    }
    //relasi dosen
    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }
    
    //relasi mahasiswa
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }
    //relasi parameter penilaian
    public function parameter_penilaian(): BelongsTo
    {
        return $this->belongsTo(ParameterPenilaian::class, 'parameter_penilaian_id')->withTrashed();
    }
}
