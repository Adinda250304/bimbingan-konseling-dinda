<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Konseling extends Model
{
    protected $fillable = [
        'siswa_id', 'guru_id', 'jadwal_id', 'jenis_masalah', 'deskripsi_masalah',
        'jenis', 'status', 'link_meeting', 'tanggal_konseling',
        'jam_konseling', 'alasan_penolakan',
    ];

    protected $casts = [
        'tanggal_konseling' => 'date',
    ];

    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function jadwal()
    {
        return $this->belongsTo(JadwalKonseling::class, 'jadwal_id');
    }

    public function hasil()
    {
        return $this->hasOne(HasilKonseling::class, 'konseling_id');
    }

    public function tindakLanjut()
    {
        return $this->hasMany(TindakLanjut::class, 'konseling_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'menunggu'     => 'Menunggu',
            'disetujui'    => 'Terjadwal',
            'berlangsung'  => 'Berlangsung',
            'ditolak'      => 'Ditolak',
            'selesai'      => 'Selesai',
            'tidak_hadir'  => 'Tidak Hadir',
            default        => ucfirst($this->status),
        };
    }
}
