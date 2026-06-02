@extends('layouts.dashboard')
@section('title', 'Jadwal Konseling')
@section('nav-title', 'Jadwal Konseling')

@section('content')
<div class="bg-surface rounded-3xl shadow-sm border border-outline-variant/30 overflow-hidden">
    <div class="p-6 border-b border-outline-variant/30 bg-surface-container-lowest flex items-center justify-between flex-wrap gap-4">
        <div>
            <h2 class="text-xl font-bold text-on-surface">Jadwal Panggilan</h2>
            <p class="text-sm text-on-surface-variant mt-1">Siswa Kelas {{ $kelas ?? '—' }}</p>
        </div>
        <form method="GET">
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">filter_list</span>
                <select name="status" onchange="this.form.submit()"
                    class="pl-10 pr-10 py-2.5 bg-surface border border-outline-variant/50 rounded-xl text-sm font-medium text-on-surface outline-none cursor-pointer focus:ring-2 focus:ring-primary appearance-none transition-all shadow-sm">
                    <option value="">Semua (Aktif)</option>
                    <option value="menunggu"  {{ request('status')==='menunggu'  ? 'selected' : '' }}>Menunggu Persetujuan</option>
                    <option value="disetujui" {{ request('status')==='disetujui' ? 'selected' : '' }}>Terjadwal</option>
                </select>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px] pointer-events-none">expand_more</span>
            </div>
        </form>
    </div>

    <div class="divide-y divide-outline-variant/20 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($konselings as $k)
            @php
                $bc = match($k->status) {
                    'disetujui' => 'bg-secondary-container text-on-secondary-container',
                    'menunggu'  => 'bg-tertiary-container text-on-tertiary-container',
                    default     => 'bg-surface-container-high text-on-surface',
                };
                $bl = match($k->status) {
                    'disetujui' => 'Terjadwal',
                    'menunggu'  => 'Menunggu Persetujuan',
                    default     => $k->status,
                };
                $icon = match($k->status) {
                    'disetujui' => 'event_available',
                    'menunggu'  => 'pending_actions',
                    default     => 'info',
                };
            @endphp
            <div class="border border-outline-variant/30 bg-surface-container-lowest rounded-2xl p-5 hover:shadow-md transition-all hover:-translate-y-1 duration-300 group">
                <div class="flex items-start justify-between gap-3 mb-3">
                    <div class="flex-1 min-w-0">
                        <div class="font-bold text-base text-on-surface truncate">{{ $k->siswa->name }}</div>
                        <div class="text-xs font-medium text-on-surface-variant mt-0.5 inline-flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">category</span>
                            {{ $k->jenis_masalah }}
                        </div>
                    </div>
                    <span class="flex-shrink-0 px-3 py-1 rounded-full text-[11px] font-bold tracking-wide uppercase {{ $bc }}">{{ $bl }}</span>
                </div>
                
                <div class="pt-3 border-t border-outline-variant/20">
                    @if($k->tanggal_konseling)
                    <div class="flex items-center gap-2 text-sm text-on-surface-variant bg-surface-container-low p-2.5 rounded-xl">
                        <span class="material-symbols-outlined text-primary text-[18px]">calendar_today</span>
                        <div>
                            <span class="font-bold text-on-surface">{{ $k->tanggal_konseling->translatedFormat('d M Y') }}</span>
                            @if($k->jam_konseling)
                            <span class="mx-1">•</span>
                            <span class="font-medium text-primary">{{ \Carbon\Carbon::parse($k->jam_konseling)->format('H:i') }} WIB</span>
                            @endif
                        </div>
                    </div>
                    @else
                    <div class="flex items-center gap-2 text-sm text-on-surface-variant bg-surface-container-low p-2.5 rounded-xl">
                        <span class="material-symbols-outlined text-[18px] opacity-70">hourglass_empty</span>
                        <span>Menunggu admin menentukan jadwal</span>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-span-full py-12 text-center">
                <div class="w-16 h-16 bg-surface-container-high rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-3xl text-outline">event_busy</span>
                </div>
                <p class="text-base font-bold text-on-surface">Tidak ada jadwal aktif</p>
                <p class="text-sm text-on-surface-variant mt-1">Semua sesi siswa di kelas ini sudah selesai atau belum ada pengajuan.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
