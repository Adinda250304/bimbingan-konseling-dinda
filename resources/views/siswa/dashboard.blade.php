@extends('layouts.dashboard')
@section('title', 'Dashboard Siswa')
@section('nav-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    {{-- Sesi Aktif --}}
    <div class="bg-teal-300/70 rounded-2xl p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-white/70 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5m-9-6h.008v.008H12V12zm0 3h.008v.008H12V15zm0 3h.008v.008H12V18zm-3-6h.008v.008H9V12zm0 3h.008v.008H9V15zm0 3h.008v.008H9V18zm6-6h.008v.008H15V12zm0 3h.008v.008H15V15z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-600 font-medium leading-tight">Sesi Aktif</p>
            <p class="text-3xl font-bold text-gray-800 mt-0.5">{{ $sesi_aktif }}</p>
        </div>
    </div>
    {{-- Menunggu --}}
    <div class="bg-pink-200/80 rounded-2xl p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-white/70 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-600 font-medium leading-tight">Menunggu</p>
            <p class="text-3xl font-bold text-gray-800 mt-0.5">{{ $menunggu }}</p>
        </div>
    </div>
    {{-- Selesai --}}
    <div class="bg-amber-100/90 rounded-2xl p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-white/70 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-600 font-medium leading-tight">Selesai</p>
            <p class="text-3xl font-bold text-gray-800 mt-0.5">{{ $selesai }}</p>
        </div>
    </div>
    {{-- Total Sesi --}}
    <div class="bg-violet-200/80 rounded-2xl p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-white/70 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-600 font-medium leading-tight">Total Sesi</p>
            <p class="text-3xl font-bold text-gray-800 mt-0.5">{{ $total }}</p>
        </div>
    </div>
</div>

<div class="grid md:grid-cols-3 gap-4">
    {{-- Ajukan banner --}}
    <div class="md:col-span-1 bg-blue-600 rounded-2xl p-6 flex flex-col justify-between gap-4 shadow-sm shadow-blue-200">
        <div>
            <p class="text-white font-bold text-lg">Butuh Bantuan?</p>
            <p class="text-blue-100 text-sm mt-1">Ajukan sesi konseling dengan guru BK.</p>
        </div>
        <a href="{{ route('siswa.pengajuan') }}"
           class="self-start bg-white text-blue-600 font-bold text-sm px-5 py-2.5 rounded-xl hover:bg-blue-50 transition">
            Ajukan Sekarang
        </a>
    </div>

    {{-- Riwayat --}}
    <div class="md:col-span-2 bg-white rounded-2xl shadow-sm p-5">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-blue-600 font-bold text-base">Riwayat Konseling</h2>
            <a href="{{ route('siswa.riwayat') }}" class="text-xs text-gray-400 hover:text-blue-500">Lihat semua</a>
        </div>
        @forelse($riwayat as $k)
        @php
            $bc = match($k->status){ 'selesai'=>'bg-green-100 text-green-700','disetujui'=>'bg-pink-100 text-pink-700','ditolak'=>'bg-red-100 text-red-700', default=>'bg-yellow-100 text-yellow-700' };
            $bl = match($k->status){ 'selesai'=>'Selesai','disetujui'=>'Berlangsung','ditolak'=>'Ditolak', default=>'Menunggu' };
        @endphp
        <div class="flex items-center gap-3 py-2.5 border-b border-gray-50 last:border-b-0">
            <div class="flex-1 min-w-0">
                <div class="font-semibold text-sm text-gray-800 truncate">{{ $k->jenis_masalah }}</div>
                <div class="text-xs text-gray-400">{{ $k->created_at->format('d M Y') }}</div>
            </div>
            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $bc }}">{{ $bl }}</span>
        </div>
        @empty
        <p class="text-center text-gray-400 py-8 text-sm">Belum ada riwayat konseling.</p>
        @endforelse
    </div>
</div>
@endsection
