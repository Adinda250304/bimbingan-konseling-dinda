@extends('layouts.admin')
@section('title', 'Profil Saya')
@section('nav-title', 'Selamat Datang, ' . auth()->user()->name . '!')

@section('content')
@if(session('success_pw'))
<div class="mb-4 flex items-center gap-2 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm">
    {{ session('success_pw') }}
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    {{-- Profil Saya --}}
    <div class="bg-white rounded-2xl shadow-sm p-5">
        <div class="flex items-center gap-2 font-bold text-gray-800 mb-4">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
            </svg>
            Profil Saya
        </div>
        {{-- Avatar --}}
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-5">
            <svg class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-3">
            @csrf @method('PUT')
            <div>
                <label class="flex items-center gap-1 text-sm font-medium text-gray-700 mb-1">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/></svg>
                    Nama Lengkap
                </label>
                <input type="text" name="name" value="{{ $user->name }}" required
                    class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm outline-none focus:border-blue-400 font-poppins transition"
                    placeholder="Nama lengkap...">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="flex items-center gap-1 text-sm font-medium text-gray-700 mb-1">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                    Email
                </label>
                <input type="email" name="email" value="{{ $user->email }}" required
                    class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm outline-none focus:border-blue-400 font-poppins transition"
                    placeholder="Email">
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="flex items-center gap-1 text-sm font-medium text-gray-700 mb-1">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                    Nomor Telepon
                </label>
                <input type="text" name="no_telp" value="{{ $user->no_telp }}"
                    class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm outline-none focus:border-blue-400 font-poppins transition"
                    placeholder="No. Telepon">
            </div>
            <div>
                <label class="flex items-center gap-1 text-sm font-medium text-gray-700 mb-1">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                    Alamat
                </label>
                <input type="text" name="alamat" value="{{ $user->alamat ?? '' }}"
                    class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm outline-none focus:border-blue-400 font-poppins transition"
                    placeholder="Alamat">
            </div>
            <button type="submit"
                class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm transition">
                Simpan Profil
            </button>
        </form>
    </div>

    {{-- Update Password --}}
    <div class="bg-white rounded-2xl shadow-sm p-5">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2 font-bold text-gray-800">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/>
                </svg>
                Update Password
            </div>
            <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-gray-600 text-lg leading-none">×</a>
        </div>

        @if($errors->has('current_password'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
            {{ $errors->first('current_password') }}
        </div>
        @endif

        <form action="{{ route('admin.profile.password') }}" method="POST" class="space-y-3">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
                <input type="password" name="current_password" required
                    class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm outline-none focus:border-blue-400 font-poppins transition"
                    placeholder="Masukan Password saat ini">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                <input type="password" name="password" required minlength="6"
                    class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm outline-none focus:border-blue-400 font-poppins transition"
                    placeholder="Masukan Password baru">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" required
                    class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm outline-none focus:border-blue-400 font-poppins transition"
                    placeholder="Konfirmasi Password baru">
            </div>
            <div class="flex gap-3 pt-1">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex-1 text-center py-2.5 border-2 border-gray-300 hover:border-gray-500 rounded-xl text-sm font-semibold text-gray-700 transition">
                    Batal
                </a>
                <button type="submit"
                    class="flex-1 py-2.5 bg-gray-200 hover:bg-gray-300 rounded-xl text-sm font-semibold text-gray-700 transition">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
