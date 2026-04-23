@extends('layouts.dashboard')
@section('title', 'Dashboard Wali Kelas')
@section('nav-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    {{-- Siswa Kelas --}}
    <div class="bg-teal-300/70 rounded-2xl p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-white/70 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-600 font-medium leading-tight">Siswa Kelas</p>
            <p class="text-3xl font-bold text-gray-800 mt-0.5">{{ $total_siswa }}</p>
        </div>
    </div>
    {{-- Sesi Aktif --}}
    <div class="bg-pink-200/80 rounded-2xl p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-white/70 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5m-9-6h.008v.008H12V12zm0 3h.008v.008H12V15zm0 3h.008v.008H12V18zm-3-6h.008v.008H9V12zm0 3h.008v.008H9V15zm0 3h.008v.008H9V18zm6-6h.008v.008H15V12zm0 3h.008v.008H15V15z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-600 font-medium leading-tight">Sesi Aktif</p>
            <p class="text-3xl font-bold text-gray-800 mt-0.5">{{ $sesi_aktif }}</p>
        </div>
    </div>
    {{-- Menunggu --}}
    <div class="bg-amber-100/90 rounded-2xl p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-white/70 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-600 font-medium leading-tight">Menunggu</p>
            <p class="text-3xl font-bold text-gray-800 mt-0.5">{{ $menunggu }}</p>
        </div>
    </div>
    {{-- Selesai --}}
    <div class="bg-violet-200/80 rounded-2xl p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-white/70 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-600 font-medium leading-tight">Selesai</p>
            <p class="text-3xl font-bold text-gray-800 mt-0.5">{{ $selesai }}</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm p-5">
    <h2 class="text-blue-600 font-bold text-base mb-4">Riwayat Konseling — {{ auth()->user()->kelas ?? 'Kelas Anda' }}</h2>
    @forelse($riwayat as $k)
    @php
        $bc = match($k->status){ 'selesai'=>'bg-green-100 text-green-700','disetujui'=>'bg-pink-100 text-pink-700','ditolak'=>'bg-red-100 text-red-700', default=>'bg-yellow-100 text-yellow-700' };
        $bl = match($k->status){ 'selesai'=>'Selesai','disetujui'=>'Berlangsung','ditolak'=>'Ditolak', default=>'Menunggu' };
    @endphp
    <div class="flex items-center gap-3 py-3 border-b border-gray-50 last:border-b-0">
        <div class="flex-1 min-w-0">
            <div class="font-semibold text-sm text-gray-800">{{ $k->siswa->name }}</div>
            <div class="text-xs text-gray-400">{{ $k->jenis_masalah }}</div>
        </div>
        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $bc }}">{{ $bl }}</span>
    </div>
    @empty
    <p class="text-center text-gray-400 py-8 text-sm">Belum ada riwayat konseling di kelas ini.</p>
    @endforelse
</div>
@endsection
