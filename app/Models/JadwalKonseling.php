<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalKonseling extends Model
{
    protected $fillable = ['hari', 'jam_mulai', 'jam_selesai', 'tempat', 'is_aktif'];

    protected $casts = [
        'is_aktif' => 'boolean',
    ];

    public function konselings()
    {
        return $this->hasMany(Konseling::class, 'jadwal_id');
    }
}
