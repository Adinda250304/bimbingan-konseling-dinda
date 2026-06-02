@extends('layouts.dashboard')
@section('title', 'Riwayat Konseling Kelas')
@section('nav-title', 'Riwayat Kelas')

@section('content')
<div class="bg-surface rounded-3xl shadow-sm border border-outline-variant/30 overflow-hidden">
    <div class="p-6 border-b border-outline-variant/30 bg-surface-container-lowest flex items-center justify-between flex-wrap gap-4">
        <div>
            <h2 class="text-xl font-bold text-on-surface">Riwayat Kelas</h2>
            <p class="text-sm text-on-surface-variant mt-1">Kelas {{ $kelas ?? 'Anda' }}</p>
        </div>
        <form method="GET">
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[1.125rem]">filter_list</span>
                <select name="status" onchange="this.form.submit()"
                    class="pl-10 pr-10 py-2.5 bg-surface border border-outline-variant/50 rounded-xl text-sm font-medium text-on-surface outline-none cursor-pointer focus:ring-2 focus:ring-primary appearance-none transition-all shadow-sm">
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

    <div class="divide-y divide-outline-variant/20">
        @forelse($konselings as $k)
        @php
            $bc = match($k->status){ 'selesai'=>'bg-green-100 text-green-800','disetujui'=>'bg-secondary-container text-on-secondary-container','ditolak'=>'bg-error-container text-on-error-container', default=>'bg-tertiary-container text-on-tertiary-container' };
            $bl = match($k->status){ 'selesai'=>'Selesai','disetujui'=>'Berlangsung','ditolak'=>'Ditolak', default=>'Menunggu' };
            $icon = match($k->status){ 'selesai'=>'check_circle','disetujui'=>'schedule','ditolak'=>'cancel', default=>'hourglass_empty' };
        @endphp
        <div class="p-6 hover:bg-surface-container-lowest transition-colors">
            {{-- Header card --}}
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-4 flex-1 min-w-0">
                    <div class="w-12 h-12 rounded-full bg-surface-container flex items-center justify-center flex-shrink-0 text-on-surface-variant">
                        <span class="material-symbols-outlined text-[1.5rem]">{{ $icon }}</span>
                    </div>
                    <div>
                        <div class="font-bold text-base text-on-surface">{{ $k->siswa->name }}</div>
                        <div class="text-sm font-medium text-on-surface-variant mt-0.5 flex items-center gap-2 flex-wrap">
                            <span class="inline-flex items-center gap-1"><span class="material-symbols-outlined text-[0.875rem]">category</span> {{ $k->jenis_masalah }}</span>
                            <span>•</span>
                            <span class="inline-flex items-center gap-1"><span class="material-symbols-outlined text-[0.875rem]">calendar_today</span> {{ $k->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
                <span class="flex-shrink-0 px-3 py-1 rounded-full text-[0.6875rem] font-bold tracking-wide uppercase {{ $bc }}">{{ $bl }}</span>
            </div>

            {{-- Hasil Review Guru BK (hanya tampil jika selesai dan ada hasil) --}}
            @if($k->status === 'selesai' && $k->hasil)
            <div class="mt-5 pt-5 border-t border-outline-variant/20 ml-16">
                <p class="text-xs font-bold text-primary uppercase tracking-wide mb-3 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[1rem]">verified</span> Hasil Review Guru BK
                </p>

                <div class="space-y-4">
                    <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-xl p-4">
                        <p class="text-xs font-bold text-on-surface-variant mb-1 uppercase tracking-wide">Catatan Konselor</p>
                        <p class="text-sm text-on-surface">{{ $k->hasil->catatan_konselor }}</p>
                    </div>

                    <div class="bg-primary/5 border border-primary/20 rounded-xl p-4">
                        <p class="text-xs font-bold text-primary mb-1 uppercase tracking-wide">Saran untuk Siswa</p>
                        <p class="text-sm text-on-surface">{{ $k->hasil->saran }}</p>
                    </div>

                    @if($k->hasil->tindak_lanjut)
                    <div class="bg-secondary-container/30 border border-secondary/20 rounded-xl p-4">
                        <p class="text-xs font-bold text-secondary mb-1 uppercase tracking-wide">Tindak Lanjut</p>
                        <p class="text-sm text-on-surface">{{ $k->hasil->tindak_lanjut }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @elseif($k->status === 'ditolak' && $k->alasan_penolakan)
            <div class="mt-5 pt-5 border-t border-outline-variant/20 ml-16">
                <div class="bg-error/5 border border-error/20 rounded-xl p-4">
                    <p class="text-xs font-bold text-error mb-1 uppercase tracking-wide flex items-center gap-1">
                        <span class="material-symbols-outlined text-[0.875rem]">info</span> Alasan Penolakan
                    </p>
                    <p class="text-sm text-on-surface">{{ $k->alasan_penolakan }}</p>
                </div>
            </div>
            @endif
        </div>
        @empty
        <div class="p-12 text-center">
            <div class="w-16 h-16 bg-surface-container-high rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-3xl text-outline">history</span>
            </div>
            <p class="text-base font-bold text-on-surface">Belum ada riwayat konseling.</p>
            <p class="text-sm text-on-surface-variant mt-1">Belum ada sesi konseling siswa kelas ini yang diselesaikan atau ditolak.</p>
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
