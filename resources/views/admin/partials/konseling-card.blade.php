{{-- Partial: admin/partials/konseling-card.blade.php --}}
@php
    $s = match($k->status) {
        'selesai'   => ['label' => 'Selesai',     'cls' => 'bg-green-100 text-green-700'],
        'disetujui' => ['label' => 'Berlangsung', 'cls' => 'bg-pink-100 text-pink-700'],
        default     => ['label' => 'Terjadwal',   'cls' => 'bg-gray-200 text-gray-600'],
    };
    $tgl  = $k->tanggal_konseling
        ? $k->tanggal_konseling->translatedFormat('l, j F Y')
        : ($k->jadwal?->hari ?? '—');
    $jam  = $k->jam_konseling
        ? \Carbon\Carbon::parse($k->jam_konseling)->format('H:i')
        : ($k->jadwal ? \Carbon\Carbon::parse($k->jadwal->jam_mulai)->format('H:i') : '—');
    $room = $k->jadwal?->tempat ?? ($k->jenis === 'online' ? 'Online' : 'Ruang BK');
@endphp

<div class="flex items-start gap-3 bg-gray-100 rounded-xl p-3.5 mb-2 flex-wrap">
    {{-- Avatar --}}
    <div class="w-9 h-9 bg-gray-300 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
    </div>

    {{-- Info --}}
    <div class="flex-1 min-w-[140px]">
        <div class="font-bold text-sm text-gray-800">{{ $k->siswa->name }}</div>
        <div class="text-xs text-gray-500">{{ $k->siswa->kelas ?? '—' }}</div>
        <div class="text-xs text-gray-600 mt-0.5">{{ $k->jenis_masalah }}</div>
        <div class="text-xs text-gray-400 mt-1">{{ $tgl }} — {{ $jam }} — {{ $room }}</div>
    </div>

    {{-- Right Actions --}}
    <div class="flex flex-col items-end gap-2">
        <div class="flex items-center gap-1.5 flex-wrap justify-end">
            <a href="{{ route('admin.konseling.show', $k) }}"
               class="px-3 py-1 rounded-lg border border-gray-300 bg-white text-xs font-semibold text-gray-600 hover:border-gray-500 transition">
                Cetak
            </a>
            <span class="px-3 py-0.5 rounded-full text-xs font-bold {{ $s['cls'] }}">{{ $s['label'] }}</span>

            {{-- Dropdown --}}
            <div class="dd-wrap relative">
                <button onclick="toggleDdK('kdd-{{ $k->id }}')"
                    class="flex items-center gap-1 px-3 py-1.5 border-2 border-gray-300 bg-white rounded-lg text-xs font-semibold text-gray-700 hover:border-gray-500 transition">
                    Ubah
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                    </svg>
                </button>
                <div id="kdd-{{ $k->id }}" class="dd-menu hidden absolute right-0 mt-1 w-36 bg-white border border-gray-200 rounded-xl shadow-lg z-10 overflow-hidden">
                    <button class="w-full text-left px-4 py-2.5 text-xs font-medium text-gray-700 hover:bg-gray-50 transition"
                        onclick="openEditKonseling(
                            {{ $k->id }}, {{ $k->siswa_id }},
                            '{{ $k->tanggal_konseling?->format('Y-m-d') ?? '' }}',
                            '{{ $k->jam_konseling ? \Carbon\Carbon::parse($k->jam_konseling)->format('H:i') : '' }}',
                            '{{ addslashes($k->jenis_masalah) }}',
                            '{{ $k->status }}'
                        );toggleDdK('kdd-{{ $k->id }}')">
                        Edit Jadwal
                    </button>
                    <button class="w-full text-left px-4 py-2.5 text-xs font-medium text-red-600 hover:bg-red-50 transition border-t border-gray-100"
                        onclick="openHapusKonseling({{ $k->id }});toggleDdK('kdd-{{ $k->id }}')">
                        Hapus
                    </button>
                </div>
            </div>
        </div>

        @if(in_array($k->status, ['disetujui', 'selesai']))
        <button onclick="openHasil(
            {{ $k->id }},
            '{{ addslashes($k->siswa->name) }}',
            '{{ addslashes($k->siswa->kelas ?? '—') }}',
            '{{ addslashes($k->jenis_masalah) }}',
            '{{ addslashes($k->hasil?->catatan_konselor ?? '') }}',
            '{{ addslashes($k->hasil?->saran ?? '') }}'
        )" class="px-4 py-1.5 border-2 border-gray-300 bg-white rounded-lg text-xs font-semibold text-gray-700 hover:border-gray-500 transition whitespace-nowrap">
            Hasil &amp; Saran
        </button>
        @endif
    </div>
</div>
