<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeminarSidang extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'seminar_sidang';
    protected $casts = [
        'catatan_ta_notulen_mahasiswa_id' => 'array',
    ];
    protected $attributes = [
        'is_publish' => false,
    ];
    protected $fillable = [
        'tugas_akhir_id',
        'mahasiswa_id',
        'tahapan_ta',
        'status_pendaftaran',
        'tanggal_pelaksanaan',
        'jam_pelaksanaan',
        'tempat_pelaksanaan',
        'dokumen_ta',
        'file_lampiran',
        'file_revisi',
        'link_video',
        'total_nilai_pembimbing_1',
        'total_nilai_pembimbing_2',
        'total_nilai_penguji_1',
        'total_nilai_penguji_2',
        'catatan_ta_pembimbing_1',
        'catatan_ta_pembimbing_2',
        'catatan_ta_penguji_1',
        'catatan_ta_penguji_2',
        'file_notulensi',
        'total_nilai_akhir',
        'status_kelulusan',
        'alasan_penolakan',
        'alasan_dibatalkan',
        'notulen_mahasiswa_id',
        'catatan_ta_notulen_mahasiswa_id',
        'status_validasi_notulen_mhs',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = Str::uuid()->toString();
        });
    }

    public function getTanggalPelaksanaan($value)
    {
        return Carbon::parse($value)->format('d/m/y');
    }

    public function setTanggalPelaksanaan($value)
    {
        $this->attributes['tanggal_pelaksanaan'] = Carbon::createFromFormat('d/m/y', $value)->format('Y-m-d');
    }

    public function setCategoryAttributeLampiran($value)
    {
        $this->attributes['file_lampiran'] = json_encode($value);
    }

    public function getCategoryAttributeLampiran($value)
    {
        return $this->attributes['file_lampiran'] = json_decode($value);
    }

    public function setCategoryAttributeNotulensi($value)
    {
        $this->attributes['file_notulensi'] = json_encode($value);
    }

    public function getCategoryAttributeNotulensi($value)
    {
        return $this->attributes['file_notulensi'] = json_decode($value);
    }

    public function tugas_akhir(): BelongsTo
    {

        return $this->belongsTo(TugasAkhir::class, 'tugas_akhir_id', 'id');
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    public function notulenMahasiswa()
    {
        return $this->belongsTo(\App\Models\Mahasiswa::class, 'notulen_mahasiswa_id');
    }

    public function tugasAkhir()
    {
        return $this->belongsTo(TugasAkhir::class);
    }

    public function penilaians(): HasMany
    {
        return $this->hasMany(Penilaian::class, 'seminar_sidang_id');
    }

    // auto create mahasiswa lulus
    protected static function booted()
    {
        static::saved(function (SeminarSidang $ss) {

            // Wajib punya mahasiswa dan tanggal pelaksanaan
            if (!$ss->mahasiswa_id || !$ss->tanggal_pelaksanaan) {
                return;
            }

            // hanya kalau LULUS
            if ($ss->status_kelulusan !== 'LULUS') {
                return;
            }

            // (REKOMENDASI) isi tahun_lulus hanya saat Sidang Akhir lulus
            if ($ss->tahapan_ta !== 'Sidang Akhir') {
                return;
            }

            // Parse tanggal_pelaksanaan
            $date = Carbon::parse($ss->tanggal_pelaksanaan);

            // Update mahasiswa (tanpa memicu event lain yang tidak perlu)
            \App\Models\Mahasiswa::where('id', $ss->mahasiswa_id)
                ->update([
                    'tgl_lulus'   => $date->format('d'),  // '31'
                    'bln_lulus'   => $date->format('m'),  // '07'
                    'tahun_lulus' => $date->format('Y'),  // '2026'
                ]);
        });
    }
}
