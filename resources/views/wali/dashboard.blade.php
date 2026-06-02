@extends('layouts.dashboard')
@section('title', 'Dashboard Wali Kelas')
@section('nav-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    {{-- Siswa Kelas --}}
    <div class="bg-surface rounded-3xl p-6 border border-outline-variant/30 shadow-sm flex items-center gap-4 transition-transform hover:-translate-y-1 duration-300">
        <div class="w-12 h-12 bg-primary-container text-on-primary-container rounded-2xl flex items-center justify-center flex-shrink-0 shadow-inner">
            <span class="material-symbols-outlined text-[1.75rem]">groups</span>
        </div>
        <div>
            <p class="text-xs text-on-surface-variant font-bold uppercase tracking-wider mb-0.5">Siswa Kelas</p>
            <p class="text-3xl font-bold text-on-surface leading-none">{{ $total_siswa }}</p>
        </div>
    </div>
    {{-- Sesi Aktif --}}
    <div class="bg-surface rounded-3xl p-6 border border-outline-variant/30 shadow-sm flex items-center gap-4 transition-transform hover:-translate-y-1 duration-300">
        <div class="w-12 h-12 bg-secondary-container text-on-secondary-container rounded-2xl flex items-center justify-center flex-shrink-0 shadow-inner">
            <span class="material-symbols-outlined text-[1.75rem]">event_available</span>
        </div>
        <div>
            <p class="text-xs text-on-surface-variant font-bold uppercase tracking-wider mb-0.5">Sesi Aktif</p>
            <p class="text-3xl font-bold text-on-surface leading-none">{{ $sesi_aktif }}</p>
        </div>
    </div>
    {{-- Menunggu --}}
    <div class="bg-surface rounded-3xl p-6 border border-outline-variant/30 shadow-sm flex items-center gap-4 transition-transform hover:-translate-y-1 duration-300">
        <div class="w-12 h-12 bg-tertiary-container text-on-tertiary-container rounded-2xl flex items-center justify-center flex-shrink-0 shadow-inner">
            <span class="material-symbols-outlined text-[1.75rem]">pending_actions</span>
        </div>
        <div>
            <p class="text-xs text-on-surface-variant font-bold uppercase tracking-wider mb-0.5">Menunggu</p>
            <p class="text-3xl font-bold text-on-surface leading-none">{{ $menunggu }}</p>
        </div>
    </div>
    {{-- Selesai --}}
    <div class="bg-surface rounded-3xl p-6 border border-outline-variant/30 shadow-sm flex items-center gap-4 transition-transform hover:-translate-y-1 duration-300">
        <div class="w-12 h-12 bg-green-100 text-green-700 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-inner">
            <span class="material-symbols-outlined text-[1.75rem]">task_alt</span>
        </div>
        <div>
            <p class="text-xs text-on-surface-variant font-bold uppercase tracking-wider mb-0.5">Selesai</p>
            <p class="text-3xl font-bold text-on-surface leading-none">{{ $selesai }}</p>
        </div>
    </div>
</div>

<div class="bg-surface rounded-3xl shadow-sm border border-outline-variant/30 overflow-hidden">
    <div class="p-6 border-b border-outline-variant/30 bg-surface-container-lowest">
        <h2 class="text-xl font-bold text-on-surface">Riwayat Konseling Terbaru</h2>
        <p class="text-sm text-on-surface-variant mt-1">Kelas {{ auth()->user()->kelas ?? 'Anda' }}</p>
    </div>
    <div class="divide-y divide-outline-variant/20">
        @forelse($riwayat as $k)
        @php
            $bc = match($k->status){ 'selesai'=>'bg-green-100 text-green-800','disetujui'=>'bg-secondary-container text-on-secondary-container','ditolak'=>'bg-error-container text-on-error-container', default=>'bg-tertiary-container text-on-tertiary-container' };
            $bl = match($k->status){ 'selesai'=>'Selesai','disetujui'=>'Berlangsung','ditolak'=>'Ditolak', default=>'Menunggu' };
            $icon = match($k->status){ 'selesai'=>'check_circle','disetujui'=>'schedule','ditolak'=>'cancel', default=>'hourglass_empty' };
        @endphp
        <div class="p-5 flex items-center gap-4 hover:bg-surface-container-lowest transition-colors">
            <div class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center flex-shrink-0 text-on-surface-variant">
                <span class="material-symbols-outlined text-[1.25rem]">{{ $icon }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-bold text-on-surface text-sm truncate">{{ $k->siswa->name }}</div>
                <div class="text-xs text-on-surface-variant truncate mt-0.5">{{ $k->jenis_masalah }}</div>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $bc }}">{{ $bl }}</span>
        </div>
        @empty
        <div class="p-8 text-center">
            <span class="material-symbols-outlined text-4xl text-outline mb-2">history</span>
            <p class="text-sm text-on-surface-variant font-medium">Belum ada riwayat konseling di kelas ini.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
