@extends('layouts.admin')
@section('title', 'Riwayat Konseling')
@section('nav-title', 'Riwayat Konseling')

@section('content')

<!-- Stats Row -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
    <!-- Stat 1: Cases Solved -->
    <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/30 shadow-sm">
        <div class="flex items-center gap-4 mb-4">
            <div class="p-3 bg-primary/10 text-primary rounded-lg">
                <span class="material-symbols-outlined">task_alt</span>
            </div>
            <p class="font-label-md text-outline">TOTAL KASUS DISELESAIKAN</p>
        </div>
        <p class="font-display-lg text-display-lg text-on-surface">{{ $total_selesai }}</p>
        @if($trend >= 0)
            <p class="text-body-sm text-primary mt-2">↑ {{ $trend }}% dari bulan lalu</p>
        @else
            <p class="text-body-sm text-error mt-2">↓ {{ abs($trend) }}% dari bulan lalu</p>
        @endif
    </div>

    <!-- Stat 2: Avg Duration -->
    <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/30 shadow-sm">
        <div class="flex items-center gap-4 mb-4">
            <div class="p-3 bg-tertiary-container/10 text-tertiary rounded-lg">
                <span class="material-symbols-outlined">timer</span>
            </div>
            <p class="font-label-md text-outline">RATA-RATA DURASI SESI</p>
        </div>
        <p class="font-display-lg text-display-lg text-on-surface">45<span class="text-headline-md font-normal ml-1">menit</span></p>
        <p class="text-body-sm text-on-surface-variant mt-2">Konsisten dengan standar klinis</p>
    </div>

    <!-- Stat 3: Top Category -->
    <div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/30 shadow-sm">
        <div class="flex items-center gap-4 mb-4">
            <div class="p-3 bg-secondary-container/20 text-secondary rounded-lg">
                <span class="material-symbols-outlined">category</span>
            </div>
            <p class="font-label-md text-outline">KATEGORI MASALAH TERBANYAK</p>
        </div>
        <p class="font-headline-lg text-headline-lg text-on-surface">{{ $kategori_terbanyak_nama }}</p>
        <p class="text-body-sm text-on-surface-variant mt-2">Mencakup {{ $kategori_terbanyak_percentage }}% dari total laporan</p>
    </div>
</div>

<!-- Filter & Search Bar -->
<form method="GET" action="{{ route('guru_bk.riwayat') }}" class="bg-white/50 backdrop-blur-md p-4 rounded-2xl border border-outline-variant/50 flex flex-wrap items-center gap-3 mb-8">
    <div class="relative flex-grow min-w-[17.5rem] h-12">
        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">search</span>
        <input class="w-full h-full pl-12 pr-4 bg-background border border-outline-variant/50 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-body-sm text-on-surface" 
               type="text" 
               name="search" 
               value="{{ request('search') }}" 
               placeholder="Cari nama siswa..."/>
    </div>
    
    <!-- Filter Kelas -->
    <select name="kelas" onchange="this.form.submit()" class="h-12 px-4 bg-background border border-outline-variant/50 rounded-xl text-body-sm text-on-surface-variant focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none cursor-pointer transition-all">
        <option value="">Semua Kelas</option>
        @foreach($kelas_list as $kls)
            <option value="{{ $kls }}" {{ request('kelas') == $kls ? 'selected' : '' }}>{{ $kls }}</option>
        @endforeach
    </select>
    
    <!-- Filter Tipe Masalah -->
    <select name="jenis_masalah" onchange="this.form.submit()" class="h-12 px-4 bg-background border border-outline-variant/50 rounded-xl text-body-sm text-on-surface-variant focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none cursor-pointer transition-all">
        <option value="">Tipe Masalah</option>
        <option value="akademik" {{ request('jenis_masalah') == 'akademik' ? 'selected' : '' }}>Akademik</option>
        <option value="sosial" {{ request('jenis_masalah') == 'sosial' ? 'selected' : '' }}>Sosial</option>
        <option value="pribadi" {{ request('jenis_masalah') == 'pribadi' ? 'selected' : '' }}>Pribadi</option>
        <option value="karir" {{ request('jenis_masalah') == 'karir' ? 'selected' : '' }}>Karir</option>
        <option value="keluarga" {{ request('jenis_masalah') == 'keluarga' ? 'selected' : '' }}>Keluarga</option>
    </select>
    
    <!-- Filter Status -->
    <select name="status" onchange="this.form.submit()" class="h-12 px-4 bg-background border border-outline-variant/50 rounded-xl text-body-sm text-on-surface-variant focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none cursor-pointer transition-all">
        <option value="">Status</option>
        <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Terjadwal</option>
        <option value="berlangsung" {{ request('status') == 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
        <option value="tidak_hadir" {{ request('status') == 'tidak_hadir' ? 'selected' : '' }}>Tidak Hadir</option>
    </select>

    <!-- Filter Tanggal -->
    <div class="relative flex items-center h-12 border border-outline-variant/50 rounded-xl bg-background px-4 focus-within:ring-2 focus-within:ring-primary/20 focus-within:border-primary transition-all">
        <span class="material-symbols-outlined text-outline text-lg mr-2 select-none pointer-events-none">calendar_today</span>
        <input type="date" name="tanggal" value="{{ request('tanggal') }}" onchange="this.form.submit()" class="bg-transparent border-none p-0 text-body-sm text-on-surface-variant outline-none cursor-pointer focus:ring-0 w-32 [color-scheme:light]">
    </div>
    
    <button type="submit" class="flex items-center justify-center gap-2 h-12 px-6 bg-primary text-on-primary rounded-xl font-semibold hover:opacity-90 transition-all shrink-0">
        <span class="material-symbols-outlined">filter_list</span>
        Cari
    </button>

    @if(request()->filled('search') || request()->filled('kelas') || request()->filled('jenis_masalah') || request()->filled('status') || request()->filled('tanggal'))
        <a href="{{ route('guru_bk.riwayat') }}" class="flex items-center justify-center gap-1.5 h-12 px-4 bg-error-container/20 text-error rounded-xl font-semibold hover:bg-error-container/30 transition-all text-body-sm shrink-0">
            <span class="material-symbols-outlined text-lg">close</span>
            Reset
        </a>
    @endif
</form>

<!-- Data Table -->
<div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-container-low border-b border-outline-variant/30">
                    <th class="px-6 py-4 font-label-md text-outline whitespace-nowrap">SISWA</th>
                    <th class="px-6 py-4 font-label-md text-outline whitespace-nowrap">TANGGAL</th>
                    <th class="px-6 py-4 font-label-md text-outline whitespace-nowrap">KATEGORI</th>
                    <th class="px-6 py-4 font-label-md text-outline whitespace-nowrap">RINGKASAN CATATAN</th>
                    <th class="px-6 py-4 font-label-md text-outline whitespace-nowrap">STATUS</th>
                    <th class="px-6 py-4 font-label-md text-outline text-right whitespace-nowrap">AKSI</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/20">
                @forelse($konselings as $k)
                    @php
                        // Student Initials
                        $words = explode(' ', $k->siswa->name);
                        $initials = '';
                        if (count($words) >= 2) {
                            $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
                        } else {
                            $initials = strtoupper(substr($k->siswa->name, 0, 2));
                        }

                        // Status Color Mapping
                        $statusColor = match($k->status) {
                            'selesai' => 'bg-primary/10 text-primary border-primary/20',
                            'ditolak', 'tidak_hadir' => 'bg-error-container/20 text-error border-error-container/30',
                            'berlangsung' => 'bg-tertiary-container/10 text-tertiary border-tertiary-container/20',
                            'disetujui' => 'bg-secondary-container/20 text-secondary border-secondary-container/30',
                            default => 'bg-surface-container-high/60 text-on-surface-variant border-outline-variant/40',
                        };
                        $statusDot = match($k->status) {
                            'selesai' => 'bg-primary',
                            'ditolak', 'tidak_hadir' => 'bg-error',
                            'berlangsung' => 'bg-tertiary',
                            'disetujui' => 'bg-secondary',
                            default => 'bg-outline',
                        };
                        $statusLabel = match($k->status) {
                            'selesai' => 'Selesai',
                            'ditolak' => 'Ditolak',
                            'tidak_hadir' => 'Tidak Hadir',
                            'berlangsung' => 'Berlangsung',
                            'disetujui' => 'Terjadwal',
                            default => 'Menunggu',
                        };
                    @endphp
                    <tr class="hover:bg-surface-container-lowest transition-colors group">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xs shrink-0">
                                    {{ $initials }}
                                </div>
                                <div class="whitespace-nowrap">
                                    <p class="text-sm font-semibold text-on-surface">{{ $k->siswa->name }}</p>
                                    <p class="text-xs text-outline">{{ $k->siswa->kelas ?? 'Kelas -' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-xs text-on-surface-variant whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($k->tanggal_konseling ?? $k->created_at)->translatedFormat('d M Y') }}
                        </td>
                        <td class="px-6 py-5">
                            <span class="px-3 py-1 bg-surface-container-high text-on-surface-variant rounded-full text-xs whitespace-nowrap capitalize">
                                {{ $k->jenis_masalah }}
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            <p class="text-xs text-on-surface-variant line-clamp-1 max-w-xs" title="{{ $k->hasil?->catatan_konselor ?? $k->deskripsi_masalah }}">
                                {{ Str::limit($k->hasil?->catatan_konselor ?? $k->deskripsi_masalah ?? '-', 50) }}
                            </p>
                        </td>
                        <td class="px-6 py-5">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium whitespace-nowrap {{ $statusColor }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $statusDot }}"></span>
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex justify-end gap-2">
                                <!-- Detail action -->
                                <a href="{{ route('guru_bk.konseling.show', $k) }}" class="p-2 text-outline hover:text-primary hover:bg-primary/10 rounded-lg transition-all shrink-0" title="Lihat Detail">
                                    <span class="material-symbols-outlined">visibility</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-16 text-center text-on-surface-variant">
                            <div class="flex flex-col items-center justify-center">
                                <span class="material-symbols-outlined text-outline/50 text-5xl mb-3">history</span>
                                <p class="font-semibold text-body-md">Tidak ada riwayat konseling</p>
                                <p class="text-body-sm text-outline">Data konseling siswa yang cocok akan tampil di sini.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Laravel Pagination Wrapper -->
    @if($konselings->hasPages())
        <div class="px-6 py-4 bg-surface-container-low/30 border-t border-outline-variant/30">
            {{ $konselings->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection
