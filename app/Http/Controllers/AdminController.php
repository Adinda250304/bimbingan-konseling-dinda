<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $data = [
            'total_siswa'       => User::role('siswa')->count(),
            'total_guru_bk'     => User::role('guru_bk')->count(),
            'total_wali_kelas'  => User::role('wali_kelas')->count(),
            'recent_users'      => User::latest()->take(5)->get(),
        ];
        return view('admin.dashboard', $data);
    }

    // ===== PROFILE =====
    public function profile()
    {
        return view('admin.profile', ['user' => auth()->user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,' . $user->id,
            'no_telp' => 'nullable|string|max:20',
            'alamat'  => 'nullable|string|max:255',
            
        ]);
        $user->update($request->only('name', 'email', 'no_telp', 'alamat'));
        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password'      => 'required',
            'password'              => [
                'required',
                'min:8',
                'max:20',
                'confirmed',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&^]/',
            ],
        ], [
            'password.min'   => 'Kata sandi minimal 8 karakter.',
            'password.max'   => 'Kata sandi maksimal 20 karakter.',
            'password.regex' => 'Kata sandi harus mengandung huruf besar, huruf kecil, angka, dan karakter spesial (@$!%*#?&^).',
        ]);
        if (!\Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }
        auth()->user()->update(['password' => bcrypt($request->password)]);
        return back()->with('success_pw', 'Password berhasil diperbarui.');
    }

    // ===== USERS =====
    public function users(Request $request)
    {
        $role  = $request->get('role', 'semua');
        $search = $request->get('search', '');
        $query = User::query();
        if ($role !== 'semua') $query->role($role);
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('username', 'like', "%$search%");
            });
        }
        $users = $query->latest()->paginate(10);
        return view('admin.users', compact('users', 'role', 'search'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'username'      => 'required|string|max:100|unique:users',
            'email'         => 'required|email|unique:users',
            'password'      => [
                'required',
                'min:8',
                'max:20',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&^]/',
            ],
            'role'          => 'required|in:admin,guru_bk,siswa,wali_kelas',
            'tingkat_kelas' => 'nullable|in:X,XI,XII',
            'jurusan'       => 'nullable|string|max:100',
            'no_telp'       => 'nullable|string|max:20',
        ]);

        // Gabungkan tingkat + jurusan menjadi nilai kelas
        $kelas = null;
        if ($request->tingkat_kelas) {
            $kelas = trim($request->tingkat_kelas . ' ' . $request->jurusan);
        }

        $user = User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'kelas'    => $kelas,
            'no_telp'  => $request->no_telp,
        ]);
        $user->assignRole($request->role);

        return back()->with('success', 'User berhasil ditambahkan.');
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'username'      => 'required|string|max:100|unique:users,username,' . $user->id,
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'role'          => 'required|in:admin,guru_bk,siswa,wali_kelas',
            'tingkat_kelas' => 'nullable|in:X,XI,XII',
            'jurusan'       => 'nullable|string|max:100',
            'no_telp'       => 'nullable|string|max:20',
        ]);

        $kelas = null;
        if ($request->tingkat_kelas) {
            $kelas = trim($request->tingkat_kelas . ' ' . $request->jurusan);
        }

        $user->update([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'kelas'    => $kelas,
            'no_telp'  => $request->no_telp,
        ]);
        $user->syncRoles($request->role);
        if ($request->filled('password')) {
            $user->update(['password' => bcrypt($request->password)]);
        }

        return back()->with('success', 'User berhasil diperbarui.');
    }

    public function deleteUser(User $user)
    {
        if ($user->hasRole('admin') && User::role('admin')->count() <= 1) {
            return back()->with('error', 'Tidak bisa menghapus admin terakhir.');
        }
        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }

}
