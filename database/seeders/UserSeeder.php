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
        $gurubk = User::firstOrCreate(['email' => 'gurubk@smkypml.sch.id'], [
            'name'     => 'Guru BK',
            'username' => 'gurubk',
            'password' => Hash::make('gurubk123'),
        ]);
        $gurubk->syncRoles(['guru_bk']);

        $faker = \Faker\Factory::create('id_ID');

        $tingkats = ['X', 'XI', 'XII'];
        $jurusans = ['DKV', 'TKJ'];
        $uruts = [1, 2, 3];

        foreach ($tingkats as $tingkat) {
            foreach ($jurusans as $jurusan) {
                foreach ($uruts as $urut) {
                    $kelasFull = "{$tingkat} {$jurusan} {$urut}";
                    $kodeLower = strtolower($tingkat . $jurusan . $urut);

                    // Buat 1 Wali Kelas untuk masing-masing kelas
                    $wali = User::firstOrCreate(['email' => "wali{$kodeLower}@smkypml.sch.id"], [
                        'name'     => "Wali Kelas {$kelasFull}",
                        'username' => "wali{$kodeLower}",
                        'password' => Hash::make("walikelas123"),
                        'kelas'    => $kelasFull,
                    ]);
                    $wali->syncRoles(['wali_kelas']);

                    // Buat 9 Siswa untuk masing-masing kelas
                    for ($i = 1; $i <= 9; $i++) {
                        $siswaUsername = "siswa{$kodeLower}" . $i;
                        $siswa = User::firstOrCreate(['email' => "{$siswaUsername}@siswa.sch.id"], [
                            'name'     => $faker->name(),
                            'username' => $siswaUsername,
                            'password' => Hash::make('siswa123'),
                            'kelas'    => $kelasFull,
                        ]);
                        $siswa->syncRoles(['siswa']);
                    }

                    // Keep DB connection alive between batches
                    \Illuminate\Support\Facades\DB::reconnect();
                    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
                }
            }
        }
    }
}
