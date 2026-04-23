<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Konseling;
use App\Models\User;

class KonselingSeeder extends Seeder
{
    public function run(): void
    {
        $siswaIds = User::role('siswa')->pluck('id');
        if ($siswaIds->isEmpty()) return;

        $items = [
            [
                'siswa_id'          => $siswaIds[0] ?? null,
                'jenis_masalah'     => 'Konsultasi karir',
                'deskripsi_masalah' => 'Siswa ingin konsultasi tentang pilihan jurusan kuliah.',
                'jenis'             => 'offline',
                'status'            => 'selesai',
                'tanggal_konseling' => now()->subDays(3)->toDateString(),
                'jam_konseling'     => '09:00',
            ],
            [
                'siswa_id'          => $siswaIds[1] ?? null,
                'jenis_masalah'     => 'Konsultasi karir',
                'deskripsi_masalah' => 'Masalah motivasi belajar menjelang ujian.',
                'jenis'             => 'offline',
                'status'            => 'disetujui',
                'tanggal_konseling' => now()->toDateString(),
                'jam_konseling'     => '10:00',
            ],
            [
                'siswa_id'          => $siswaIds[2] ?? null,
                'jenis_masalah'     => 'Konsultasi jurusan',
                'deskripsi_masalah' => 'Belum memutuskan jurusan yang tepat.',
                'jenis'             => 'offline',
                'status'            => 'menunggu',
                'tanggal_konseling' => now()->addDays(7)->toDateString(),
                'jam_konseling'     => '10:00',
            ],
        ];

        foreach ($items as $item) {
            if ($item['siswa_id']) {
                Konseling::firstOrCreate(
                    ['siswa_id' => $item['siswa_id'], 'jenis_masalah' => $item['jenis_masalah']],
                    $item
                );
            }
        }
    }
}
