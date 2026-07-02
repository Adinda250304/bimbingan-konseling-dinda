<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles & permissions to avoid stale cache issues
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Akun Admin
        $admin = User::firstOrCreate(['email' => 'admin@smkypml.sch.id'], [
            'name'     => 'Administrator',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
        ]);
        $admin->update(['name' => 'Administrator']);
        $admin->syncRoles(['admin']);

        // Akun Guru BK
        $gurubk = User::firstOrCreate(['email' => 'rinimariny@smkypml.sch.id'], [
            'name'     => 'Rini Mariny',
            'username' => 'rinimariny',
            'password' => Hash::make('gurubk123'),
        ]);
        $gurubk->syncRoles(['guru_bk']);

        $siswas = [
            ['name' => 'Satria Dwi Jaya Perdana', 'kelas' => '10 TKJ-1'],
            ['name' => 'Ridho Maulana', 'kelas' => '11 TKJ-1'],
            ['name' => 'M. Alfiansyah', 'kelas' => '11 TKJ-1'],
            ['name' => 'Almira Qivi Putri', 'kelas' => '11 DKV'],
            ['name' => 'Desy Yulanda', 'kelas' => '10 DKV'],
            ['name' => 'Aurel', 'kelas' => '12 TKJ'],
            ['name' => 'Siro Judin', 'kelas' => '10 TKJ-2'],
            ['name' => 'M. Rayyan Sofyan', 'kelas' => '10 TKJ-2'],
            ['name' => 'Luthfi', 'kelas' => '10 TKJ-2'],
            ['name' => 'Januar', 'kelas' => '10 TKJ-2'],
            ['name' => 'Tegar', 'kelas' => '11 TKJ-2'],
        ];

        // Daftar Wali Kelas (Silakan sesuaikan jika Anda memiliki data aslinya nanti)
        $walis = [
            ['name' => 'Budi Santoso', 'kelas' => '10 TKJ-1'],
            ['name' => 'Siti Aminah', 'kelas' => '11 TKJ-1'],
            ['name' => 'Ahmad Fauzi', 'kelas' => '11 DKV'],
            ['name' => 'Dewi Lestari', 'kelas' => '10 DKV'],
            ['name' => 'Rahmat Hidayat', 'kelas' => '12 TKJ'],
            ['name' => 'Fitriani', 'kelas' => '10 TKJ-2'],
            ['name' => 'Eko Prasetyo', 'kelas' => '11 TKJ-2'],
        ];

        // Buat akun Wali Kelas
        foreach ($walis as $dataWali) {
            // Username otomatis dari nama (tanpa spasi dan huruf kecil)
            $username = strtolower(preg_replace('/[^a-z0-9]/i', '', $dataWali['name']));
            
            $wali = User::firstOrCreate(['email' => "{$username}@smkypml.sch.id"], [
                'name'     => $dataWali['name'],
                'username' => $username,
                'password' => Hash::make("walikelas123"),
                'kelas'    => $dataWali['kelas'],
            ]);
            $wali->syncRoles(['wali_kelas']);
        }

        // Buat akun siswa
        foreach ($siswas as $dataSiswa) {
            $username = strtolower(preg_replace('/[^a-z0-9]/i', '', $dataSiswa['name']));
            
            $siswa = User::firstOrCreate(['email' => "{$username}@siswa.sch.id"], [
                'name'     => $dataSiswa['name'],
                'username' => $username,
                'password' => Hash::make('siswa123'),
                'kelas'    => $dataSiswa['kelas'],
            ]);
            $siswa->syncRoles(['siswa']);
        }
    }
}
