@extends('layouts.admin')
@section('title', 'Profil Saya')
@section('nav-title', 'Selamat Datang, ' . auth()->user()->name . '!')

@section('content')
@if(session('success'))
<div class="mb-6 flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 rounded-2xl px-5 py-4 text-sm font-medium shadow-sm">
    <span class="material-symbols-outlined text-[1.25rem] text-green-600">check_circle</span>
    {{ session('success') }}
</div>
@endif
@if(session('success_pw'))
<div class="mb-6 flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 rounded-2xl px-5 py-4 text-sm font-medium shadow-sm">
    <span class="material-symbols-outlined text-[1.25rem] text-green-600">check_circle</span>
    {{ session('success_pw') }}
</div>
@endif

<div class="max-w-5xl mx-auto grid grid-cols-1 xl:grid-cols-2 gap-8">
    {{-- Profil Saya --}}
    <div class="bg-surface rounded-3xl shadow-sm border border-outline-variant/30 overflow-hidden">
        <div class="px-8 py-6 border-b border-outline-variant/20 bg-surface-container-lowest flex items-center gap-3">
            <span class="material-symbols-outlined text-[1.5rem] text-primary">person</span>
            <h2 class="text-xl font-bold text-on-surface">Profil Saya</h2>
        </div>
        <div class="px-8 py-8">
            {{-- Avatar --}}
            <div class="w-24 h-24 bg-primary-container text-on-primary-container rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-inner">
                <span class="material-symbols-outlined text-[3rem]">account_circle</span>
            </div>

            <form action="{{ route('guru_bk.profile.update') }}" method="POST" class="space-y-6">
                @csrf @method('PUT')

                <div class="space-y-1.5">
                    <label class="flex items-center gap-2 text-sm font-bold text-on-surface">
                        <span class="material-symbols-outlined text-[1.125rem] text-on-surface-variant">badge</span>
                        Nama Lengkap
                    </label>
                    <input type="text" name="name" value="{{ $user->name }}" required
                        class="w-full bg-surface-container-lowest border border-outline-variant/50 text-on-surface text-sm rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent block px-4 py-3.5 transition-all outline-none"
                        placeholder="Nama lengkap...">
                    @error('name')<p class="text-error text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                </div>

                <div class="space-y-1.5">
                    <label class="flex items-center gap-2 text-sm font-bold text-on-surface">
                        <span class="material-symbols-outlined text-[1.125rem] text-on-surface-variant">mail</span>
                        Email
                    </label>
                    <input type="email" name="email" value="{{ $user->email }}" required
                        class="w-full bg-surface-container-lowest border border-outline-variant/50 text-on-surface text-sm rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent block px-4 py-3.5 transition-all outline-none"
                        placeholder="Email">
                    @error('email')<p class="text-error text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                </div>

                <div class="space-y-1.5">
                    <label class="flex items-center gap-2 text-sm font-bold text-on-surface">
                        <span class="material-symbols-outlined text-[1.125rem] text-on-surface-variant">call</span>
                        Nomor Telepon
                    </label>
                    <input type="text" name="no_telp" value="{{ $user->no_telp }}"
                        class="w-full bg-surface-container-lowest border border-outline-variant/50 text-on-surface text-sm rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent block px-4 py-3.5 transition-all outline-none"
                        placeholder="No. Telepon">
                </div>

                <div class="space-y-1.5">
                    <label class="flex items-center gap-2 text-sm font-bold text-on-surface">
                        <span class="material-symbols-outlined text-[1.125rem] text-on-surface-variant">home</span>
                        Alamat
                    </label>
                    <input type="text" name="alamat" value="{{ $user->alamat ?? '' }}"
                        class="w-full bg-surface-container-lowest border border-outline-variant/50 text-on-surface text-sm rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent block px-4 py-3.5 transition-all outline-none"
                        placeholder="Alamat">
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full py-3.5 bg-primary hover:bg-primary/90 text-white font-bold rounded-xl text-sm transition-all focus:ring-4 focus:ring-primary/20 active:scale-95 shadow-md flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[1.125rem]">save</span>
                        Simpan Profil
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Update Password --}}
    <div class="bg-surface rounded-3xl shadow-sm border border-outline-variant/30 overflow-hidden h-fit">
        <div class="px-8 py-6 border-b border-outline-variant/20 bg-surface-container-lowest flex items-center gap-3">
            <span class="material-symbols-outlined text-[1.5rem] text-primary">key</span>
            <h2 class="text-xl font-bold text-on-surface">Update Password</h2>
        </div>

        <div class="px-8 py-8">
            @if($errors->has('current_password'))
            <div class="mb-6 flex items-center gap-3 bg-error-container border border-error/20 text-on-error-container rounded-2xl px-5 py-4 text-sm font-medium">
                <span class="material-symbols-outlined text-[1.25rem] text-error">error</span>
                {{ $errors->first('current_password') }}
            </div>
            @endif

            <form action="{{ route('guru_bk.profile.password') }}" method="POST" class="space-y-6">
                @csrf @method('PUT')

                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-on-surface">Password Saat Ini</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[1.125rem]">lock</span>
                        <input type="password" name="current_password" required
                            class="w-full bg-surface-container-lowest border border-outline-variant/50 text-on-surface text-sm rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent block py-3.5 pl-12 pr-4 transition-all outline-none"
                            placeholder="Masukkan password saat ini">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-on-surface">Password Baru</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[1.125rem]">lock_reset</span>
                        <input type="password" name="password" required minlength="6"
                            class="w-full bg-surface-container-lowest border border-outline-variant/50 text-on-surface text-sm rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent block py-3.5 pl-12 pr-4 transition-all outline-none"
                            placeholder="Masukkan password baru">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-bold text-on-surface">Konfirmasi Password Baru</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[1.125rem]">password</span>
                        <input type="password" name="password_confirmation" required
                            class="w-full bg-surface-container-lowest border border-outline-variant/50 text-on-surface text-sm rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent block py-3.5 pl-12 pr-4 transition-all outline-none"
                            placeholder="Ketik ulang password baru">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full py-3.5 bg-surface-container-highest hover:bg-on-surface/10 text-on-surface font-bold rounded-xl text-sm transition-all focus:ring-4 focus:ring-outline-variant/20 active:scale-95 shadow-sm flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[1.125rem]">update</span>
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
