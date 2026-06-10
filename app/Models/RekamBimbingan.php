<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RekamBimbingan extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'rekam_bimbingan';
    protected $fillable = [
        'mahasiswa_id',
        'dosen_pembimbing_1_id',
        'dosen_pembimbing_2_id',
        'uraian_kemajuan_ta',
        'pengerjaan_selanjutnya',
        'tanggal_bimbingan',
        'status_rekam_bimbingan',
        'pembimbing_id',
        'persentase_mhs',
        'dokumen_bimbingan_mhs',
        'komentar_dosen'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = Str::uuid()->toString();
        });
    }
    protected static function booted()
    {
        static::deleting(function ($rekam) {

            $jadwal = JadwalBimbingan::where('dosen_id', $rekam->pembimbing_id)
                ->whereDate('tanggal', $rekam->tanggal_bimbingan)
                ->first();

            if ($jadwal && $jadwal->terisi > 0) {
                $jadwal->decrement('terisi');
            }
        });
    }
    public function dosen_pembimbing_1(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'dosen_pembimbing_1_id');
    }

    public function dosen_pembimbing_2(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'dosen_pembimbing_2_id');
    }

    public function pembimbing(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'pembimbing_id');
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }
}
