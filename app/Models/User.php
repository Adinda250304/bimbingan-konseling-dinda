<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $fillable = [
        'name', 'username', 'email', 'password', 'kelas', 'no_telp', 'alamat', 'nama_ortu', 'no_telp_ortu', 'email_ortu',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isSiswa(): bool
    {
        return $this->hasRole('siswa');
    }

    public function konselings()
    {
        return $this->hasMany(Konseling::class, 'siswa_id');
    }

    public function artikels()
    {
        return $this->hasMany(Artikel::class, 'author_id');
    }
}
