<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengumuman extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'pengumuman';
    protected $fillable = [
        'judul_pengumuman',
        'isi_pengumuman',
        'roles_id',
        'lampiran'
    ];
    public function setCategoryAttribute($value)
    {
        $this->attributes['lampiran'] = json_encode($value);
    }

    public function getCategoryAttribute($value)
    {
        return $this->attributes['lampiran'] = json_decode($value);
    }

    public function roles(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'roles_id');
    }
}
