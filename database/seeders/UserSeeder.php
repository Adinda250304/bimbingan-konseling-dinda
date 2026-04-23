<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Guru BK (Bimbingan Konseling)
        $admin = User::firstOrCreate(['email' => 'admin@smkypml.sch.id'], [
            'name'     => 'Guru BK',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
        ]);
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }


        // Siswa
        $siswa = [
            ['name' => 'Adinda Putri',   'username' => 'adinda', 'email' => 'adinda@siswa.sch.id',  'kelas' => 'XII MIPA 1'],
            ['name' => 'Otty Ramadhani', 'username' => 'otty',   'email' => 'otty@siswa.sch.id',    'kelas' => 'XII MIPA 1'],
            ['name' => 'Yubi Santoso',   'username' => 'yubi',   'email' => 'yubi@siswa.sch.id',    'kelas' => 'XII MIPA 1'],
            ['name' => 'Budi Prasetyo',  'username' => 'budi',   'email' => 'budi@siswa.sch.id',    'kelas' => 'XI TKJ 1'],
            ['name' => 'Siti Rahayu',    'username' => 'siti',   'email' => 'siti@siswa.sch.id',    'kelas' => 'XI TKJ 1'],
        ];

        foreach ($siswa as $s) {
            $u = User::firstOrCreate(['email' => $s['email']], [
                'name'     => $s['name'],
                'username' => $s['username'],
                'password' => Hash::make('siswa123'),
                'kelas'    => $s['kelas'],
            ]);
            if (!$u->hasRole('siswa')) {
                $u->assignRole('siswa');
            }
        }
    }
}
