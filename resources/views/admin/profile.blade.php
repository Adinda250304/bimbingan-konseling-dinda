@extends('layouts.admin')
@section('title', 'Profil Saya')
@section('nav-title', 'Selamat Datang, ' . auth()->user()->name . '!')

@section('content')
@if(session('success_pw'))
<div class="mb-4 flex items-center gap-2 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm">
    {{ session('success_pw') }}
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Profil Saya --}}
    <div class="bg-surface rounded-3xl shadow-sm border border-outline-variant/30 overflow-hidden">
        <div class="p-6 border-b border-outline-variant/30 bg-surface-container-lowest flex items-center gap-3">
            <span class="material-symbols-outlined text-[24px] text-primary">person</span>
            <h2 class="text-xl font-bold text-on-surface">Profil Saya</h2>
        </div>
        <div class="p-6 sm:p-8">
            {{-- Avatar --}}
            <div class="w-20 h-20 bg-primary-container text-on-primary-container rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-inner">
                <span class="material-symbols-outlined text-[40px]">account_circle</span>
            </div>
            
            <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-5">
                @csrf @method('PUT')
                
                <div>
                    <label class="flex items-center gap-2 text-sm font-bold text-on-surface mb-2">
                        <span class="material-symbols-outlined text-[18px] text-on-surface-variant">badge</span>
                        Nama Lengkap
                    </label>
                    <input type="text" name="name" value="{{ $user->name }}" required
                        class="w-full bg-surface-container-lowest border border-outline-variant/50 text-on-surface text-sm rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent block p-3.5 transition-all outline-none"
                        placeholder="Nama lengkap...">
                    @error('name')<p class="text-error text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="flex items-center gap-2 text-sm font-bold text-on-surface mb-2">
                        <span class="material-symbols-outlined text-[18px] text-on-surface-variant">mail</span>
                        Email
                    </label>
                    <input type="email" name="email" value="{{ $user->email }}" required
                        class="w-full bg-surface-container-lowest border border-outline-variant/50 text-on-surface text-sm rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent block p-3.5 transition-all outline-none"
                        placeholder="Email">
                    @error('email')<p class="text-error text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="flex items-center gap-2 text-sm font-bold text-on-surface mb-2">
                        <span class="material-symbols-outlined text-[18px] text-on-surface-variant">call</span>
                        Nomor Telepon
                    </label>
                    <input type="text" name="no_telp" value="{{ $user->no_telp }}"
                        class="w-full bg-surface-container-lowest border border-outline-variant/50 text-on-surface text-sm rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent block p-3.5 transition-all outline-none"
                        placeholder="No. Telepon">
                </div>
                
                <div>
                    <label class="flex items-center gap-2 text-sm font-bold text-on-surface mb-2">
                        <span class="material-symbols-outlined text-[18px] text-on-surface-variant">home</span>
                        Alamat
                    </label>
                    <input type="text" name="alamat" value="{{ $user->alamat ?? '' }}"
                        class="w-full bg-surface-container-lowest border border-outline-variant/50 text-on-surface text-sm rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent block p-3.5 transition-all outline-none"
                        placeholder="Alamat">
                </div>
                
                <div class="pt-2">
                    <button type="submit"
                        class="w-full py-3.5 bg-primary hover:bg-primary/90 text-white font-bold rounded-xl text-sm transition-all focus:ring-4 focus:ring-primary/20 active:scale-95 shadow-md flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        Simpan Profil
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Update Password --}}
    <div class="bg-surface rounded-3xl shadow-sm border border-outline-variant/30 overflow-hidden h-fit">
        <div class="p-6 border-b border-outline-variant/30 bg-surface-container-lowest flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-[24px] text-primary">key</span>
                <h2 class="text-xl font-bold text-on-surface">Update Password</h2>
            </div>
        </div>
        
        <div class="p-6 sm:p-8">
            @if($errors->has('current_password'))
            <div class="mb-6 flex items-center gap-3 bg-error-container border border-error/20 text-on-error-container rounded-2xl px-4 py-3 text-sm font-medium">
                <span class="material-symbols-outlined text-[20px] text-error">error</span>
                {{ $errors->first('current_password') }}
            </div>
            @endif

            <form action="{{ route('admin.profile.password') }}" method="POST" class="space-y-5">
                @csrf @method('PUT')
                
                <div>
                    <label class="block text-sm font-bold text-on-surface mb-2">Password Saat Ini</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">lock</span>
                        <input type="password" name="current_password" required
                            class="w-full bg-surface-container-lowest border border-outline-variant/50 text-on-surface text-sm rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent block py-3.5 pl-11 pr-4 transition-all outline-none"
                            placeholder="Masukkan password saat ini">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-on-surface mb-2">Password Baru</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">lock_reset</span>
                        <input type="password" name="password" required minlength="6"
                            class="w-full bg-surface-container-lowest border border-outline-variant/50 text-on-surface text-sm rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent block py-3.5 pl-11 pr-4 transition-all outline-none"
                            placeholder="Masukkan password baru">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-on-surface mb-2">Konfirmasi Password Baru</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">password</span>
                        <input type="password" name="password_confirmation" required
                            class="w-full bg-surface-container-lowest border border-outline-variant/50 text-on-surface text-sm rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent block py-3.5 pl-11 pr-4 transition-all outline-none"
                            placeholder="Ketik ulang password baru">
                    </div>
                </div>
                
                <div class="pt-4">
                    <button type="submit"
                        class="w-full py-3.5 bg-surface-container-highest hover:bg-outline-variant/30 text-on-surface font-bold rounded-xl text-sm transition-all focus:ring-4 focus:ring-outline-variant/20 active:scale-95 shadow-sm flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">update</span>
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
