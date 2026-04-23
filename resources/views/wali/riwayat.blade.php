@extends('layouts.dashboard')
@section('title', 'Riwayat Konseling Kelas')
@section('nav-title', 'Riwayat Kelas')

@section('content')
<div class="bg-white rounded-2xl shadow-sm p-5">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-blue-600 font-bold text-xl">Riwayat — {{ $kelas ?? 'Kelas Anda' }}</h2>
        <form method="GET">
            <div class="relative">
                <select name="status" onchange="this.form.submit()"
                    class="appearance-none pl-3 pr-8 py-2 border-2 border-gray-200 rounded-xl text-xs font-poppins text-gray-700 outline-none bg-white cursor-pointer focus:border-blue-400 transition">
                    <option value="">Semua Status</option>
                    <option value="menunggu"  {{ request('status')==='menunggu'  ? 'selected' : '' }}>Menunggu</option>
                    <option value="disetujui" {{ request('status')==='disetujui' ? 'selected' : '' }}>Berlangsung</option>
                    <option value="selesai"   {{ request('status')==='selesai'   ? 'selected' : '' }}>Selesai</option>
                    <option value="ditolak"   {{ request('status')==='ditolak'   ? 'selected' : '' }}>Ditolak</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-2 flex items-center">
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                    </svg>
                </div>
            </div>
        </form>
    </div>

    @forelse($konselings as $k)
    @php
        $bc = match($k->status){ 'selesai'=>'bg-green-100 text-green-700','disetujui'=>'bg-pink-100 text-pink-700','ditolak'=>'bg-red-100 text-red-700', default=>'bg-yellow-100 text-yellow-700' };
        $bl = match($k->status){ 'selesai'=>'Selesai','disetujui'=>'Berlangsung','ditolak'=>'Ditolak', default=>'Menunggu' };
    @endphp
    <div class="border border-gray-100 rounded-xl p-4 mb-3 hover:shadow-sm transition">
        {{-- Header card --}}
        <div class="flex items-start justify-between gap-3">
            <div class="flex-1 min-w-0">
                <div class="font-bold text-sm text-gray-800">{{ $k->siswa->name }}
                    <span class="text-xs text-gray-400 font-normal ml-1">{{ $k->siswa->kelas ?? '' }}</span>
                </div>
                <div class="text-xs text-gray-500 mt-0.5">{{ $k->jenis_masalah }}</div>
                <div class="text-xs text-gray-400">{{ $k->created_at->format('d M Y') }}</div>
            </div>
            <span class="flex-shrink-0 px-3 py-1 rounded-full text-xs font-semibold {{ $bc }}">{{ $bl }}</span>
        </div>

        {{-- Hasil Review Guru BK (hanya tampil jika selesai dan ada hasil) --}}
        @if($k->status === 'selesai' && $k->hasil)
        <div class="mt-3 pt-3 border-t border-gray-100 space-y-2.5">
            <p class="text-xs font-bold text-blue-600 uppercase tracking-wide">Hasil Review Guru BK</p>

            <div>
                <p class="text-xs font-semibold text-gray-500 mb-0.5">Catatan Konselor</p>
                <p class="text-sm text-gray-700 bg-gray-50 rounded-lg px-3 py-2">{{ $k->hasil->catatan_konselor }}</p>
            </div>

            <div>
                <p class="text-xs font-semibold text-gray-500 mb-0.5">Saran untuk Siswa</p>
                <p class="text-sm text-gray-700 bg-blue-50 rounded-lg px-3 py-2 text-blue-800">{{ $k->hasil->saran }}</p>
            </div>

            @if($k->hasil->tindak_lanjut)
            <div>
                <p class="text-xs font-semibold text-gray-500 mb-0.5">Tindak Lanjut</p>
                <p class="text-sm text-gray-700 bg-amber-50 rounded-lg px-3 py-2 text-amber-800">{{ $k->hasil->tindak_lanjut }}</p>
            </div>
            @endif
        </div>
        @elseif($k->status === 'ditolak' && $k->alasan_penolakan)
        <div class="mt-3 pt-3 border-t border-gray-100">
            <p class="text-xs font-semibold text-red-500 mb-0.5">Alasan Penolakan</p>
            <p class="text-sm text-red-700 bg-red-50 rounded-lg px-3 py-2">{{ $k->alasan_penolakan }}</p>
        </div>
        @endif
    </div>
    @empty
    <div class="py-12 text-center text-gray-400 text-sm">Belum ada riwayat konseling.</div>
    @endforelse

    @if($konselings->hasPages())
    <div class="mt-4 flex justify-center">{{ $konselings->links() }}</div>
    @endif
</div>
@endsection
