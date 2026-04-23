<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TindakLanjut extends Model
{
    protected $table = 'tindak_lanjut';

    protected $fillable = [
        'konseling_id', 'jenis', 'catatan', 'kode_unik',
        'status_wa', 'status_email', 'dikirim_at',
    ];

    protected $casts = [
        'dikirim_at' => 'datetime',
    ];

    public function konseling()
    {
        return $this->belongsTo(Konseling::class);
    }

    public function getJenisLabelAttribute(): string
    {
        return match($this->jenis) {
            'pemanggilan_ortu' => 'Pemanggilan Orang Tua',
            'mediasi'          => 'Mediasi',
            'rujukan'          => 'Rujukan Profesional',
            'lainnya'          => 'Lainnya',
            default            => ucfirst($this->jenis),
        };
    }
}
