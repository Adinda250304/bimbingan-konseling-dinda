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

        $faker = \Faker\Factory::create('id_ID');

        $tingkats = ['X', 'XI', 'XII'];
        $jurusans = ['DKV', 'TKJ'];
        $uruts = [1, 2, 3];

        foreach ($tingkats as $tingkat) {
            foreach ($jurusans as $jurusan) {
                foreach ($uruts as $urut) {
                    $kelasFull = "{$tingkat} {$jurusan} {$urut}";
                    
                    // Format username (contoh: walixdkv1, walixiitkj3)
                    $kodeLower = strtolower($tingkat . $jurusan . $urut);
                    
                    // Buat 1 Wali Kelas untuk masing-masing kelas
                    $wali = User::firstOrCreate(['email' => "wali{$kodeLower}@smkypml.sch.id"], [
                        'name'     => "Wali Kelas {$kelasFull}",
                        'username' => "wali{$kodeLower}",
                        'password' => Hash::make("wali123"),
                        'kelas'    => $kelasFull
                    ]);
                    if (!$wali->hasRole('wali_kelas')) {
                        $wali->assignRole('wali_kelas');
                    }

                    // Buat 9 Siswa untuk masing-masing kelas
                    for ($i = 1; $i <= 9; $i++) {
                        $siswaUsername = "siswa{$kodeLower}" . $i;
                        $siswa = User::firstOrCreate(['email' => "{$siswaUsername}@siswa.sch.id"], [
                            'name'     => $faker->name(),
                            'username' => $siswaUsername,
                            'password' => Hash::make('siswa123'),
                            'kelas'    => $kelasFull,
                        ]);
                        if (!$siswa->hasRole('siswa')) {
                            $siswa->assignRole('siswa');
                        }
                    }
                }
            }
        }
    }
}
