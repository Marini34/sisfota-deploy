<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParameterPenilaian extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'parameter_penilaian';
    protected $fillable = [
        'tahapan_ta',
        'nama_parameter',
        'deskripsi_parameter',
        'persentase',
    ];
    protected $casts = [
        'parameter_penilaian_ids' => 'integer',
    ];
}
