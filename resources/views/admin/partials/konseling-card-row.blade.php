{{-- Partial: admin/partials/konseling-card-row.blade.php --}}
@php
    $statusLabel = match($k->status) {
        'selesai'   => ['label' => 'Selesai',     'class' => 'badge-selesai'],
        'disetujui' => ['label' => 'Berlangsung', 'class' => 'badge-berlangsung'],
        'menunggu'  => ['label' => 'Terjadwal',   'class' => 'badge-terjadwal'],
        default     => ['label' => ucfirst($k->status), 'class' => 'badge-terjadwal'],
    };
    $tanggalFormat = $k->tanggal_konseling
        ? $k->tanggal_konseling->translatedFormat('l, j F Y')
        : ($k->jadwal ? $k->jadwal->hari : '—');
    $jamFormat = $k->jam_konseling
        ? \Carbon\Carbon::parse($k->jam_konseling)->format('H.i')
        : ($k->jadwal ? \Carbon\Carbon::parse($k->jadwal->jam_mulai)->format('H.i') : '—');
    $tempatFormat = $k->jadwal ? $k->jadwal->tempat : ($k->jenis === 'online' ? 'Online' : 'Ruang BK');
@endphp

<div class="k-card">
    {{-- Avatar --}}
    <div class="k-avatar">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
    </div>

    {{-- Info --}}
    <div class="k-info">
        <div class="k-name">{{ $k->siswa->name }}</div>
        <div class="k-kelas">{{ $k->siswa->kelas ?? '—' }}</div>
        <div class="k-masalah">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75m-7.5 6H18a2.25 2.25 0 002.25-2.25V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v13.5A2.25 2.25 0 006 21.75z"/>
            </svg>
            {{ $k->jenis_masalah }}
        </div>
    </div>

    {{-- Right --}}
    <div class="k-right">
        {{-- Top row: Cetak | Status | Edit dropdown --}}
        <div class="k-top">
            <a href="{{ route('admin.konseling.show', $k) }}" class="btn-cetak">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z"/>
                </svg>
                Cetak
            </a>

            <span class="{{ $statusLabel['class'] }}">{{ $statusLabel['label'] }}</span>

            {{-- Edit dropdown --}}
            <div class="edit-dropdown">
                <button class="edit-btn" onclick="toggleDropdown('kdd-{{ $k->id }}')">
                    Edit
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" width="13" height="13">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                    </svg>
                </button>
                <div class="dropdown-menu" id="kdd-{{ $k->id }}">
                    <button class="dropdown-item"
                        onclick="
                            openEditKonseling(
                                {{ $k->id }},
                                {{ $k->siswa_id }},
                                '{{ $k->tanggal_konseling ? $k->tanggal_konseling->format('Y-m-d') : '' }}',
                                '{{ $k->jam_konseling ? \Carbon\Carbon::parse($k->jam_konseling)->format('H:i') : '' }}',
                                '{{ addslashes($k->jenis_masalah) }}',
                                '{{ $k->status }}'
                            );
                            document.getElementById('kdd-{{ $k->id }}').classList.remove('open')
                        ">
                        ✏️ Edit Jadwal
                    </button>
                    <button class="dropdown-item danger"
                        onclick="
                            openHapusKonseling({{ $k->id }});
                            document.getElementById('kdd-{{ $k->id }}').classList.remove('open')
                        ">
                        🗑 Hapus
                    </button>
                </div>
            </div>
        </div>

        {{-- Date/time meta --}}
        <div class="k-meta">
            <span>
                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                </svg>
                {{ $tanggalFormat }}
            </span>
            <span>
                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ $jamFormat }} {{ $tempatFormat }}
            </span>
        </div>

        {{-- Hasil & Saran --}}
        @if(in_array($k->status, ['disetujui', 'selesai']))
        <button class="btn-hasil"
            onclick="openHasil(
                {{ $k->id }},
                '{{ addslashes($k->siswa->name) }}',
                '{{ addslashes($k->siswa->kelas ?? '—') }}',
                '{{ addslashes($k->jenis_masalah) }}',
                '{{ addslashes($k->hasil?->catatan_konselor ?? '') }}',
                '{{ addslashes($k->hasil?->saran ?? '') }}'
            )">
            Hasil & Saran
        </button>
        @endif
    </div>
</div>
