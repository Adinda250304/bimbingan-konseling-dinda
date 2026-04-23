<?php

namespace Database\Seeders;

use App\Models\HasilKonseling;
use App\Models\Konseling;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BukuPenghubungSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name' => 'Satria Dyva Jaya Perdana', 'kelas' => '10 Tkj.2', 'masalah' => 'Absensi ket. Alpa (6)', 'tindakan' => 'Panggilan & Home Visit'],
            ['name' => 'Ridho Maulana', 'kelas' => '11 Tkj.1', 'masalah' => 'Absensi ket. Alpa (5)', 'tindakan' => null],
            ['name' => 'M. Alfiansyah', 'kelas' => '11 Tkj.1', 'masalah' => 'Absensi ket. Alpa (5)', 'tindakan' => null],
            ['name' => 'Almira Dwi Putri', 'kelas' => '11 DKV', 'masalah' => 'Absensi ket. Alpa', 'tindakan' => null],
            ['name' => 'Desy Yolanda', 'kelas' => '10 DKV', 'masalah' => 'Absensi (6)', 'tindakan' => 'Sudah komunikasi dgn pihak ortu, Pemanggilan dan memberikan Motivasi'],
            ['name' => 'Aurel', 'kelas' => '12 TKJ', 'masalah' => 'Terkait Pkl', 'tindakan' => 'Pemanggilan dan Memberikan motivasi'],
            ['name' => 'Sirojudin', 'kelas' => '10 Tkj.2', 'masalah' => 'Ribut dikelas', 'tindakan' => 'Mediasi di kantor'],
            ['name' => 'M. Reyvan Sopian', 'kelas' => '10 Tkj.2', 'masalah' => 'Ribut dikelas', 'tindakan' => 'Mediasi di kelas'],
            ['name' => 'Luthfi', 'kelas' => '10 Tkj.2', 'masalah' => 'Merokok', 'tindakan' => 'Pemanggilan dan Pembuatan surat perjanjian'],
            ['name' => 'M. Reyvan', 'kelas' => '10 Tkj.2', 'masalah' => 'Malakin', 'tindakan' => 'Perjanjian'],
            ['name' => 'Januar', 'kelas' => '10 Tkj.2', 'masalah' => 'Merokok', 'tindakan' => 'Pemanggilan dan Pembuatan surat perjanjian'],
            ['name' => 'Tegar', 'kelas' => '11 Tkj.2', 'masalah' => 'Merokok', 'tindakan' => 'Pemanggilan orang tua dan Pembuatan surat perjanjian'],
        ];

        foreach ($data as $row) {
            $username = Str::slug($row['name'], '');
            // Prevent duplicate usernames
            $count = User::where('username', 'like', $username . '%')->count();
            if ($count > 0) {
                $username .= $count;
            }

            $user = User::create([
                'name' => $row['name'],
                'username' => $username,
                'email' => $username . '@siswa.com',
                'password' => bcrypt('password'),
                'kelas' => $row['kelas'],
            ]);
            $user->assignRole('siswa');

            $konseling = Konseling::create([
                'siswa_id' => $user->id,
                'tanggal_konseling' => today()->subDays(rand(1, 14)), // create mock dates from recent past
                'jam_konseling' => '09:00:00',
                'jenis_masalah' => $row['masalah'],
                'deskripsi_masalah' => "Disalin dari Buku Penghubung BK. Permasalahan: " . $row['masalah'],
                'jenis' => 'offline',
                'status' => 'selesai', // Status selesai so it enters history automatically
            ]);

            if ($row['tindakan']) {
                HasilKonseling::create([
                    'konseling_id' => $konseling->id,
                    'catatan_konselor' => 'Berdasarkan buku penghubung BK.',
                    'saran' => 'Harus diperbaiki dan tidak mengulangi kembali.',
                    'tindak_lanjut' => $row['tindakan']
                ]);
            } else {
                 HasilKonseling::create([
                    'konseling_id' => $konseling->id,
                    'catatan_konselor' => 'Berdasarkan buku penghubung BK.',
                    'saran' => 'Akan dipantau lebih lanjut.',
                    'tindak_lanjut' => 'Pemantauan secara berkala'
                ]);
            }
        }
    }
}
