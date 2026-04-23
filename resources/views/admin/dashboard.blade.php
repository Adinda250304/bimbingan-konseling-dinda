@extends('layouts.admin')
@section('title', 'Dashboard Guru BK')
@section('nav-title', 'Dashboard')

@section('content')
{{-- 4-kolom di desktop, 2-kolom di mobile --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    {{-- Total Siswa --}}
    <div class="bg-teal-300/70 rounded-2xl p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-white/70 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-600 font-medium leading-tight">Total Siswa</p>
            <p class="text-3xl font-bold text-gray-800 mt-0.5">{{ $total_siswa }}</p>
        </div>
    </div>
    {{-- Sesi Hari Ini --}}
    <div class="bg-pink-200/80 rounded-2xl p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-white/70 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5m-9-6h.008v.008H12V12zm0 3h.008v.008H12V15zm0 3h.008v.008H12V18zm-3-6h.008v.008H9V12zm0 3h.008v.008H9V15zm0 3h.008v.008H9V18zm6-6h.008v.008H15V12zm0 3h.008v.008H15V15z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-600 font-medium leading-tight">Sesi Hari Ini</p>
            <p class="text-3xl font-bold text-gray-800 mt-0.5">{{ $sesi_hari_ini }}</p>
        </div>
    </div>
    {{-- Menunggu Jadwal --}}
    <div class="bg-amber-100/90 rounded-2xl p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-white/70 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-600 font-medium leading-tight">Menunggu Jadwal</p>
            <p class="text-3xl font-bold text-gray-800 mt-0.5">{{ $menunggu }}</p>
        </div>
    </div>
    {{-- Selesai Bulan Ini --}}
    <div class="bg-violet-200/80 rounded-2xl p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-white/70 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-600 font-medium leading-tight">Selesai Bulan Ini</p>
            <p class="text-3xl font-bold text-gray-800 mt-0.5">{{ $selesai_bulan_ini }}</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm p-5">
    <h2 class="text-blue-600 font-bold text-base mb-4">Pengajuan Terbaru</h2>
    @if($recent_konselings->isEmpty())
        <p class="text-center text-gray-400 py-8 text-sm">Belum ada pengajuan.</p>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase">
                    <th class="py-3 px-4 text-left font-semibold rounded-l-xl">Siswa</th>
                    <th class="py-3 px-4 text-left font-semibold hidden md:table-cell">Kelas</th>
                    <th class="py-3 px-4 text-left font-semibold">Masalah</th>
                    <th class="py-3 px-4 text-center font-semibold">Status</th>
                    <th class="py-3 px-4 text-center font-semibold rounded-r-xl">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($recent_konselings as $k)
                @php
                    $bc = match($k->status) { 'selesai'=>'bg-green-100 text-green-700','disetujui'=>'bg-pink-100 text-pink-700','ditolak'=>'bg-red-100 text-red-700', default=>'bg-yellow-100 text-yellow-700' };
                    $bl = match($k->status) { 'selesai'=>'Selesai','disetujui'=>'Berlangsung','ditolak'=>'Ditolak', default=>'Menunggu' };
                @endphp
                <tr class="hover:bg-gray-50 transition">
                    <td class="py-3 px-4 font-medium text-gray-800">{{ $k->siswa->name }}</td>
                    <td class="py-3 px-4 text-gray-500 hidden md:table-cell">{{ $k->siswa->kelas ?? '—' }}</td>
                    <td class="py-3 px-4 text-gray-600">{{ $k->jenis_masalah }}</td>
                    <td class="py-3 px-4 text-center">
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $bc }}">{{ $bl }}</span>
                    </td>
                    <td class="py-3 px-4 text-center">
                        <a href="{{ route('admin.konseling.show', $k) }}" class="text-blue-600 font-semibold text-xs hover:underline">Review</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
