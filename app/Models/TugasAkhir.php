<?php

namespace App\Models;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\SeminarSidang;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TugasAkhir extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'tugas_akhir';

    protected $fillable = [
        'mahasiswa_id',
        'judul',
        'no_hp',
        'studi_kasus',
        'metode',
        'dosen_PA_id',
        'dosen_pembimbing_1_id',
        'dosen_pembimbing_2_id',
        'dosen_penguji_1_id',
        'dosen_penguji_2_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = Str::uuid()->toString();
        });
    }

    // marini
    public function penguji1()
    {
        return $this->belongsTo(\App\Models\Dosen::class, 'dosen_penguji_1_id');
    }

    public function penguji2()
    {
        return $this->belongsTo(\App\Models\Dosen::class, 'dosen_penguji_2_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id', 'id');
    }

    // relasi ke dosen pembimbing 1
    public function dosen_PA()
    {
        return $this->belongsTo(Dosen::class, 'dosen_PA_id');
    }

    public function dosen_pembimbing_1()
    {
        return $this->belongsTo(Dosen::class, 'dosen_pembimbing_1_id');
    }

    // relasi ke dosen pembimbing 2
    public function dosen_pembimbing_2()
    {
        return $this->belongsTo(Dosen::class, 'dosen_pembimbing_2_id');
    }

    public function dosen_penguji_1()
    {
        return $this->belongsTo(Dosen::class, 'dosen_penguji_1_id');
    }

    public function dosen_penguji_2()
    {
        return $this->belongsTo(Dosen::class, 'dosen_penguji_2_id');
    }

    public function seminarSidangData(): HasMany
    {
        return $this->hasMany(SeminarSidang::class, 'tugas_akhir_id');
    }
    public function seminarSidangTerakhir(): HasOne
    {
        return $this->hasOne(SeminarSidang::class, 'tugas_akhir_id')
            ->latestOfMany();
    }
}
