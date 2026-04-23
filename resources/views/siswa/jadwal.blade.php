@extends('layouts.dashboard')
@section('title', 'Jadwal Konseling')
@section('nav-title', 'Jadwal Konseling')

@section('content')

{{-- Sesi siswa yang sedang aktif --}}
@if($konselings->isNotEmpty())
<div class="bg-white rounded-2xl shadow-sm p-5 mb-5">
    <h2 class="text-blue-600 font-bold text-base mb-4">Sesi Konseling Kamu</h2>

    @foreach($konselings as $k)
    @php
        $bc = match($k->status) {
            'disetujui' => 'bg-green-100 text-green-700',
            'menunggu'  => 'bg-yellow-100 text-yellow-700',
            default     => 'bg-gray-100 text-gray-500',
        };
        $bl = match($k->status) {
            'disetujui' => 'Terjadwal',
            'menunggu'  => 'Menunggu Persetujuan',
            default     => $k->status,
        };
    @endphp
    <div class="border border-gray-100 rounded-xl p-4 mb-3 last:mb-0">
        <div class="flex items-start justify-between gap-3">
            <div class="flex-1 min-w-0">
                <div class="font-bold text-sm text-gray-800">{{ $k->jenis_masalah }}</div>
                <div class="text-xs text-gray-500 mt-0.5">Diajukan {{ $k->created_at->diffForHumans() }}</div>

                @if($k->status === 'disetujui' && $k->tanggal_konseling)
                <div class="mt-2 bg-green-50 border border-green-100 rounded-lg px-3 py-2 text-xs">
                    <div class="font-semibold text-green-700 mb-0.5">Jadwal Dikonfirmasi</div>
                    <div class="text-green-600">
                        {{ $k->tanggal_konseling->translatedFormat('l, d F Y') }}
                        @if($k->jam_konseling)
                        · pukul {{ \Carbon\Carbon::parse($k->jam_konseling)->format('H:i') }}
                        @endif
                        @if($k->tempat)
                        · {{ $k->tempat }}
                        @endif
                    </div>
                </div>
                @elseif($k->status === 'menunggu')
                <div class="mt-2 text-xs text-yellow-600">
                    Guru BK akan menentukan jadwal untukmu segera.
                </div>
                @endif
            </div>
            <span class="flex-shrink-0 px-3 py-1 rounded-full text-xs font-semibold {{ $bc }}">{{ $bl }}</span>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="bg-white rounded-2xl shadow-sm p-5 mb-5">
    <div class="py-8 text-center text-gray-400">
        <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
            <svg class="w-7 h-7 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5"/>
            </svg>
        </div>
        <p class="text-sm font-medium text-gray-500">Tidak ada jadwal aktif</p>
        <p class="text-xs text-gray-400 mt-1">Kamu belum punya sesi konseling yang sedang berjalan.</p>
        <a href="{{ route('siswa.pengajuan') }}"
           class="mt-4 inline-block text-sm font-semibold text-blue-600 hover:underline">
            Ajukan Konseling Sekarang
        </a>
    </div>
</div>
@endif

@endsection
