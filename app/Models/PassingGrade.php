<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PassingGrade extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'passing_grade';
    protected $fillable = [
        'tahapan_ta',
        'nilai'
    ];
}
