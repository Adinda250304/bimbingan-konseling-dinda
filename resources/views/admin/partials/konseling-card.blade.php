{{-- Partial: admin/partials/konseling-card.blade.php --}}
@php
    $s = match($k->status) {
        'selesai'   => ['label' => 'Selesai',     'cls' => 'bg-green-100 text-green-800'],
        'disetujui' => ['label' => 'Berlangsung', 'cls' => 'bg-secondary-container text-on-secondary-container'],
        default     => ['label' => 'Terjadwal',   'cls' => 'bg-surface-container-high text-on-surface'],
    };
    $tgl  = $k->tanggal_konseling
        ? $k->tanggal_konseling->translatedFormat('l, j F Y')
        : ($k->jadwal?->hari ?? '—');
    $jam  = $k->jam_konseling
        ? \Carbon\Carbon::parse($k->jam_konseling)->format('H:i')
        : ($k->jadwal ? \Carbon\Carbon::parse($k->jadwal->jam_mulai)->format('H:i') : '—');
    $room = $k->tempat ?? ($k->jadwal?->tempat ?? ($k->jenis === 'online' ? 'Online' : 'Ruang BK'));
@endphp

<div class="flex items-start gap-4 bg-surface-container-lowest border border-outline-variant/30 rounded-2xl p-4 mb-3 flex-wrap hover:bg-surface-container-low transition-colors shadow-sm">
    {{-- Avatar --}}
    <div class="w-10 h-10 bg-surface-container-highest rounded-full flex items-center justify-center flex-shrink-0 text-on-surface-variant">
        <span class="material-symbols-outlined text-[20px]">person</span>
    </div>

    {{-- Info --}}
    <div class="flex-1 min-w-[140px]">
        <div class="font-bold text-base text-on-surface">{{ $k->siswa->name }}</div>
        <div class="text-xs font-medium text-on-surface-variant flex items-center gap-2 mt-0.5">
            <span class="inline-flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">school</span> {{ $k->siswa->kelas ?? '—' }}</span>
            <span>•</span>
            <span class="inline-flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">category</span> {{ $k->jenis_masalah }}</span>
        </div>
        <div class="text-xs text-on-surface-variant mt-1.5 flex items-center gap-2">
            <span class="inline-flex items-center gap-1 bg-surface-container py-0.5 px-2 rounded-md"><span class="material-symbols-outlined text-[14px]">event</span> {{ $tgl }}</span>
            <span class="inline-flex items-center gap-1 bg-surface-container py-0.5 px-2 rounded-md"><span class="material-symbols-outlined text-[14px]">schedule</span> {{ $jam }}</span>
            <span class="inline-flex items-center gap-1 bg-surface-container py-0.5 px-2 rounded-md"><span class="material-symbols-outlined text-[14px]">meeting_room</span> {{ $room }}</span>
        </div>
    </div>

    {{-- Right Actions --}}
    <div class="flex flex-col items-end gap-2.5">
        <div class="flex items-center gap-2 flex-wrap justify-end">
            <a href="{{ route('admin.konseling.show', $k) }}"
               class="px-3 py-1.5 rounded-xl border border-outline-variant/50 bg-surface-container-lowest text-xs font-bold text-on-surface-variant hover:bg-surface-container-high transition-colors flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px]">print</span> Cetak
            </a>
            <span class="px-3 py-1 rounded-full text-[11px] font-bold tracking-wide uppercase {{ $s['cls'] }}">{{ $s['label'] }}</span>

            {{-- Dropdown --}}
            <div class="dd-wrap relative">
                <button onclick="toggleDdK('kdd-{{ $k->id }}')"
                    class="flex items-center gap-1 px-3 py-1.5 border border-outline-variant/50 bg-surface-container-lowest rounded-xl text-xs font-bold text-on-surface hover:bg-surface-container-high transition-colors">
                    Ubah
                    <span class="material-symbols-outlined text-[16px]">expand_more</span>
                </button>
                <div id="kdd-{{ $k->id }}" class="dd-menu hidden absolute right-0 mt-2 w-40 bg-surface-container-lowest border border-outline-variant/50 rounded-2xl shadow-lg z-10 overflow-hidden py-1">
                    <button class="w-full flex items-center gap-2 px-4 py-3 text-xs font-bold text-on-surface hover:bg-surface-container-high transition-colors"
                        onclick="openEditKonseling(
                            {{ $k->id }}, {{ $k->siswa_id }},
                            '{{ $k->tanggal_konseling?->format('Y-m-d') ?? '' }}',
                            '{{ $k->jam_konseling ? \Carbon\Carbon::parse($k->jam_konseling)->format('H:i') : '' }}',
                            '{{ addslashes($k->jenis_masalah) }}',
                            '{{ $k->status }}'
                        );toggleDdK('kdd-{{ $k->id }}')">
                        <span class="material-symbols-outlined text-[18px]">edit_calendar</span> Edit Jadwal
                    </button>
                    <button class="w-full flex items-center gap-2 px-4 py-3 text-xs font-bold text-error hover:bg-error/10 transition-colors"
                        onclick="openHapusKonseling({{ $k->id }});toggleDdK('kdd-{{ $k->id }}')">
                        <span class="material-symbols-outlined text-[18px]">delete</span> Hapus
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
        )" class="px-4 py-2 border border-outline-variant/50 bg-surface-container-lowest rounded-xl text-xs font-bold text-on-surface hover:bg-surface-container-high transition-colors flex items-center gap-1.5 shadow-sm whitespace-nowrap">
            <span class="material-symbols-outlined text-[16px]">assignment_turned_in</span> Hasil &amp; Saran
        </button>
        @endif
    </div>
</div>
