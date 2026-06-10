<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Mahasiswa extends Model
{
    use HasFactory;
    protected $table = 'mahasiswa';
    protected $fillable = [

        'nim',
        'nama_lengkap',
        'jenis_kelamin',
        'alamat',
        'user_id',
        'tahun_masuk',
    ];
    public function seminarSidang()
    {
        return $this->hasMany(\App\Models\SeminarSidang::class);
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = Str::uuid()->toString();
        });
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function tugasAkhir()
    {
        return $this->hasOne(TugasAkhir::class, 'mahasiswa_id');
    }
    public function seminarSidangData()
    {
        return $this->hasOne(SeminarSidang::class, 'mahasiswa_id');
    }
    public function rekamBimbinganData()
    {
        return $this->hasOne(RekamBimbingan::class, 'mahasiswa_id');
    }

    public function lamaPengerjaanTa()
    {
        $seminarProposal = SeminarSidang::where('mahasiswa_id', $this->id)
            ->where('tahapan_ta', 'Seminar Proposal')
            ->where('status_kelulusan', 'LULUS')
            ->first();

        $sidangAkhir = SeminarSidang::where('mahasiswa_id', $this->id)
            ->where('tahapan_ta', 'Sidang Akhir')
            ->where('status_kelulusan', 'LULUS')
            ->first();

        if (!$seminarProposal || !$sidangAkhir) {
            return '-';
        }

        $tanggalProposal = Carbon::parse($seminarProposal->tanggal_pelaksanaan);
        $tanggalSidang = Carbon::parse($sidangAkhir->tanggal_pelaksanaan);

        $selisihHari = $tanggalProposal->diffInDays($tanggalSidang);

        $bulan = floor($selisihHari / 30);
        $hari = $selisihHari % 30;

        return $bulan . ' Bulan ' . $hari . ' Hari';
    }
}
