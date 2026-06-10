@extends('layouts.dashboard')
@section('title', 'Riwayat Konseling Kelas')
@section('nav-title', 'Riwayat Konseling Kelas')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-on-surface">Riwayat Sesi Konseling</h2>
        <p class="text-sm text-on-surface-variant mt-1">Siswa Kelas {{ $kelas ?? 'Anda' }}</p>
    </div>
    <form method="GET" class="w-full sm:w-auto shrink-0">
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[1.125rem]">filter_list</span>
            <select name="status" onchange="this.form.submit()"
                class="w-full sm:w-48 pl-10 pr-10 py-2.5 bg-surface border border-outline-variant/50 rounded-xl text-sm font-medium text-on-surface outline-none cursor-pointer focus:ring-2 focus:ring-primary appearance-none transition-all shadow-sm hover:border-primary/50">
                <option value="">Semua Status</option>
                <option value="menunggu"  {{ request('status')==='menunggu'  ? 'selected' : '' }}>Menunggu</option>
                <option value="disetujui" {{ request('status')==='disetujui' ? 'selected' : '' }}>Berlangsung</option>
                <option value="selesai"   {{ request('status')==='selesai'   ? 'selected' : '' }}>Selesai</option>
                <option value="ditolak"   {{ request('status')==='ditolak'   ? 'selected' : '' }}>Ditolak</option>
            </select>
            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[1.125rem] pointer-events-none">expand_more</span>
        </div>
    </form>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    @forelse($konselings as $k)
    @php
        $bc = match($k->status){ 'selesai'=>'bg-[#E6F4EA] text-[#137333]','disetujui'=>'bg-secondary-container text-on-secondary-container','ditolak'=>'bg-[#FCE8E6] text-[#C5221F]', default=>'bg-tertiary-container text-on-tertiary-container' };
        $bl = match($k->status){ 'selesai'=>'Selesai','disetujui'=>'Berlangsung','ditolak'=>'Ditolak', default=>'Menunggu' };
        $icon = match($k->status){ 'selesai'=>'check_circle','disetujui'=>'schedule','ditolak'=>'cancel', default=>'hourglass_empty' };
        $borderColor = match($k->status){ 'selesai'=>'border-[#137333]/20','disetujui'=>'border-secondary/20','ditolak'=>'border-[#C5221F]/20', default=>'border-tertiary/20' };
    @endphp
    
    <div class="bg-surface rounded-3xl p-6 border border-outline-variant/30 shadow-subtle flex flex-col gap-5 hover:shadow-md transition-all hover:-translate-y-1 duration-300">
        {{-- Profile & Status Header --}}
        <div class="flex items-start justify-between gap-4">
            <div class="flex items-center gap-4 min-w-0">
                <div class="w-12 h-12 rounded-full bg-surface-container flex items-center justify-center flex-shrink-0 text-on-surface-variant">
                    <span class="material-symbols-outlined text-[1.5rem]">{{ $icon }}</span>
                </div>
                <div class="min-w-0">
                    <div class="font-bold text-lg text-on-surface truncate">{{ $k->siswa->name }}</div>
                    <div class="text-sm font-medium text-on-surface-variant mt-0.5 flex items-center gap-2 flex-wrap">
                        <span class="inline-flex items-center gap-1"><span class="material-symbols-outlined text-[1rem]">category</span> {{ $k->jenis_masalah }}</span>
                        <span class="opacity-50">•</span>
                        <span class="inline-flex items-center gap-1"><span class="material-symbols-outlined text-[1rem]">calendar_today</span> {{ $k->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
            <span class="flex-shrink-0 px-3 py-1.5 rounded-full text-xs font-bold tracking-wide uppercase {{ $bc }}">{{ $bl }}</span>
        </div>

        {{-- Detail Content --}}
        @if($k->status === 'selesai' && $k->hasil)
        <div class="mt-2 bg-surface-container-lowest border {{ $borderColor }} rounded-2xl p-5">
            <div class="flex items-center gap-2 mb-4 pb-3 border-b border-outline-variant/20">
                <span class="material-symbols-outlined text-[#137333] text-[1.25rem]">verified</span>
                <span class="text-sm font-bold text-[#137333] uppercase tracking-wide">Hasil Konseling</span>
            </div>
            
            <div class="flex flex-col gap-4">
                <div>
                    <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1">Catatan Konselor</p>
                    <p class="text-sm text-on-surface bg-surface p-3 rounded-xl border border-outline-variant/30">{{ $k->hasil->catatan_konselor }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1">Saran untuk Siswa</p>
                    <p class="text-sm text-on-surface bg-surface p-3 rounded-xl border border-outline-variant/30">{{ $k->hasil->saran }}</p>
                </div>
                @if($k->hasil->tindak_lanjut)
                <div>
                    <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1">Tindak Lanjut</p>
                    <p class="text-sm text-on-surface bg-surface p-3 rounded-xl border border-outline-variant/30">{{ $k->hasil->tindak_lanjut }}</p>
                </div>
                @endif
            </div>
        </div>
        @elseif($k->status === 'ditolak' && $k->alasan_penolakan)
        <div class="mt-2 bg-[#FCE8E6]/50 border {{ $borderColor }} rounded-2xl p-5">
            <div class="flex items-center gap-2 mb-2">
                <span class="material-symbols-outlined text-[#C5221F] text-[1.25rem]">info</span>
                <span class="text-sm font-bold text-[#C5221F] uppercase tracking-wide">Alasan Penolakan</span>
            </div>
            <p class="text-sm text-on-surface mt-2">{{ $k->alasan_penolakan }}</p>
        </div>
        @endif
    </div>
    @empty
    <div class="col-span-full py-16 text-center bg-surface rounded-3xl border border-outline-variant/30 shadow-subtle">
        <div class="w-20 h-20 bg-surface-container-high rounded-full flex items-center justify-center mx-auto mb-5">
            <span class="material-symbols-outlined text-4xl text-outline">history</span>
        </div>
        <p class="text-xl font-bold text-on-surface">Belum ada riwayat konseling</p>
        <p class="text-base text-on-surface-variant mt-2 max-w-md mx-auto">Siswa di kelas ini belum memiliki riwayat sesi konseling yang telah diselesaikan atau ditolak.</p>
    </div>
    @endforelse
</div>

    @if($konselings->hasPages())
    <div class="p-6 border-t border-outline-variant/30">
        {{ $konselings->links() }}
    </div>
    @endif
</div>
@endsection
