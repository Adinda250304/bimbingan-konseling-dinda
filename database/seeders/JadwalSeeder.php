<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JadwalKonseling;

class JadwalSeeder extends Seeder
{
    public function run(): void
    {
        $jadwals = [
            ['Senin',  '08:00', '10:00', 'Ruangan BK 1'],
            ['Selasa', '10:00', '12:00', 'Ruangan BK 1'],
            ['Rabu',   '08:00', '10:00', 'Ruangan BK 2'],
            ['Kamis',  '10:00', '12:00', 'Ruangan BK 2'],
            ['Jumat',  '08:00', '11:00', 'Ruangan BK 1'],
        ];

        foreach ($jadwals as [$hari, $mulai, $selesai, $tempat]) {
            JadwalKonseling::firstOrCreate(
                ['hari' => $hari, 'jam_mulai' => $mulai],
                [
                    'jam_selesai' => $selesai,
                    'tempat'      => $tempat,
                    'is_aktif'    => true,
                ]
            );
        }
    }
}
