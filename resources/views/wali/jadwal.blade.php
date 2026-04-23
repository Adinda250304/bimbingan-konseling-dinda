@extends('layouts.dashboard')
@section('title', 'Jadwal Konseling')
@section('nav-title', 'Jadwal Konseling')

@section('content')
<div class="bg-white rounded-2xl shadow-sm p-5">
    <div class="flex items-center justify-between mb-4 flex-wrap gap-3">
        <div>
            <h2 class="text-blue-600 font-bold text-xl">Jadwal Konseling</h2>
            <p class="text-xs text-gray-400 mt-0.5">Kelas {{ $kelas ?? '—' }}</p>
        </div>
        <form method="GET">
            <select name="status" onchange="this.form.submit()"
                class="px-3 py-2 border-2 border-gray-200 rounded-xl text-xs text-gray-700 outline-none bg-white cursor-pointer">
                <option value="">Semua (Aktif)</option>
                <option value="menunggu"  {{ request('status')==='menunggu'  ? 'selected' : '' }}>Menunggu Persetujuan</option>
                <option value="disetujui" {{ request('status')==='disetujui' ? 'selected' : '' }}>Terjadwal</option>
            </select>
        </form>
    </div>

    @forelse($konselings as $k)
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
    <div class="border border-gray-100 rounded-xl p-4 mb-3 hover:shadow-sm transition">
        <div class="flex items-start justify-between gap-3">
            <div class="flex-1 min-w-0">
                <div class="font-bold text-sm text-gray-800">{{ $k->siswa->name }}</div>
                <div class="text-xs text-gray-500 mt-0.5">{{ $k->jenis_masalah }}</div>
                @if($k->tanggal_konseling)
                <div class="text-xs text-gray-400 mt-1">
                    {{ $k->tanggal_konseling->translatedFormat('l, d F Y') }}
                    @if($k->jam_konseling)
                    pukul {{ \Carbon\Carbon::parse($k->jam_konseling)->format('H:i') }}
                    @endif
                </div>
                @else
                <div class="text-xs text-gray-400 mt-1">Menunggu admin menentukan jadwal</div>
                @endif
            </div>
            <span class="flex-shrink-0 px-3 py-1 rounded-full text-xs font-semibold {{ $bc }}">{{ $bl }}</span>
        </div>
    </div>
    @empty
    <div class="py-12 text-center text-gray-400">
        <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
            <svg class="w-7 h-7 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5"/>
            </svg>
        </div>
        <p class="text-sm font-medium text-gray-500">Tidak ada jadwal konseling aktif</p>
        <p class="text-xs text-gray-400 mt-1">Semua sesi siswa di kelas ini sudah selesai atau belum ada pengajuan.</p>
    </div>
    @endforelse
</div>
@endsection
