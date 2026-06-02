@extends('layouts.dashboard')
@section('title', 'Jadwal Konseling')
@section('nav-title', 'Jadwal Konseling')

@section('content')

@php
    // Find today's active session
    $sesi_hari_ini = $konselings->first(function($k) {
        return $k->status === 'disetujui' && $k->tanggal_konseling && $k->tanggal_konseling->isToday();
    });

    // Filter upcoming sessions
    $sesi_mendatang = $konselings->filter(function($k) use ($sesi_hari_ini) {
        if ($sesi_hari_ini && $k->id === $sesi_hari_ini->id) {
            return false;
        }
        return true;
    });
@endphp

<div class="max-w-6xl mx-auto">
    <!-- Header Section -->
    <div class="mb-10">
        <p class="font-body-lg text-on-surface-variant max-w-2xl">Kelola dan pantau sesi bimbinganmu. Kami di sini untuk mendengarkan dan membantumu menemukan jalan keluar.</p>
    </div>

    @if($konselings->isEmpty())
        <!-- Empty State -->
        <div class="bg-surface-container-lowest rounded-2xl p-8 border border-surface-variant shadow-subtle max-w-3xl mx-auto text-center my-12">
            <div class="py-8">
                <div class="w-16 h-16 bg-surface-container rounded-full flex items-center justify-center mx-auto mb-4 text-outline-variant">
                    <span class="material-symbols-outlined text-[2rem]">calendar_today</span>
                </div>
                <h3 class="font-bold text-on-surface text-base mb-1">Tidak Ada Jadwal Aktif</h3>
                <p class="text-xs text-on-surface-variant max-w-xs mx-auto mb-6">Kamu belum memiliki sesi konseling yang sedang berjalan atau menunggu persetujuan.</p>
                <a href="{{ route('siswa.pengajuan') }}"
                   class="inline-flex items-center gap-1.5 px-6 py-3 bg-primary hover:bg-primary-container text-white font-bold rounded-xl text-sm transition shadow-md hover:scale-105 active:scale-95">
                    <span class="material-symbols-outlined text-[1.125rem]">add</span>
                    Ajukan Konseling Sekarang
                </a>
            </div>
        </div>
    @else
        <!-- Sesi Hari Ini (Hero Component) -->
        @if($sesi_hari_ini)
        <section class="mb-12">
            <div class="flex items-center gap-2 mb-4">
                <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">calendar_today</span>
                <h3 class="font-headline-sm text-headline-sm text-on-surface">Sesi Hari Ini</h3>
            </div>
            <div class="relative overflow-hidden rounded-3xl bg-primary-container text-on-primary shadow-[0px_8px_30px_rgba(16,106,106,0.15)] p-1 text-on-primary-container">
                <!-- Background Pattern/Image -->
                <div class="absolute inset-0 opacity-10 mix-blend-overlay">
                    <img class="w-full h-full object-cover" data-alt="A tranquil, high-key abstract image featuring soft ripples in water and gentle light reflections in shades of teal and white." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAY1QJ21ojIr81dAbVEjYO-s5SNyJhizinZoEVxdpZB75ObAiPnvioQ5RL0GMyCnXnFniXyOxPb6wJOXIH62gwdTY_L1Sm664nrwBkoX_aUbs3ifzL4WAuzRKlbhNU1JGCRPEDk96CZHZBblMHGsVbTaTzbPT8_nge1HOQ8kFDC1YLUpKncgoyORlXnQ4aKfPoY1_RFnEf0d_Bju-Lu8bFKjh3DiYMvWHcEx3DjPZDPaoyj4Pk07r19CMKG4PY1Vo1xyvB2k6zhBYA"/>
                </div>
                <div class="relative z-10 p-8 md:flex items-center justify-between gap-8">
                    <div class="flex-1">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 backdrop-blur-sm border border-white/30 mb-6">
                            <span class="w-2 h-2 rounded-full bg-on-primary-container animate-pulse"></span>
                            <span class="text-label-md uppercase tracking-wider text-white">Sesi Aktif</span>
                        </div>
                        <h4 class="font-headline-lg text-headline-lg text-white mb-2">{{ $sesi_hari_ini->jenis_masalah }}</h4>
                        <p class="text-white/80 text-body-md line-clamp-2 max-w-xl mb-4 font-medium italic">"{{ $sesi_hari_ini->deskripsi_masalah }}"</p>
                        <div class="flex flex-wrap items-center gap-6 mt-6">
                            <div class="flex items-center gap-2 text-white/90">
                                <span class="material-symbols-outlined">person</span>
                                <span class="font-medium">{{ $sesi_hari_ini->guru ? $sesi_hari_ini->guru->name : 'Guru BK' }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-white/90">
                                <span class="material-symbols-outlined">schedule</span>
                                <span class="font-medium">{{ $sesi_hari_ini->jam_konseling ? \Carbon\Carbon::parse($sesi_hari_ini->jam_konseling)->format('H:i') . ' WIB' : '—' }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-white/90">
                                <span class="material-symbols-outlined">location_on</span>
                                <span class="font-medium">{{ $sesi_hari_ini->tempat ?? 'Ruang Konseling BK' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 md:mt-0 flex flex-col gap-3 shrink-0">
                        @if($sesi_hari_ini->link_meeting)
                            <a href="{{ $sesi_hari_ini->link_meeting }}" target="_blank" class="px-8 py-4 bg-white text-primary font-bold rounded-2xl hover:scale-105 active:scale-95 transition-all shadow-lg flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined">video_call</span>
                                Gabung Video Call
                            </a>
                        @else
                            <button class="px-8 py-4 bg-white text-primary font-bold rounded-2xl hover:scale-105 active:scale-95 transition-all shadow-lg flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined">directions</span>
                                Petunjuk Lokasi
                            </button>
                        @endif
                        @if($sesi_hari_ini->guru && $sesi_hari_ini->guru->no_telp)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $sesi_hari_ini->guru->no_telp) }}" target="_blank" class="px-8 py-4 border border-white/40 text-white font-semibold rounded-2xl hover:bg-white/10 hover:scale-105 active:scale-95 transition-all flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined">chat</span>
                                Hubungi Guru
                            </a>
                        @else
                            <button class="px-8 py-4 border border-white/40 text-white font-semibold rounded-2xl hover:bg-white/10 transition-colors flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined">chat</span>
                                Hubungi Guru
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </section>
        @endif

        <!-- Upcoming Sessions (Bento/Card Grid) -->
        @if($sesi_mendatang->isNotEmpty())
        <section class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">event_upcoming</span>
                    <h3 class="font-headline-sm text-headline-sm text-on-surface">Sesi Mendatang</h3>
                </div>
                <a href="{{ route('siswa.riwayat') }}" class="text-primary font-semibold hover:underline flex items-center gap-1 transition-all">
                    Lihat Semua <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($sesi_mendatang as $k)
                @php
                    $icon = match(strtolower($k->jenis_masalah)) {
                        'akademik' => 'school',
                        'pribadi'  => 'psychology',
                        'sosial'   => 'groups',
                        'karir'    => 'work',
                        'keluarga' => 'family_restroom',
                        default    => 'event_available',
                    };
                    $statusClass = match($k->status) {
                        'disetujui' => 'bg-secondary-fixed text-on-secondary-fixed-variant border border-secondary-fixed-dim/20',
                        'menunggu'  => 'bg-tertiary-fixed text-on-tertiary-fixed-variant border border-tertiary-fixed-dim/20',
                        default     => 'bg-surface-variant text-on-surface-variant',
                    };
                    $statusLabel = match($k->status) {
                        'disetujui' => 'DISETUJUI',
                        'menunggu'  => 'MENUNGGU',
                        default     => strtoupper($k->status),
                    };
                @endphp
                <!-- Session Card -->
                <div class="session-card bg-white rounded-3xl p-6 border border-surface-variant shadow-subtle hover:shadow-md transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <div class="bg-surface-container-low p-3 rounded-2xl">
                                <span class="material-symbols-outlined text-primary">{{ $icon }}</span>
                            </div>
                            <span class="px-3 py-1 rounded-full text-[0.75rem] font-bold {{ $statusClass }}">{{ $statusLabel }}</span>
                        </div>
                        <p class="text-label-md text-on-surface-variant mb-1 font-semibold">
                            @if($k->tanggal_konseling)
                                {{ $k->tanggal_konseling->translatedFormat('l, d F Y') }}
                            @else
                                Menunggu Konfirmasi Jadwal
                            @endif
                        </p>
                        <h5 class="font-headline-sm text-[1.125rem] text-on-surface mb-2 truncate" title="{{ $k->jenis_masalah }}">
                            {{ $k->jenis_masalah }}
                        </h5>
                        <p class="text-xs text-on-surface-variant line-clamp-2 leading-relaxed mb-4 italic">
                            "{{ $k->deskripsi_masalah }}"
                        </p>
                    </div>

                    <div>
                        <div class="flex items-center gap-3 py-4 border-t border-surface-variant">
                            <div class="w-10 h-10 rounded-full bg-primary-container/20 flex items-center justify-center text-primary font-bold text-sm shrink-0">
                                {{ strtoupper(substr($k->guru ? $k->guru->name : 'BK', 0, 2)) }}
                            </div>
                            <div>
                                <p class="text-body-sm font-semibold text-on-surface truncate max-w-[9.375rem]" title="{{ $k->guru ? $k->guru->name : 'Guru BK' }}">
                                    {{ $k->guru ? $k->guru->name : 'Guru BK' }}
                                </p>
                                <p class="text-[0.75rem] text-on-surface-variant">Guru BK Sekolah</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 text-body-sm text-on-surface-variant">
                            <span class="material-symbols-outlined text-[1.125rem]">schedule</span>
                            @if($k->jam_konseling)
                                {{ \Carbon\Carbon::parse($k->jam_konseling)->format('H:i') }} WIB
                            @else
                                Menunggu Jam Konfirmasi
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif
    @endif

    <!-- Reassuring Bottom Card -->
    <section class="mt-16 bg-surface-container rounded-[2.5rem] p-10 flex flex-col md:flex-row items-center gap-10">
        <div class="w-full md:w-1/3">
            <img class="w-full aspect-square rounded-[2rem] object-cover shadow-2xl" data-alt="Two students and a counselor sitting in a circle in a bright, modern school lounge area." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCff3GiosqqrBb8ObPEFzIE6QYXewZJcIf9std3c_uDYnjIyuKxJi5tzcGbAUEoxOarAooNEkwbLPbhqRCYBqd7gmm_Nmp6srrgRiZoZsN-MQ6DSG5J0SJMhA5D3XRrvSjkFtzD-o36NcyC7W7_ryoeeXxdd2D3sxZkvhVb_yDcRNg15tlzb63Nkc3v5gPjkbYBpP77rYr_WCx3NGoNW_aZ0pPCBbC193cIdZJ0AK_xAyabpbqnl0pnWATD9yELVLiNLoorqoCkrwQ"/>
        </div>
        <div class="flex-1">
            <h3 class="font-headline-lg text-headline-lg text-primary mb-4">Butuh bantuan di luar jadwal?</h3>
            <p class="font-body-lg text-on-surface-variant mb-8 leading-relaxed">
                Jika kamu merasa dalam situasi mendesak atau hanya perlu teman bicara segera, tim BK kami selalu siap membantumu. Jangan ragu untuk mengetuk pintu kami atau menggunakan tombol darurat.
            </p>
            <div class="flex flex-wrap gap-4">
                @if($guru_bk && $guru_bk->no_telp)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $guru_bk->no_telp) }}" target="_blank" class="bg-primary text-white px-8 py-4 rounded-2xl font-bold flex items-center gap-2 hover:bg-primary-container transition-all hover:scale-105 active:scale-95 shadow-md">
                        <span class="material-symbols-outlined">sos</span>
                        Hubungi Darurat
                    </a>
                @else
                    <button class="bg-primary text-white px-8 py-4 rounded-2xl font-bold flex items-center gap-2 hover:bg-primary-container transition-all hover:scale-105 active:scale-95 shadow-md">
                        <span class="material-symbols-outlined">sos</span>
                        Hubungi Darurat
                    </button>
                @endif
                <a href="{{ route('siswa.dashboard') }}" class="bg-white border border-primary text-primary px-8 py-4 rounded-2xl font-bold hover:bg-primary-container/5 hover:scale-105 active:scale-95 transition-all flex items-center justify-center">
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </section>
</div>

@endsection

@section('scripts')
<script>
    // Subtle interaction for cards
    document.querySelectorAll('.session-card').forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-4px)';
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0)';
        });
    });
</script>
@endsection
