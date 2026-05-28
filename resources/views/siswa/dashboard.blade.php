@extends('layouts.dashboard')
@section('title', 'Dashboard Siswa')
@section('nav-title', 'Dashboard')

@section('content')
<!-- Welcome Banner -->
<section class="mb-section-margin bg-surface-container-lowest rounded-2xl p-8 border border-surface-variant shadow-subtle relative overflow-hidden flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
    <div class="absolute right-0 top-0 w-1/2 h-full bg-gradient-to-l from-secondary-container/30 to-transparent pointer-events-none"></div>
    <div class="relative z-10 max-w-2xl">
        <h2 class="font-headline-lg text-headline-lg text-primary mb-2">Halo, {{ auth()->user()->name }}!</h2>
        <p class="font-body-md text-body-md text-on-surface-variant">Ada yang ingin kamu ceritakan atau konsultasikan hari ini? Teman BK selalu ada untukmu.</p>
    </div>
    <a href="{{ route('siswa.pengajuan') }}" 
       class="relative z-10 w-full sm:w-auto bg-primary hover:bg-primary-container text-white rounded-xl px-6 py-3 flex items-center justify-center gap-2 font-bold text-sm transition-colors shadow-md shrink-0">
        <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'wght' 600;">add</span>
        Ajukan Konseling Baru
    </a>
</section>

<!-- Key Metrics Grid -->
<section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-card-gap mb-section-margin">
    <!-- Metric 1: Next Session -->
    <div class="bg-surface-container-lowest rounded-2xl p-6 border border-surface-variant shadow-subtle">
        <div class="flex items-start justify-between mb-4">
            <div class="p-3 bg-primary-container/10 rounded-xl text-primary flex items-center justify-center">
                <span class="material-symbols-outlined">schedule</span>
            </div>
            @if($sesi_terdekat)
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-[#E8F0FE] text-[#1A73E8] font-label-md text-label-md">
                    Terjadwal
                </span>
            @else
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-surface-variant text-on-surface-variant font-label-md text-label-md">
                    Kosong
                </span>
            @endif
        </div>
        <p class="font-body-sm text-body-sm text-on-surface-variant mb-1">Sesi Terdekat</p>
        <p class="font-headline-md text-headline-md text-on-surface truncate">
            {{ $sesi_terdekat ? $sesi_terdekat->tanggal_konseling->translatedFormat('d M Y') : 'Tidak Ada Sesi' }}
        </p>
    </div>

    <!-- Metric 2: Completed Sessions -->
    <div class="bg-surface-container-lowest rounded-2xl p-6 border border-surface-variant shadow-subtle">
        <div class="flex items-start justify-between mb-4">
            <div class="p-3 bg-primary-container/10 rounded-xl text-primary flex items-center justify-center">
                <span class="material-symbols-outlined">task_alt</span>
            </div>
        </div>
        <p class="font-body-sm text-body-sm text-on-surface-variant mb-1">Total Sesi Selesai</p>
        <p class="font-headline-md text-headline-md text-on-surface">{{ $selesai }} Sesi</p>
    </div>

    <!-- Metric 3: Pending Requests -->
    <div class="bg-surface-container-lowest rounded-2xl p-6 border border-surface-variant shadow-subtle">
        <div class="flex items-start justify-between mb-4">
            <div class="p-3 bg-secondary-container/30 rounded-xl text-secondary flex items-center justify-center">
                <span class="material-symbols-outlined">hourglass_empty</span>
            </div>
            @if($menunggu > 0)
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-[#FFF8E1] text-[#F57F17] font-label-md text-label-md animate-pulse">
                    Menunggu
                </span>
            @endif
        </div>
        <p class="font-body-sm text-body-sm text-on-surface-variant mb-1">Pengajuan Aktif</p>
        <p class="font-headline-md text-headline-md text-on-surface">{{ $menunggu }} Menunggu</p>
    </div>

    <!-- Metric 4: BK Availability -->
    <div class="bg-surface-container-lowest rounded-2xl p-6 border border-surface-variant shadow-subtle">
        <div class="flex items-start justify-between mb-4">
            <div class="p-3 bg-primary-container/10 rounded-xl text-primary flex items-center justify-center">
                <span class="material-symbols-outlined">face</span>
            </div>
            @if($is_available)
                <div class="w-3 h-3 bg-primary rounded-full relative">
                    <div class="absolute inset-0 bg-primary rounded-full animate-ping opacity-75"></div>
                </div>
            @endif
        </div>
        <p class="font-body-sm text-body-sm text-on-surface-variant mb-1">Status Ketersediaan BK</p>
        <p class="font-headline-md text-headline-md text-on-surface truncate">
            {{ $is_available ? 'Guru BK Aktif' : 'Tidak Aktif' }}
        </p>
    </div>
</section>

<!-- Main Grid -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-card-gap">
    <!-- Left Column: Recent Activity (7 cols wide) -->
    <section class="lg:col-span-7 bg-surface-container-lowest rounded-2xl border border-surface-variant shadow-subtle p-6 flex flex-col">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold">Aktivitas Konseling Saya</h3>
            <a href="{{ route('siswa.riwayat') }}" 
               class="text-primary hover:text-primary-container font-label-md text-label-md transition-colors">Lihat Semua</a>
        </div>
        
        <div class="flex flex-col gap-4">
            @forelse($riwayat->take(3) as $k)
                @php
                    $bc = match($k->status) {
                        'selesai' => 'bg-[#E6F4EA] text-[#137333] border border-[#137333]/20',
                        'disetujui' => 'bg-[#E8F0FE] text-[#1A73E8] border border-[#1A73E8]/20',
                        'ditolak' => 'bg-[#FCE8E6] text-[#C5221F] border border-[#C5221F]/20',
                        default => 'bg-[#FFF8E1] text-[#F57F17] border border-[#F57F17]/20'
                    };
                    $bl = match($k->status) {
                        'selesai' => 'Selesai',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                        default => 'Menunggu'
                    };
                @endphp
                <!-- Session Item Card -->
                <div class="flex flex-col p-5 rounded-xl border border-outline-variant/40 hover:bg-surface-container-low transition-colors group">
                    <div class="flex items-center justify-between mb-3 flex-wrap gap-2">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-sm shrink-0">
                                {{ strtoupper(substr($k->guru ? $k->guru->name : 'BK', 0, 2)) }}
                            </div>
                            <div>
                                <h4 class="font-body-md text-body-md font-semibold text-on-surface">{{ $k->guru ? $k->guru->name : 'Guru BK' }}</h4>
                                <p class="font-body-sm text-body-sm text-on-surface-variant">
                                    {{ $k->tanggal_konseling ? $k->tanggal_konseling->translatedFormat('d M Y') : $k->created_at->translatedFormat('d M Y') }}
                                    @if($k->jam_konseling)
                                        • pukul {{ \Carbon\Carbon::parse($k->jam_konseling)->format('H:i') }} WIB
                                    @endif
                                    @if($k->tempat)
                                        • {{ $k->tempat }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full font-label-md text-label-md {{ $bc }}">{{ $bl }}</span>
                    </div>
                    
                    <div class="bg-surface-container-low p-4 rounded-xl border border-outline-variant/30 mt-2">
                        <p class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-1">Topik / Kategori Masalah: {{ $k->jenis_masalah }}</p>
                        <p class="text-sm text-on-surface leading-relaxed italic">"{{ $k->deskripsi_masalah }}"</p>
                    </div>

                    @if($k->status === 'selesai' && $k->hasil && $k->hasil->catatan_konselor)
                        <div class="bg-primary/5 p-4 rounded-xl border-l-4 border-primary mt-3">
                            <p class="text-[11px] font-bold text-primary uppercase tracking-wider mb-1">Catatan Hasil dari Guru BK:</p>
                            <p class="text-sm text-on-surface leading-relaxed">"{{ $k->hasil->catatan_konselor }}"</p>
                        </div>
                    @elseif($k->status === 'ditolak' && $k->alasan_penolakan)
                        <div class="bg-error/5 p-4 rounded-xl border-l-4 border-error mt-3">
                            <p class="text-[11px] font-bold text-error uppercase tracking-wider mb-1">Alasan Penolakan:</p>
                            <p class="text-sm text-on-surface leading-relaxed">"{{ $k->alasan_penolakan }}"</p>
                        </div>
                    @endif
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <span class="material-symbols-outlined text-outline-variant text-4xl mb-2">history</span>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Belum ada riwayat aktivitas konseling.</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Right Column: Articles (5 cols wide) -->
    <section class="lg:col-span-5 bg-surface-container-lowest rounded-2xl border border-surface-variant shadow-subtle p-6 flex flex-col">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold">Tips &amp; Artikel Kesehatan</h3>
            <a href="{{ route('siswa.artikel.index') }}" class="text-xs text-primary font-semibold hover:underline inline-flex items-center gap-1">
                Lihat Semua
                <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
            </a>
        </div>
        <div class="flex flex-col gap-4">
            @forelse($artikels as $artikel)
                <!-- Article Card -->
                <a href="{{ route('siswa.artikel.detail', $artikel->id) }}" class="flex gap-4 group cursor-pointer p-3 rounded-xl hover:bg-surface-container-low transition-colors border border-transparent hover:border-outline-variant/30">
                    <div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0 bg-surface-variant">
                        <img alt="{{ $artikel->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" src="{{ $artikel->thumbnail }}"/>
                    </div>
                    <div class="flex flex-col justify-center">
                        <h5 class="font-body-md text-body-md font-semibold text-on-surface group-hover:text-primary transition-colors line-clamp-2">{{ $artikel->judul }}</h5>
                        <p class="font-label-md text-label-md text-on-surface-variant mt-1.5">{{ $artikel->created_at->format('d M Y') }}</p>
                    </div>
                </a>
                @if(!$loop->last)
                    <hr class="border-outline-variant/30"/>
                @endif
            @empty
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <span class="material-symbols-outlined text-outline-variant text-4xl mb-2">article</span>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Belum ada artikel yang diterbitkan.</p>
                </div>
            @endforelse
        </div>
    </section>
</div>
@endsection
