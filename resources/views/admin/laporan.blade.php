@extends('layouts.admin')
@section('title', 'Laporan & Rekap')
@section('nav-title', 'Laporan & Rekap')

@section('content')
@php
    $period_label = 'Kustom';
    if (request('start_date') == now()->subMonths(3)->toDateString() && request('end_date') == now()->toDateString()) {
        $period_label = '3 Bulan Terakhir';
    } elseif (request('start_date') == now()->subMonths(6)->toDateString() && request('end_date') == now()->toDateString()) {
        $period_label = '6 Bulan Terakhir';
    } elseif (request('start_date') == now()->startOfYear()->toDateString() && request('end_date') == now()->toDateString()) {
        $period_label = 'Tahun Ini';
    } elseif (!request()->has('start_date')) {
        $period_label = '3 Bulan Terakhir'; // default
    }
@endphp

<!-- Header Section -->
<div class="flex flex-col sm:flex-row justify-end items-center gap-3 mb-8">
    <div class="flex items-center gap-3">
        <!-- Date Selector Button & Panel -->
        <div class="relative dd-wrap">
            <button onclick="toggleDd('filter-panel')" class="bg-white border border-outline-variant/30 px-4 py-2.5 rounded-xl flex items-center gap-3 hover:border-primary transition-all cursor-pointer shadow-sm">
                <span class="material-symbols-outlined text-primary">calendar_today</span>
                <span class="font-body-sm font-semibold text-on-surface">{{ $period_label }}</span>
                <span class="material-symbols-outlined text-outline text-[18px]">expand_more</span>
            </button>
            
            <!-- Collapsible Filter Panel -->
            <div id="filter-panel" class="hidden dd-menu absolute right-0 mt-3 w-80 bg-white border border-outline-variant/30 rounded-2xl shadow-xl p-5 z-50 text-left origin-top-right transition-all">
                <h5 class="font-semibold text-sm text-on-surface mb-3">Filter Periode &amp; Status</h5>
                <form method="GET" action="{{ route('admin.laporan') }}" class="space-y-4">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-outline uppercase tracking-wider">Mulai Dari</label>
                        <input type="date" name="start_date" value="{{ $start_date }}" class="w-full px-3 py-2 border border-outline-variant/50 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-outline uppercase tracking-wider">Sampai Dengan</label>
                        <input type="date" name="end_date" value="{{ $end_date }}" class="w-full px-3 py-2 border border-outline-variant/50 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-outline uppercase tracking-wider">Status Sesi</label>
                        <select name="status" class="w-full px-3 py-2 border border-outline-variant/50 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                            <option value="">Semua Status</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Berlangsung</option>
                            <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    
                    <div class="pt-2 flex gap-2">
                        <a href="{{ route('admin.laporan') }}" class="flex-1 py-2 text-center border border-outline-variant/50 rounded-xl text-xs font-bold text-outline hover:bg-surface-container-low transition-all">Reset</a>
                        <button type="submit" class="flex-1 py-2 bg-primary text-white rounded-xl text-xs font-bold hover:bg-primary-container transition-all">Terapkan</button>
                    </div>
                </form>
                
                <div class="border-t border-outline-variant/30 mt-4 pt-3 space-y-2">
                    <span class="text-[10px] font-bold text-outline uppercase tracking-wider">Quick Filter</span>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('admin.laporan', ['start_date' => now()->startOfMonth()->toDateString(), 'end_date' => now()->endOfMonth()->toDateString()]) }}" class="py-1.5 text-center bg-surface-container-low rounded-lg text-xs font-semibold hover:bg-primary/10 hover:text-primary transition-all">Bulan Ini</a>
                        <a href="{{ route('admin.laporan', ['start_date' => now()->subMonths(3)->toDateString(), 'end_date' => now()->toDateString()]) }}" class="py-1.5 text-center bg-surface-container-low rounded-lg text-xs font-semibold hover:bg-primary/10 hover:text-primary transition-all">3 Bulan</a>
                        <a href="{{ route('admin.laporan', ['start_date' => now()->subMonths(6)->toDateString(), 'end_date' => now()->toDateString()]) }}" class="py-1.5 text-center bg-surface-container-low rounded-lg text-xs font-semibold hover:bg-primary/10 hover:text-primary transition-all">6 Bulan</a>
                        <a href="{{ route('admin.laporan', ['start_date' => now()->startOfYear()->toDateString(), 'end_date' => now()->toDateString()]) }}" class="py-1.5 text-center bg-surface-container-low rounded-lg text-xs font-semibold hover:bg-primary/10 hover:text-primary transition-all">Tahun Ini</a>
                    </div>
                </div>
            </div>
        </div>

        <button onclick="openModal('modal-buat-laporan')" class="bg-primary text-white px-6 py-2.5 rounded-xl font-semibold flex items-center gap-2 hover:opacity-90 transition-all shadow-md cursor-pointer">
            <span class="material-symbols-outlined text-[20px]">print</span>
            <span class="font-body-sm font-semibold">Cetak Laporan PDF</span>
        </button>
    </div>
</div>

<!-- Summary Cards Bento Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Card 1: Total Sesi -->
    <div class="tonal-card p-6 rounded-2xl">
        <div class="flex justify-between items-start mb-4">
            <div class="bg-primary/10 p-3 rounded-xl">
                <span class="material-symbols-outlined text-primary">analytics</span>
            </div>
            <span class="bg-emerald-100 text-emerald-700 text-[12px] font-bold px-2 py-1 rounded-lg flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">{{ $stats['trend_percentage'] >= 0 ? 'trending_up' : 'trending_down' }}</span>
                {{ abs($stats['trend_percentage']) }}%
            </span>
        </div>
        <p class="text-outline text-label-md uppercase tracking-wider">Total Sesi</p>
        <h3 class="text-display-lg font-bold text-on-surface">{{ $stats['total'] }}</h3>
        <p class="text-body-sm text-on-surface-variant mt-2">Dibandingkan periode lalu</p>
    </div>

    <!-- Card 2: Masalah Tersering -->
    <div class="tonal-card p-6 rounded-2xl">
        <div class="bg-tertiary-container/20 p-3 rounded-xl w-fit mb-4">
            <span class="material-symbols-outlined text-tertiary">psychology</span>
        </div>
        <p class="text-outline text-label-md uppercase tracking-wider">Masalah Tersering</p>
        <h3 class="text-headline-lg font-bold text-on-surface">{{ $stats['masalah_tersering'] }}</h3>
        <div class="flex items-center gap-2 mt-2">
            <div class="w-full bg-surface-container rounded-full h-1.5">
                <div class="bg-tertiary h-1.5 rounded-full" style="width: {{ $stats['masalah_tersering_percentage'] }}%"></div>
            </div>
            <span class="text-body-sm font-bold text-tertiary">{{ $stats['masalah_tersering_percentage'] }}%</span>
        </div>
    </div>

    <!-- Card 3: Kepuasan Siswa -->
    <div class="tonal-card p-6 rounded-2xl">
        <div class="bg-secondary-container/20 p-3 rounded-xl w-fit mb-4">
            <span class="material-symbols-outlined text-secondary">sentiment_satisfied</span>
        </div>
        <p class="text-outline text-label-md uppercase tracking-wider">Kepuasan Siswa</p>
        <div class="flex items-center gap-1 mt-1">
            <h3 class="text-display-lg font-bold text-on-surface mr-3">{{ number_format($stats['rating_score'], 1) }}</h3>
            @php
                $full_stars = floor($stats['rating_score']);
                $has_half = ($stats['rating_score'] - $full_stars) >= 0.3;
            @endphp
            @for ($star = 1; $star <= 5; $star++)
                @if ($star <= $full_stars)
                    <span class="material-symbols-outlined text-amber-400" style="font-variation-settings: 'FILL' 1;">star</span>
                @elseif ($star == $full_stars + 1 && $has_half)
                    <span class="material-symbols-outlined text-amber-400" style="font-variation-settings: 'FILL' 1;">star_half</span>
                @else
                    <span class="material-symbols-outlined text-amber-400">star</span>
                @endif
            @endfor
        </div>
        <p class="text-body-sm text-on-surface-variant mt-2">Dari {{ $stats['responden_count'] }} responden</p>
    </div>
</div>

<!-- Data Visualizations -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Line Chart Section -->
    <div class="tonal-card p-8 rounded-3xl">
        <div class="flex justify-between items-center mb-8">
            <h4 class="font-headline-sm text-on-surface">Tren Sesi Konseling</h4>
            <div class="text-xs text-outline font-semibold">Distribusi Mingguan</div>
        </div>
        <!-- Simulated Line Chart -->
        <div class="relative h-64 flex items-end justify-between gap-4">
            <div class="absolute inset-0 flex flex-col justify-between pointer-events-none opacity-10">
                <div class="border-b border-on-surface"></div>
                <div class="border-b border-on-surface"></div>
                <div class="border-b border-on-surface"></div>
                <div class="border-b border-on-surface"></div>
            </div>
            <!-- Chart Bars/Lines representation -->
            @foreach ($stats['weekly_heights'] as $wIdx => $height)
                <div class="flex-1 group relative flex flex-col justify-end items-center h-full">
                    <div class="text-[10px] font-bold text-primary opacity-0 group-hover:opacity-100 transition-opacity mb-1">{{ $stats['weekly_counts'][$wIdx] }} Sesi</div>
                    <div class="bg-primary/20 hover:bg-primary/50 transition-all rounded-t-lg w-full cursor-pointer" style="height: {{ max(10, $height) }}%"></div>
                    <span class="absolute -bottom-6 left-1/2 -translate-x-1/2 text-[10px] text-outline whitespace-nowrap">Minggu {{ $wIdx + 1 }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Donut Chart Section -->
    <div class="tonal-card p-8 rounded-3xl">
        <div class="flex justify-between items-center mb-8">
            <h4 class="font-headline-sm text-on-surface">Kategori Masalah</h4>
            <span class="text-body-sm text-outline">Total: {{ $stats['total'] }} Kasus</span>
        </div>
        <div class="flex flex-col sm:flex-row items-center justify-around gap-6">
            <div class="relative w-48 h-48 rounded-full flex items-center justify-center shadow-inner" 
                 style="background: conic-gradient(var(--color-primary) 0% {{ $stats['pct_akademik'] }}%, var(--color-tertiary) {{ $stats['pct_akademik'] }}% {{ $stats['pct_akademik'] + $stats['pct_pribadi'] }}%, var(--color-secondary) {{ $stats['pct_akademik'] + $stats['pct_pribadi'] }}% {{ $stats['pct_akademik'] + $stats['pct_pribadi'] + $stats['pct_sosial'] }}%, var(--color-secondary-container) {{ $stats['pct_akademik'] + $stats['pct_pribadi'] + $stats['pct_sosial'] }}% {{ $stats['pct_akademik'] + $stats['pct_pribadi'] + $stats['pct_sosial'] + $stats['pct_keluarga'] }}%, var(--color-outline-variant) {{ $stats['pct_akademik'] + $stats['pct_pribadi'] + $stats['pct_sosial'] + $stats['pct_keluarga'] }}% 100%)">
                <div class="absolute inset-[16px] rounded-full bg-white flex items-center justify-center shadow">
                    <div class="text-center">
                        <span class="text-headline-lg font-bold text-primary">{{ $stats['total'] }}</span>
                        <p class="text-[10px] text-outline uppercase tracking-wider">Kasus</p>
                    </div>
                </div>
            </div>
            <div class="space-y-3 w-full sm:w-auto">
                <div class="flex items-center gap-3 min-w-[150px]">
                    <div class="w-3 h-3 rounded-full bg-primary"></div>
                    <span class="text-body-sm font-semibold">Akademik</span>
                    <span class="text-body-sm text-outline ml-auto">{{ $stats['pct_akademik'] }}%</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 rounded-full bg-tertiary"></div>
                    <span class="text-body-sm font-semibold">Pribadi</span>
                    <span class="text-body-sm text-outline ml-auto">{{ $stats['pct_pribadi'] }}%</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 rounded-full bg-secondary"></div>
                    <span class="text-body-sm font-semibold">Sosial</span>
                    <span class="text-body-sm text-outline ml-auto">{{ $stats['pct_sosial'] }}%</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 rounded-full bg-secondary-container"></div>
                    <span class="text-body-sm font-semibold">Keluarga</span>
                    <span class="text-body-sm text-outline ml-auto">{{ $stats['pct_keluarga'] }}%</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 rounded-full bg-outline-variant"></div>
                    <span class="text-body-sm font-semibold">Karir</span>
                    <span class="text-body-sm text-outline ml-auto">{{ $stats['pct_karir'] }}%</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Report History List -->
<div class="tonal-card rounded-3xl overflow-hidden mt-8">
    <div class="p-8 border-b border-outline-variant/30 flex justify-between items-center">
        <h4 class="font-headline-sm text-on-surface">Riwayat Laporan</h4>
        <div class="flex gap-2">
            <a href="{{ route('admin.laporan.pdf', request()->query()) }}" class="px-4 py-2 bg-surface-container rounded-lg text-body-sm font-semibold hover:bg-surface-container-high transition-all flex items-center gap-1.5 shadow-sm">
                <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span>
                Export Range Ini
            </a>
        </div>
    </div>
    <div class="divide-y divide-outline-variant/20">
        @forelse($historical_reports as $rep)
        <div class="p-6 flex items-center justify-between hover:bg-surface-container-low transition-all">
            <div class="flex items-center gap-4">
                <div class="bg-primary/10 text-primary p-3 rounded-xl">
                    <span class="material-symbols-outlined">description</span>
                </div>
                <div>
                    <p class="font-body-md font-bold text-on-surface">{{ $rep['name'] }}</p>
                    <p class="text-body-sm text-outline">Dibuat: {{ $rep['created_at'] }} • {{ $rep['type'] }} • {{ $rep['file_size'] }}</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                @php
                    $is_arsip = isset($rep['tag']) && $rep['tag'] === 'Arsip';
                @endphp
                <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-tighter {{ $is_arsip ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                    {{ $rep['tag'] ?? 'Selesai' }}
                </span>
                <a href="{{ route('admin.laporan.pdf', ['start_date' => $rep['start_date'], 'end_date' => $rep['end_date']]) }}" 
                   class="flex items-center gap-2 text-primary hover:underline font-semibold text-body-sm">
                    <span class="material-symbols-outlined text-[18px]">download</span>
                    Download
                </a>
            </div>
        </div>
        @empty
        <div class="p-8 text-center text-outline text-body-sm">
            Belum ada riwayat laporan yang tersedia.
        </div>
        @endforelse
    </div>
    <div class="p-6 bg-surface-container-lowest text-center">
        <a href="{{ route('admin.riwayat') }}" class="text-primary font-bold text-body-sm hover:underline">Lihat Semua Konseling</a>
    </div>
</div>

@endsection

@section('modals')
<!-- Modal Buat Laporan Baru -->
<div id="modal-buat-laporan" class="hidden fixed inset-0 bg-black/50 backdrop-blur-md z-50 items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-8 shadow-2xl relative">
        <button onclick="closeModal('modal-buat-laporan')" class="absolute top-6 right-6 text-outline hover:text-on-surface flex items-center justify-center">
            <span class="material-symbols-outlined">close</span>
        </button>
        
        <h3 class="font-headline-sm text-headline-sm text-on-surface mb-2">Cetak Rekap Laporan PDF</h3>
        <p class="text-body-sm text-outline mb-6">Pilih kriteria untuk mengekspor rekap laporan aktivitas konseling siswa ke format PDF.</p>
        
        <form action="{{ route('admin.laporan.pdf') }}" method="GET" class="space-y-4" target="_blank">
            <div class="space-y-1">
                <label class="text-xs font-bold text-outline uppercase tracking-wider">Mulai Dari</label>
                <input type="date" name="start_date" value="{{ now()->startOfMonth()->toDateString() }}" required
                       class="w-full px-4 py-3 border border-outline-variant/50 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
            </div>
            
            <div class="space-y-1">
                <label class="text-xs font-bold text-outline uppercase tracking-wider">Sampai Dengan</label>
                <input type="date" name="end_date" value="{{ now()->toDateString() }}" required
                       class="w-full px-4 py-3 border border-outline-variant/50 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
            </div>
            
            <div class="space-y-1">
                <label class="text-xs font-bold text-outline uppercase tracking-wider">Status Sesi</label>
                <select name="status" class="w-full px-4 py-3 border border-outline-variant/50 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none appearance-none cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="selesai">Selesai</option>
                    <option value="disetujui">Berlangsung/Terjadwal</option>
                    <option value="menunggu">Menunggu</option>
                    <option value="ditolak">Ditolak</option>
                </select>
            </div>
            
            <div class="pt-4 flex gap-3">
                <button type="button" onclick="closeModal('modal-buat-laporan')" 
                        class="flex-1 py-3 text-center border border-outline-variant/50 rounded-xl text-sm font-bold text-outline hover:bg-surface-container-low transition-all">
                    Batal
                </button>
                <button type="submit" onclick="closeModal('modal-buat-laporan')"
                        class="flex-1 py-3 bg-primary hover:bg-primary-container text-white rounded-xl text-sm font-bold transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">download_for_offline</span>
                    Unduh PDF
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
