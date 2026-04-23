@extends('layouts.auth')
@section('title', 'Login — Teman BK')

@section('content')
<div class="bg-white rounded-2xl shadow-lg p-7">
    {{-- Logo --}}
    <div class="flex flex-col items-center mb-6">
        <img src="{{ asset('img/logo-ypml.png') }}" alt="Logo YPML" class="h-16 w-auto mb-2">
        <h1 class="text-blue-600 font-bold text-xl">Teman BK</h1>
        <p class="text-gray-500 text-xs mt-0.5">Masuk untuk melanjutkan</p>
    </div>

    <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Username / Email</label>
            <input type="text" name="login" value="{{ old('login') }}" required autofocus
                placeholder="Masukkan username atau email"
                class="w-full px-4 py-3 border-2 {{ $errors->has('login') ? 'border-red-400' : 'border-gray-200' }} rounded-xl text-sm outline-none focus:border-blue-400 font-poppins transition">
            @error('login')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" name="password" required
                placeholder="Masukkan password"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm outline-none focus:border-blue-400 font-poppins transition">
            @error('password')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit"
            class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm transition mt-2">
            Masuk
        </button>
    </form>

    <p class="text-center text-xs text-gray-500 mt-4">
        Belum punya akun?
        <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:underline">Daftar di sini</a>
    </p>
</div>
@endsection
