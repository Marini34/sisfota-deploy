<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TranskripNilai extends Model
{
    use HasFactory;
    protected $table = 'transkrip_nilais';
    protected $fillable = [
        // 'uuid',
        'mahasiswa_id',
        'makul_id',
        'nilai'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    public function makul()
    {
        return $this->belongsTo(Makul::class, 'makul_id');
    }
}
