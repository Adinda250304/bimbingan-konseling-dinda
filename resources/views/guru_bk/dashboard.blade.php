@extends('layouts.admin')
@section('title', 'Dashboard Guru BK')
@section('nav-title', 'Dashboard')

@section('content')
<!-- Welcome Banner -->
<section class="mb-section-margin bg-surface-container-lowest rounded-2xl p-8 border border-surface-variant shadow-subtle relative overflow-hidden flex flex-col md:flex-row md:items-center gap-6">
    <div class="absolute right-0 top-0 w-full md:w-1/2 h-full bg-gradient-to-l from-secondary-container/30 to-transparent pointer-events-none"></div>
    <div class="relative z-10 max-w-2xl">
        @php
            $hour = now()->format('H');
            if ($hour < 11) {
                $greeting = 'Selamat Pagi';
            } elseif ($hour < 15) {
                $greeting = 'Selamat Siang';
            } elseif ($hour < 18) {
                $greeting = 'Selamat Sore';
            } else {
                $greeting = 'Selamat Malam';
            }
        @endphp
        <h2 class="font-headline-lg text-headline-lg text-primary mb-2">{{ $greeting }}, {{ auth()->user()->name }}!</h2>
    </div>
</section>

<!-- Key Metrics -->
<section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-card-gap mb-section-margin">
    <!-- Card 1 -->
    <div class="bg-surface-container-lowest rounded-2xl p-6 border border-surface-variant shadow-subtle">
        <div class="flex items-start justify-between mb-4">
            <div class="p-3 bg-primary-container/10 rounded-xl text-primary">
                <span class="material-symbols-outlined">schedule</span>
            </div>
            @if($sesi_hari_ini > 0)
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-[#E6F4EA] text-[#137333] font-label-md text-label-md">
                    Berjalan
                </span>
            @else
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-surface-container-highest text-on-surface-variant font-label-md text-label-md">
                    Santai
                </span>
            @endif
        </div>
        <p class="font-body-sm text-body-sm text-on-surface-variant mb-1">Konseling Hari Ini</p>
        <p class="font-headline-md text-headline-md text-on-surface">{{ $sesi_hari_ini }} Sesi</p>
    </div>

    <!-- Card 2 -->
    <div class="bg-surface-container-lowest rounded-2xl p-6 border border-surface-variant shadow-subtle">
        <div class="flex items-start justify-between mb-4">
            <div class="p-3 bg-primary-container/10 rounded-xl text-primary">
                <span class="material-symbols-outlined">task_alt</span>
            </div>
        </div>
        <p class="font-body-sm text-body-sm text-on-surface-variant mb-1">Total Selesai Bulan Ini</p>
        <p class="font-headline-md text-headline-md text-on-surface">{{ $selesai_bulan_ini }} Kasus</p>
    </div>

    <!-- Card 3 -->
    <div class="bg-surface-container-lowest rounded-2xl p-6 border border-surface-variant shadow-subtle">
        <div class="flex items-start justify-between mb-4">
            <div class="p-3 bg-secondary-container/30 rounded-xl text-secondary">
                <span class="material-symbols-outlined">person_add</span>
            </div>
            @if($menunggu > 0)
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-[#FFF8E1] text-[#F57F17] font-label-md text-label-md animate-pulse">
                    Butuh Respon
                </span>
            @endif
        </div>
        <p class="font-body-sm text-body-sm text-on-surface-variant mb-1">Pengajuan Baru</p>
        <p class="font-headline-md text-headline-md text-on-surface">{{ $menunggu }} Siswa</p>
    </div>

    <!-- Card 4 -->
    <div class="bg-surface-container-lowest rounded-2xl p-6 border border-surface-variant shadow-subtle">
        <div class="flex items-start justify-between mb-4">
            <div class="p-3 bg-primary-container/10 rounded-xl text-primary">
                <span class="material-symbols-outlined">person</span>
            </div>
            {{-- Toggle visual sesuai status --}}
            @if($is_available)
                <a href="{{ route('guru_bk.kalender') }}" class="w-12 h-6 bg-primary rounded-full relative block">
                    <div class="absolute right-1 top-1 w-4 h-4 bg-white rounded-full"></div>
                </a>
            @else
                <a href="{{ route('guru_bk.kalender') }}" class="w-12 h-6 bg-outline-variant rounded-full relative block">
                    <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full"></div>
                </a>
            @endif
        </div>
        <p class="font-body-sm text-body-sm text-on-surface-variant mb-1">Status Ketersediaan</p>
        <p class="font-headline-md text-headline-md {{ $is_available ? 'text-primary' : 'text-on-surface-variant' }}">
            {{ $is_available ? 'Aktif (Tersedia)' : 'Tidak Tersedia' }}
        </p>
        @if(!$is_available)
            <p class="text-xs text-on-surface-variant mt-1">
                <a href="{{ route('guru_bk.kalender') }}" class="underline hover:text-primary">Set kalender</a> untuk mulai menerima konseling
            </p>
        @endif
    </div>
</section>

<!-- Main Grid -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-card-gap">
    <!-- Left Column: Upcoming Schedule -->
    <section class="lg:col-span-7 bg-surface-container-lowest rounded-2xl border border-surface-variant shadow-subtle p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-headline-sm text-headline-sm text-on-surface">Jadwal Konseling Terdekat</h3>
            <a href="{{ route('guru_bk.jadwal') }}" class="text-primary hover:text-primary-container font-label-md text-label-md transition-colors">Lihat Semua</a>
        </div>
        
        <div class="flex flex-col gap-4">
            @forelse($upcoming_konselings as $k)
                <!-- Schedule Item -->
                <div class="flex flex-col sm:flex-row sm:items-center p-4 rounded-xl border border-outline-variant/50 hover:bg-surface-container-low transition-colors group gap-4">
                    <div class="flex items-center flex-1">
                        <div class="w-12 h-12 rounded-full bg-primary-container/20 flex items-center justify-center text-primary font-headline-sm text-headline-sm mr-4 font-bold shrink-0">
                            {{ strtoupper(substr($k->siswa->name, 0, 2)) }}
                        </div>
                        <div>
                            <h4 class="font-body-md text-body-md font-semibold text-on-surface">{{ $k->siswa->name }}</h4>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">
                                {{ $k->siswa->kelas ?? 'Kelas —' }} • {{ ucfirst($k->jenis) }}
                            </p>
                        </div>
                    </div>
                    <div class="flex justify-between items-center sm:text-right sm:block shrink-0 border-t sm:border-t-0 pt-2 sm:pt-0 border-outline-variant/10">
                        <div class="sm:mr-6">
                            <p class="font-body-sm text-body-sm font-semibold text-on-surface">
                                {{ $k->jam_konseling ? \Carbon\Carbon::parse($k->jam_konseling)->format('H:i') : 'TBA' }}
                            </p>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">
                                {{ $k->tanggal_konseling ? $k->tanggal_konseling->translatedFormat('d M Y') : '' }}
                            </p>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-[#E6F4EA] text-[#137333] font-label-md text-label-md border border-[#137333]/20">Disetujui</span>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <span class="material-symbols-outlined text-outline-variant text-4xl mb-2">calendar_today</span>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Tidak ada jadwal konseling terdekat.</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Right Column: New Requests -->
    <section class="lg:col-span-5 bg-surface-container-lowest rounded-2xl border border-surface-variant shadow-subtle p-6 flex flex-col">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-headline-sm text-headline-sm text-on-surface">Daftar Pengajuan Terbaru</h3>
        </div>
        
        <div class="flex-1 overflow-auto">
            <div class="flex flex-col gap-4">
                @forelse($recent_konselings as $k)
                    <!-- Request Item -->
                    <div class="p-4 rounded-xl bg-surface border border-outline-variant/30 shadow-sm">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 rounded-full bg-tertiary-container/20 flex items-center justify-center text-tertiary font-body-sm text-body-sm mr-3 font-bold">
                                {{ strtoupper(substr($k->siswa->name, 0, 2)) }}
                            </div>
                            <div>
                                <h4 class="font-body-sm text-body-sm font-semibold text-on-surface">{{ $k->siswa->name }}</h4>
                                <p class="font-label-md text-label-md text-on-surface-variant">{{ $k->siswa->kelas ?? 'Kelas —' }}</p>
                            </div>
                        </div>
                        <div class="mb-4">
                            <span class="inline-block px-2 py-1 bg-surface-container-high text-on-surface-variant rounded-md font-label-md text-label-md">
                                {{ $k->jenis_masalah }}
                            </span>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-1.5 w-full">
                            <button onclick="openSetujui({{ $k->id }}, '{{ addslashes($k->siswa->name) }}', '{{ $k->tanggal_konseling ? $k->tanggal_konseling->toDateString() : '' }}', '{{ $k->jam_konseling }}')" 
                                     class="flex-grow bg-primary text-white font-semibold py-2 rounded-lg hover:bg-primary-container transition-colors shadow-sm text-center text-xs">
                                Setujui
                            </button>
                            <button onclick="openReschedule({{ $k->id }}, '{{ addslashes($k->siswa->name) }}', '{{ $k->tanggal_konseling ? $k->tanggal_konseling->toDateString() : '' }}', '{{ $k->jam_konseling }}', '{{ addslashes($k->tempat ?? '') }}')" 
                                     class="flex-grow border border-primary text-primary font-semibold py-2 rounded-lg hover:bg-primary/5 transition-colors text-center text-xs">
                                Reschedule
                            </button>
                            <button onclick="openTolak({{ $k->id }}, '{{ addslashes($k->siswa->name) }}')" 
                                     class="flex-grow border border-error text-error font-semibold py-2 rounded-lg hover:bg-error/5 transition-colors text-center text-xs">
                                Tolak
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <span class="material-symbols-outlined text-outline-variant text-4xl mb-2">person_search</span>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">Belum ada pengajuan baru.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</div>
@endsection

@section('modals')
{{-- Modal: Setujui & Jadwalkan --}}
<div id="modal-setujui" class="hidden fixed inset-0 bg-black/40 backdrop-blur-md z-[100] items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-2xl p-6 w-full max-w-md shadow-xl border border-outline-variant/30">
        <div class="flex justify-between items-center mb-5">
            <h3 id="setujui-modal-title" class="font-headline-sm text-headline-sm text-on-surface font-bold">Setujui & Tentukan Jadwal</h3>
            <button onclick="closeModal('modal-setujui')" class="text-on-surface-variant hover:text-on-surface text-2xl leading-none">&times;</button>
        </div>
        <p class="font-body-sm text-body-sm text-on-surface-variant mb-4">Siswa: <span id="setujui-name" class="font-semibold text-on-surface"></span></p>
        <form id="setujui-form" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block font-body-sm text-body-sm font-semibold text-on-surface-variant mb-1">Tanggal Konseling</label>
                <input type="date" name="tanggal_konseling" required min="{{ today()->toDateString() }}"
                    class="w-full px-4 py-2.5 border border-outline-variant/60 rounded-xl text-sm outline-none focus:border-primary transition bg-surface">
            </div>
            <div>
                <label class="block font-body-sm text-body-sm font-semibold text-on-surface-variant mb-1">Jam</label>
                <input type="time" name="jam_konseling" required
                    class="w-full px-4 py-2.5 border border-outline-variant/60 rounded-xl text-sm outline-none focus:border-primary transition bg-surface">
            </div>
            <div>
                <label class="block font-body-sm text-body-sm font-semibold text-on-surface mb-1">Tempat / Ruangan</label>
                <input type="text" name="tempat" required placeholder="contoh: Ruang BK 1"
                    class="w-full px-4 py-2.5 border border-outline-variant/60 rounded-xl text-sm outline-none focus:border-primary transition bg-surface">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal('modal-setujui')"
                    class="flex-1 py-2.5 border border-outline text-on-surface hover:bg-surface-container-low rounded-lg transition font-semibold text-sm">
                    Batal
                </button>
                <button type="submit" id="setujui-submit-btn"
                    class="flex-1 py-2.5 bg-primary hover:bg-primary-container text-white rounded-lg transition font-semibold text-sm">
                    Setujui
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Tolak --}}
<div id="modal-tolak" class="hidden fixed inset-0 bg-black/40 backdrop-blur-md z-[100] items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-2xl p-6 w-full max-w-md shadow-xl border border-outline-variant/30">
        <div class="flex justify-between items-center mb-5">
            <h3 id="tolak-modal-title" class="font-headline-sm text-headline-sm text-on-surface font-bold">Tolak Pengajuan Konseling</h3>
            <button onclick="closeModal('modal-tolak')" class="text-on-surface-variant hover:text-on-surface text-2xl leading-none">&times;</button>
        </div>
        <p class="font-body-sm text-body-sm text-on-surface-variant mb-4">Siswa: <span id="tolak-name" class="font-semibold text-on-surface"></span></p>
        <form id="tolak-form" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block font-body-sm text-body-sm font-semibold text-on-surface-variant mb-1">Alasan Penolakan</label>
                <textarea name="alasan_penolakan" rows="3" required placeholder="Tuliskan alasan penolakan bimbingan..."
                    class="w-full px-4 py-3 border border-outline-variant/60 rounded-xl text-sm outline-none focus:border-error transition bg-surface resize-none"></textarea>
            </div>
            <div class="flex gap-3 pt-1">
                <button type="button" onclick="closeModal('modal-tolak')"
                    class="flex-1 py-2.5 border border-outline text-on-surface hover:bg-surface-container-low rounded-lg transition font-semibold text-sm">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 bg-error hover:opacity-90 text-white rounded-lg transition font-semibold text-sm">
                    Tolak
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openSetujui(id, name, date, time) {
        document.getElementById('setujui-modal-title').textContent = 'Setujui & Tentukan Jadwal';
        document.getElementById('setujui-name').textContent = name;
        
        const form = document.getElementById('setujui-form');
        form.action = '/guru-bk/konseling/' + id + '/setujui';
        
        form.querySelector('input[name="tanggal_konseling"]').value = date || '';
        form.querySelector('input[name="jam_konseling"]').value = time ? time.substring(0, 5) : '';
        form.querySelector('input[name="tempat"]').value = '';
        
        document.getElementById('setujui-submit-btn').textContent = 'Setujui';
        openModal('modal-setujui');
    }
    function openReschedule(id, name, date, time, tempat) {
        document.getElementById('setujui-modal-title').textContent = 'Atur Ulang Jadwal (Reschedule)';
        document.getElementById('setujui-name').textContent = name;
        
        const form = document.getElementById('setujui-form');
        form.action = '/guru-bk/konseling/' + id + '/setujui';
        
        form.querySelector('input[name="tanggal_konseling"]').value = date || '';
        form.querySelector('input[name="jam_konseling"]').value = time ? time.substring(0, 5) : '';
        form.querySelector('input[name="tempat"]').value = tempat || '';
        
        document.getElementById('setujui-submit-btn').textContent = 'Simpan Jadwal';
        openModal('modal-setujui');
    }
    function openTolak(id, name) {
        document.getElementById('tolak-modal-title').textContent = 'Tolak Pengajuan Konseling';
        document.getElementById('tolak-name').textContent = name;
        document.getElementById('tolak-form').action = '/guru-bk/konseling/' + id + '/tolak';
        openModal('modal-tolak');
    }
</script>
@endsection
