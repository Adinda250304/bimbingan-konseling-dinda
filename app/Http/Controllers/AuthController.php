<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showSplash()
    {
        if (Auth::check()) {
            return redirect()->route($this->getDashboardRoute(Auth::user()));
        }
        session(['splash_seen' => true]);
        return view('auth.splash');
    }

    public function showWelcome()
    {
        if (Auth::check()) {
            return redirect()->route($this->getDashboardRoute(Auth::user()));
        }
        if (!session('splash_seen')) {
            return redirect()->route('splash');
        }
        return view('auth.welcome');
    }

    public function showAbout()
    {
        if (Auth::check()) {
            return redirect()->route($this->getDashboardRoute(Auth::user()));
        }
        return view('auth.about');
    }

    public function showLayanan()
    {
        if (Auth::check()) {
            return redirect()->route($this->getDashboardRoute(Auth::user()));
        }
        return view('auth.layanan');
    }

    public function showAlur()
    {
        if (Auth::check()) {
            return redirect()->route($this->getDashboardRoute(Auth::user()));
        }
        return view('auth.alur');
    }

    public function showFaq()
    {
        if (Auth::check()) {
            return redirect()->route($this->getDashboardRoute(Auth::user()));
        }
        return view('auth.faq');
    }

    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route($this->getDashboardRoute(Auth::user()));
        }
        return view('auth.login');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route($this->getDashboardRoute(Auth::user()));
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:100|unique:users',
            'email'    => 'required|email|unique:users',
            'password' => [
                'required',
                'min:8',
                'max:20',
                'confirmed',
                'regex:/[A-Z]/',      // minimal 1 huruf besar
                'regex:/[a-z]/',      // minimal 1 huruf kecil
                'regex:/[0-9]/',      // minimal 1 angka
                'regex:/[@$!%*#?&^]/', // minimal 1 karakter spesial
            ],
            'kelas'    => 'nullable|string|max:100',
        ], [
            'username.unique'    => 'Username sudah digunakan.',
            'email.unique'       => 'Email sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min'       => 'Kata sandi minimal 8 karakter.',
            'password.max'       => 'Kata sandi maksimal 20 karakter.',
            'password.regex'     => 'Kata sandi harus mengandung huruf besar, huruf kecil, angka, dan karakter spesial (@$!%*#?&^).',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'kelas'    => $request->kelas,
        ]);
        $user->assignRole('siswa');

        Auth::login($user);
        return redirect()->route('siswa.dashboard')->with('success', 'Registrasi berhasil! Selamat datang di Teman BK.');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string|min:8|max:20',
        ], [
            'login.required'    => 'Username atau email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Kata sandi minimal 8 karakter.',
            'password.max'      => 'Kata sandi maksimal 20 karakter.',
        ]);

        $login = trim($request->login);

        $user = User::where('username', $login)
                    ->orWhere('email', $login)
                    ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors(['login' => 'Username/email atau password salah.'])
                ->withInput();
        }

        Auth::login($user, $request->boolean('remember'));

        return redirect()
            ->route($this->getDashboardRoute($user))
            ->with('success', 'Login berhasil! Selamat datang, ' . $user->name . '.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    private function getDashboardRoute(User $user): string
    {
        if ($user->hasRole('admin')) return 'admin.dashboard';
        if ($user->hasRole('wali_kelas')) return 'wali.dashboard';
        return 'siswa.dashboard';
    }
}
