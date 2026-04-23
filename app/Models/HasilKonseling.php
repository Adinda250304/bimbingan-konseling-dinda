<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilKonseling extends Model
{
    protected $fillable = ['konseling_id', 'catatan_konselor', 'saran', 'tindak_lanjut'];

    public function konseling()
    {
        return $this->belongsTo(Konseling::class, 'konseling_id');
    }
}
