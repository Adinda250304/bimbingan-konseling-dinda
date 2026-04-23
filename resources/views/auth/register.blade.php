@extends('layouts.auth')
@section('title', 'Daftar — Teman BK')

@section('content')
<div class="bg-white rounded-2xl shadow-lg p-7">
    <div class="flex flex-col items-center mb-6">
        <img src="{{ asset('img/logo-ypml.png') }}" alt="Logo" class="h-14 w-auto mb-2">
        <h1 class="text-blue-600 font-bold text-xl">Daftar Akun</h1>
        <p class="text-gray-500 text-xs">Buat akun siswa baru</p>
    </div>

    @if($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
        {{ $errors->first() }}
    </div>
    @endif

    <form action="{{ route('register.post') }}" method="POST" class="space-y-3">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm outline-none focus:border-blue-400 font-poppins transition">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
            <input type="text" name="username" value="{{ old('username') }}" required
                class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm outline-none focus:border-blue-400 font-poppins transition">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm outline-none focus:border-blue-400 font-poppins transition">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
            <input type="text" name="kelas" value="{{ old('kelas') }}" placeholder="XII MIPA 1"
                class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm outline-none focus:border-blue-400 font-poppins transition">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" name="password" required minlength="6"
                class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm outline-none focus:border-blue-400 font-poppins transition">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" required
                class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm outline-none focus:border-blue-400 font-poppins transition">
        </div>
        <button type="submit"
            class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm transition mt-1">
            Daftar
        </button>
    </form>

    <p class="text-center text-xs text-gray-500 mt-4">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:underline">Masuk</a>
    </p>
</div>
@endsection
