@extends('layouts.admin')
@section('title', 'Jadwal Konseling')
@section('nav-title', 'Jadwal Konseling')

@section('styles')
<style>
    .card-shadow {
        box-shadow: 0px 4px 20px rgba(16, 106, 106, 0.04);
    }
    .active-tab {
        border-bottom-width: 2px;
        --tw-border-opacity: 1;
        border-color: rgb(0 80 80 / var(--tw-border-opacity));
        color: rgb(0 80 80);
        font-weight: 700;
    }
</style>
@endsection

@section('content')
<div class="space-y-6">
    {{-- Elegant Tab Filters + Search --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-outline-variant/30 pb-2 gap-4">
        <div class="flex gap-6 overflow-x-auto w-full md:w-auto">
            <a href="{{ route('admin.jadwal', ['status' => 'semua', 'search' => $search]) }}" 
               class="pb-3 text-body-md text-on-surface-variant hover:text-primary transition-all whitespace-nowrap {{ $status === 'semua' ? 'active-tab' : '' }}">
                Semua Sesi
            </a>
            <a href="{{ route('admin.jadwal', ['status' => 'hari_ini', 'search' => $search]) }}" 
               class="pb-3 text-body-md text-on-surface-variant hover:text-primary transition-all whitespace-nowrap relative {{ $status === 'hari_ini' ? 'active-tab' : '' }}">
                Hari Ini
                @if($hari_ini_count > 0)
                    <span class="absolute -right-2 top-0 w-2 h-2 bg-secondary rounded-full"></span>
                @endif
            </a>
            <a href="{{ route('admin.jadwal', ['status' => 'terjadwal', 'search' => $search]) }}" 
               class="pb-3 text-body-md text-on-surface-variant hover:text-primary transition-all whitespace-nowrap {{ $status === 'terjadwal' ? 'active-tab' : '' }}">
                Terjadwal
            </a>
            <a href="{{ route('admin.jadwal', ['status' => 'menunggu', 'search' => $search]) }}" 
               class="pb-3 text-body-md text-on-surface-variant hover:text-primary transition-all whitespace-nowrap flex items-center gap-2 {{ $status === 'menunggu' ? 'active-tab' : '' }}">
                Menunggu Persetujuan
                @if($menunggu_count > 0)
                    <span class="bg-primary-container text-on-primary-container px-2 py-0.5 rounded-full text-[0.625rem] font-bold">{{ $menunggu_count }}</span>
                @endif
            </a>
        </div>

        {{-- Search Input --}}
        <form method="GET" action="{{ route('admin.jadwal') }}" class="w-full md:w-auto">
            <input type="hidden" name="status" value="{{ $status }}">
            <div class="flex items-center gap-2 border border-outline-variant/30 rounded-xl px-4 py-2 bg-surface-container-lowest focus-within:ring-2 focus-within:ring-primary focus-within:border-primary transition-all w-full md:w-64">
                <span class="material-symbols-outlined text-on-surface-variant text-[1.25rem] select-none">search</span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari siswa..."
                    class="text-sm border-none bg-transparent p-0 outline-none focus:ring-0 text-on-surface placeholder:text-on-surface-variant/50 w-full">
                @if($search)
                    <a href="{{ route('admin.jadwal', ['status' => $status]) }}" class="text-on-surface-variant hover:text-error transition-all">
                        <span class="material-symbols-outlined text-[1.125rem]">close</span>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Sesi Hari Ini (Highlight Section) --}}
    @if($status === 'hari_ini' || $status === 'semua')
        <section>
            <div class="flex justify-between items-end mb-4">
                <div>
                    <h3 class="font-headline-md text-headline-md text-on-background font-bold">Sesi Hari Ini</h3>
                    <p class="text-body-sm text-on-surface-variant font-medium">{{ \Carbon\Carbon::today()->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>

            @if($hari_ini->isEmpty())
                <div class="bg-white rounded-xl p-8 text-center border border-surface-variant/40 card-shadow mb-6">
                    <span class="material-symbols-outlined text-[2.5rem] text-on-surface-variant/30 mb-2 select-none">calendar_today</span>
                    <h4 class="text-on-surface font-semibold text-base">Tidak ada sesi untuk hari ini</h4>
                    <p class="text-sm text-on-surface-variant/70 mt-1">Semua sesi terjadwal hari ini akan tampil di sini.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    @foreach($hari_ini as $k)
                        @php 
                            $colors = [
                                'bg-primary-container/20 text-primary', 
                                'bg-secondary-container/30 text-secondary', 
                                'bg-tertiary-fixed-dim/40 text-tertiary', 
                                'bg-surface-container-highest text-on-surface-variant'
                            ];
                            $colorClass = $colors[$k->id % count($colors)];
                        @endphp
                        <div class="bg-white rounded-xl p-6 border border-surface-variant/40 card-shadow flex items-start gap-5 hover:border-primary/20 transition-all relative group pb-16 sm:pb-6">
                            {{-- Avatar Container --}}
                            <div class="relative shrink-0">
                                <div class="w-16 h-16 rounded-full {{ $colorClass }} flex items-center justify-center text-lg font-bold">
                                    {{ strtoupper(substr($k->siswa->name, 0, 2)) }}
                                </div>
                                @if($k->status === 'berlangsung')
                                    <div class="absolute bottom-0 right-0 w-4 h-4 bg-primary rounded-full border-2 border-white animate-pulse" title="Sedang Berlangsung"></div>
                                @else
                                    <div class="absolute bottom-0 right-0 w-4 h-4 bg-secondary rounded-full border-2 border-white" title="Terjadwal"></div>
                                @endif
                            </div>

                            {{-- Student details & Schedule --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <h4 class="font-bold text-base text-on-surface truncate" title="{{ $k->siswa->name }}">{{ $k->siswa->name }}</h4>
                                    <span class="bg-surface-container px-2.5 py-0.5 rounded-full text-[0.6875rem] font-semibold text-on-surface-variant uppercase tracking-tight">
                                        {{ $k->siswa->kelas ?? 'Kelas —' }}
                                    </span>
                                </div>
                                <div class="space-y-1 text-sm text-on-surface-variant">
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[1.125rem] text-primary">schedule</span>
                                        <span class="font-semibold text-primary">
                                            {{ $k->jam_konseling ? \Carbon\Carbon::parse($k->jam_konseling)->format('H:i') : '' }} WIB
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if($k->jenis === 'online')
                                            <span class="material-symbols-outlined text-[1.125rem] text-blue-500">video_camera_front</span>
                                            <span class="truncate">
                                                @if($k->link_meeting)
                                                    <a href="{{ $k->link_meeting }}" target="_blank" class="text-blue-600 hover:underline">Google Meet</a>
                                                @else
                                                    Google Meet
                                                @endif
                                            </span>
                                        @else
                                            <span class="material-symbols-outlined text-[1.125rem] text-on-surface-variant">location_on</span>
                                            <span class="truncate">{{ $k->tempat ?? ($k->jadwal->tempat ?? 'Ruang Konseling') }}</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2 pt-0.5">
                                        <span class="material-symbols-outlined text-[1.125rem] text-on-surface-variant/60">label</span>
                                        <span class="italic text-on-surface-variant/80 truncate">{{ $k->jenis_masalah }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="absolute right-4 bottom-4">
                                @if($k->status === 'disetujui')
                                    <form action="{{ route('admin.konseling.advance', $k) }}" method="POST" id="form-mulai-{{ $k->id }}">
                                        @csrf
                                        <button type="button" onclick="confirmMulai('{{ $k->id }}', '{{ addslashes($k->siswa->name) }}')"
                                            class="bg-secondary text-white px-4 py-2 rounded-xl font-bold text-xs flex items-center gap-1 hover:opacity-90 active:scale-95 transition-all shadow-sm">
                                            <span class="material-symbols-outlined text-[1rem]">play_arrow</span>
                                            Mulai Sesi
                                        </button>
                                    </form>
                                @elseif($k->status === 'berlangsung')
                                    <a href="{{ route('admin.konseling.hasil', $k) }}" 
                                       class="bg-primary text-white px-4 py-2 rounded-xl font-bold text-xs flex items-center gap-1 hover:bg-primary-container transition-all shadow-sm">
                                        <span class="material-symbols-outlined text-[1rem]">edit_note</span>
                                        Catatan / Selesai
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    @endif

    {{-- Sesi Mendatang / List View --}}
    <section>
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-headline-md text-headline-md text-on-background font-bold">
                @if($status === 'hari_ini')
                    Sesi Mendatang
                @elseif($status === 'terjadwal')
                    Sesi Terjadwal
                @elseif($status === 'menunggu')
                    Menunggu Persetujuan
                @else
                    Semua Sesi
                @endif
            </h3>
        </div>

        @if($konselings->isEmpty())
            <div class="bg-white rounded-xl p-12 text-center border border-surface-variant/40 card-shadow">
                <span class="material-symbols-outlined text-[2.75rem] text-on-surface-variant/30 mb-2 select-none">inbox</span>
                <h4 class="text-on-surface font-semibold text-base">Tidak ada data sesi</h4>
                <p class="text-sm text-on-surface-variant/70 mt-1">Gunakan tombol + di kanan bawah untuk membuat sesi baru.</p>
            </div>
        @else
            <div class="bg-white rounded-xl border border-surface-variant/40 card-shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[50rem]">
                        <thead>
                            <tr class="bg-surface-container-low border-b border-surface-variant/40">
                                <th class="px-6 py-4 font-label-md text-on-surface-variant uppercase tracking-wider text-xs font-semibold">Tanggal</th>
                                <th class="px-6 py-4 font-label-md text-on-surface-variant uppercase tracking-wider text-xs font-semibold">Waktu</th>
                                <th class="px-6 py-4 font-label-md text-on-surface-variant uppercase tracking-wider text-xs font-semibold">Nama Siswa</th>
                                <th class="px-6 py-4 font-label-md text-on-surface-variant uppercase tracking-wider text-xs font-semibold">Topik</th>
                                <th class="px-6 py-4 font-label-md text-on-surface-variant uppercase tracking-wider text-xs font-semibold">Status</th>
                                <th class="px-6 py-4 font-label-md text-on-surface-variant text-right uppercase tracking-wider text-xs font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-surface-variant/30">
                            @foreach($konselings as $k)
                                <tr class="hover:bg-surface-bright transition-colors">
                                    {{-- Tanggal --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <p class="font-body-md font-semibold text-on-surface text-sm">
                                            @if($k->tanggal_konseling)
                                                @if($k->tanggal_konseling->isToday())
                                                    Hari Ini
                                                @elseif($k->tanggal_konseling->isTomorrow())
                                                    Besok, {{ $k->tanggal_konseling->translatedFormat('d M') }}
                                                @else
                                                    {{ $k->tanggal_konseling->translatedFormat('d M Y') }}
                                                @endif
                                            @else
                                                <span class="text-on-surface-variant/50">TBD</span>
                                            @endif
                                        </p>
                                    </td>

                                    {{-- Waktu --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-on-surface-variant text-sm font-medium">
                                        {{ $k->jam_konseling ? \Carbon\Carbon::parse($k->jam_konseling)->format('H:i') . ' WIB' : 'TBD' }}
                                    </td>

                                    {{-- Siswa --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-primary-fixed text-on-primary-fixed flex items-center justify-center text-xs font-bold shrink-0">
                                                {{ strtoupper(substr($k->siswa->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <span class="font-body-md font-semibold text-on-surface text-sm">{{ $k->siswa->name }}</span>
                                                <p class="text-[0.625rem] text-on-surface-variant/70">{{ $k->siswa->kelas }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Topik --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-on-surface-variant">
                                        {{ $k->jenis_masalah }}
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($k->status === 'menunggu')
                                            <span class="bg-secondary-fixed text-on-secondary-container px-3 py-1 rounded-full text-[0.6875rem] font-bold">Persetujuan</span>
                                        @elseif($k->status === 'disetujui')
                                            <span class="bg-primary-fixed/30 text-primary px-3 py-1 rounded-full text-[0.6875rem] font-bold">Terjadwal</span>
                                        @elseif($k->status === 'berlangsung')
                                            <span class="bg-tertiary-fixed text-on-tertiary-fixed-variant px-3 py-1 rounded-full text-[0.6875rem] font-bold">Berlangsung</span>
                                        @else
                                            <span class="bg-surface-container text-on-surface-variant px-3 py-1 rounded-full text-[0.6875rem] font-bold">{{ $k->status_label }}</span>
                                        @endif
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                        <div class="flex justify-end items-center gap-2 font-semibold">
                                            @if($k->status === 'menunggu')
                                                <button onclick="openSetujui({{ $k->id }}, '{{ addslashes($k->siswa->name) }}', '{{ $k->tanggal_konseling?->toDateString() }}', '{{ $k->jam_konseling }}')" 
                                                    class="text-primary hover:underline hover:text-primary-container text-xs">Setujui</button>
                                                <span class="text-outline-variant/50">|</span>
                                                <button onclick="openReschedule({{ $k->id }}, '{{ addslashes($k->siswa->name) }}', '{{ $k->tanggal_konseling?->toDateString() }}', '{{ $k->jam_konseling }}', '{{ addslashes($k->tempat ?? ($k->jadwal->tempat ?? '')) }}')" 
                                                    class="text-on-surface-variant hover:text-primary hover:underline text-xs">Reschedule</button>
                                                <span class="text-outline-variant/50">|</span>
                                                <button onclick="openTolak({{ $k->id }}, '{{ addslashes($k->siswa->name) }}')" 
                                                    class="text-error hover:underline hover:text-red-700 text-xs">Tolak</button>
                                            @elseif($k->status === 'disetujui')
                                                <form action="{{ route('admin.konseling.advance', $k) }}" method="POST" id="form-mulai-{{ $k->id }}" class="hidden">
                                                    @csrf
                                                </form>
                                                <button type="button" onclick="confirmMulai('{{ $k->id }}', '{{ addslashes($k->siswa->name) }}')"
                                                    class="text-primary hover:underline hover:text-primary-container text-xs">Mulai</button>
                                                <span class="text-outline-variant/50">|</span>
                                                <button onclick="openReschedule({{ $k->id }}, '{{ addslashes($k->siswa->name) }}', '{{ $k->tanggal_konseling?->toDateString() }}', '{{ $k->jam_konseling }}', '{{ addslashes($k->tempat ?? ($k->jadwal->tempat ?? '')) }}')" 
                                                    class="text-on-surface-variant hover:text-primary hover:underline text-xs">Reschedule</button>
                                                <span class="text-outline-variant/50">|</span>
                                                <button onclick="openTolak({{ $k->id }}, '{{ addslashes($k->siswa->name) }}')" 
                                                    class="text-error hover:underline hover:text-red-700 text-xs">Batalkan</button>
                                            @elseif($k->status === 'berlangsung')
                                                <a href="{{ route('admin.konseling.hasil', $k) }}" 
                                                   class="text-primary hover:underline hover:text-primary-container text-xs flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-[1rem]">edit_note</span> Selesaikan
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </section>
</div>

{{-- Floating Action Button (FAB) --}}
<button onclick="openCreate()" 
        class="fixed bottom-10 right-10 w-16 h-16 bg-primary text-white rounded-full shadow-2xl flex items-center justify-center hover:scale-105 active:scale-95 transition-all z-40"
        title="Tambah Sesi Konseling Baru">
    <span class="material-symbols-outlined text-[2rem]">add</span>
</button>
@endsection

@section('modals')
{{-- Modal: Setujui & Reschedule (Dynamic Modal) --}}
<div id="modal-atur-jadwal" class="hidden fixed inset-0 bg-inverse-surface/40 backdrop-blur-md z-[100] items-center justify-center p-6">
    <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden border border-surface-variant/40 max-h-[90vh] overflow-y-auto">
        <div class="p-8">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 id="modal-title" class="font-headline-md text-headline-md text-primary font-bold">Atur Ulang Jadwal</h3>
                    <p id="modal-subtitle" class="text-body-md text-on-surface-variant">Pilih waktu baru untuk siswa</p>
                </div>
                <button onclick="closeModal('modal-atur-jadwal')" class="text-on-surface-variant hover:text-secondary p-1 rounded-full hover:bg-surface-container-low transition-all">
                    <span class="material-symbols-outlined select-none">close</span>
                </button>
            </div>
            
            <form id="form-atur-jadwal" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="font-label-md text-on-surface-variant uppercase text-xs font-semibold block">Pilih Tanggal</label>
                        <input type="date" name="tanggal_konseling" required min="{{ today()->toDateString() }}"
                            class="w-full bg-surface border border-outline-variant/40 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary transition-all text-sm outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="font-label-md text-on-surface-variant uppercase text-xs font-semibold block">Pilih Jam</label>
                        <input type="time" name="jam_konseling" required
                            class="w-full bg-surface border border-outline-variant/40 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary transition-all text-sm outline-none">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="font-label-md text-on-surface-variant uppercase text-xs font-semibold block">Tempat / Ruangan</label>
                    <input type="text" name="tempat" required placeholder="Contoh: Ruang Konseling B, Ruang Kelas, dll."
                        class="w-full bg-surface border border-outline-variant/40 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary transition-all text-sm outline-none">
                </div>

                <div class="space-y-2">
                    <label class="font-label-md text-on-surface-variant uppercase text-xs font-semibold block">Catatan Tambahan (opsional)</label>
                    <textarea name="catatan_siswa" placeholder="Tulis instruksi tambahan atau catatan untuk siswa..." rows="3"
                        class="w-full bg-surface border border-outline-variant/40 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary transition-all text-sm outline-none resize-none"></textarea>
                </div>

                <div class="flex gap-3 pt-4 border-t border-outline-variant/20">
                    <button onclick="closeModal('modal-atur-jadwal')" type="button" 
                        class="flex-1 px-6 py-3 border border-outline-variant text-on-surface-variant rounded-xl font-bold hover:bg-surface-container-low transition-all text-sm">
                        Batal
                    </button>
                    <button type="submit" 
                        class="flex-1 px-6 py-3 bg-primary text-white rounded-xl font-bold shadow-md hover:bg-primary-container transition-all text-sm">
                        Simpan Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal: Tolak --}}
<div id="modal-tolak" class="hidden fixed inset-0 bg-inverse-surface/40 backdrop-blur-md z-[100] items-center justify-center p-6">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl overflow-hidden border border-surface-variant/40 max-h-[90vh] overflow-y-auto">
        <div class="p-8">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="font-headline-md text-headline-md text-error font-bold">Batalkan / Tolak Sesi</h3>
                    <p id="tolak-subtitle" class="text-body-md text-on-surface-variant">Tolak pengajuan atau batalkan jadwal</p>
                </div>
                <button onclick="closeModal('modal-tolak')" class="text-on-surface-variant hover:text-secondary p-1 rounded-full hover:bg-surface-container-low transition-all">
                    <span class="material-symbols-outlined select-none">close</span>
                </button>
            </div>
            
            <form id="form-tolak" method="POST" class="space-y-4">
                @csrf
                <div class="space-y-2">
                    <label class="font-label-md text-on-surface-variant uppercase text-xs font-semibold block">Alasan Pembatalan / Penolakan</label>
                    <textarea name="alasan_penolakan" required placeholder="Tuliskan alasan agar siswa mengetahuinya..." rows="4"
                        class="w-full bg-surface border border-outline-variant/40 rounded-xl px-4 py-3 focus:ring-2 focus:ring-error focus:border-error transition-all text-sm outline-none resize-none"></textarea>
                </div>

                <div class="flex gap-3 pt-4 border-t border-outline-variant/20">
                    <button onclick="closeModal('modal-tolak')" type="button" 
                        class="flex-1 px-6 py-3 border border-outline-variant text-on-surface-variant rounded-xl font-bold hover:bg-surface-container-low transition-all text-sm">
                        Kembali
                    </button>
                    <button type="submit" 
                        class="flex-1 px-6 py-3 bg-error text-white rounded-xl font-bold shadow-md hover:bg-red-700 transition-all text-sm">
                        Kirim Penolakan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal: Tambah Jadwal Baru (Create Session) --}}
<div id="modal-create-konseling" class="hidden fixed inset-0 bg-inverse-surface/40 backdrop-blur-md z-[100] items-center justify-center p-6">
    <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden border border-surface-variant/40 max-h-[90vh] overflow-y-auto">
        <div class="p-8">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="font-headline-md text-headline-md text-primary font-bold">Jadwalkan Konseling Baru</h3>
                    <p class="text-body-md text-on-surface-variant">Buat sesi konseling langsung untuk siswa</p>
                </div>
                <button onclick="closeModal('modal-create-konseling')" class="text-on-surface-variant hover:text-secondary p-1 rounded-full hover:bg-surface-container-low transition-all">
                    <span class="material-symbols-outlined select-none">close</span>
                </button>
            </div>
            
            <form action="{{ route('admin.jadwal.konseling.store') }}" method="POST" class="space-y-5">
                @csrf
                <div class="space-y-2">
                    <label class="font-label-md text-on-surface-variant uppercase text-xs font-semibold block">Pilih Siswa</label>
                    <select name="siswa_id" required class="w-full bg-surface border border-outline-variant/40 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary transition-all text-sm outline-none cursor-pointer">
                        <option value="" disabled selected>-- Pilih Siswa --</option>
                        @foreach($siswa_list as $s)
                            <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->kelas ?? 'Kelas TBD' }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="font-label-md text-on-surface-variant uppercase text-xs font-semibold block">Tanggal Konseling</label>
                        <input type="date" name="tanggal_konseling" required min="{{ today()->toDateString() }}"
                            class="w-full bg-surface border border-outline-variant/40 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary transition-all text-sm outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="font-label-md text-on-surface-variant uppercase text-xs font-semibold block">Jam Konseling</label>
                        <input type="time" name="jam_konseling" required
                            class="w-full bg-surface border border-outline-variant/40 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary transition-all text-sm outline-none">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="font-label-md text-on-surface-variant uppercase text-xs font-semibold block">Topik / Jenis Masalah</label>
                    <select name="jenis_masalah" required class="w-full bg-surface border border-outline-variant/40 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary transition-all text-sm outline-none cursor-pointer">
                        <option value="" disabled selected>-- Pilih Topik --</option>
                        <option value="Akademik">Akademik (Nilai, Pembelajaran)</option>
                        <option value="Karir">Karir (Minat, Lanjutan Studi)</option>
                        <option value="Sosial">Sosial (Teman, Konflik, Relasi)</option>
                        <option value="Pribadi">Pribadi (Mental, Emosi, Stres)</option>
                        <option value="Keluarga">Keluarga (Masalah Keluarga, Hubungan Ortu)</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="font-label-md text-on-surface-variant uppercase text-xs font-semibold block">Tempat / Ruangan</label>
                    <input type="text" name="tempat" required placeholder="Contoh: Ruang Konseling B, Ruang Kelas, dll."
                        class="w-full bg-surface border border-outline-variant/40 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary focus:border-primary transition-all text-sm outline-none">
                </div>

                <div class="flex gap-3 pt-4 border-t border-outline-variant/20">
                    <button onclick="closeModal('modal-create-konseling')" type="button" 
                        class="flex-1 px-6 py-3 border border-outline-variant text-on-surface-variant rounded-xl font-bold hover:bg-surface-container-low transition-all text-sm">
                        Batal
                    </button>
                    <button type="submit" 
                        class="flex-1 px-6 py-3 bg-primary text-white rounded-xl font-bold shadow-md hover:bg-primary-container transition-all text-sm">
                        Jadwalkan Sesi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openSetujui(id, name, date, time) {
        document.getElementById('modal-title').textContent = 'Setujui & Tentukan Jadwal';
        document.getElementById('modal-subtitle').textContent = 'Konfirmasi persetujuan dan waktu untuk ' + name;
        
        const form = document.getElementById('form-atur-jadwal');
        form.action = '/admin/konseling/' + id + '/setujui';
        
        // Prep fields
        form.querySelector('input[name="tanggal_konseling"]').value = date || '';
        form.querySelector('input[name="jam_konseling"]').value = time ? time.substring(0, 5) : '';
        
        openModal('modal-atur-jadwal');
    }

    function openReschedule(id, name, date, time, tempat) {
        document.getElementById('modal-title').textContent = 'Atur Ulang Jadwal (Reschedule)';
        document.getElementById('modal-subtitle').textContent = 'Tentukan tanggal atau jam baru untuk ' + name;
        
        const form = document.getElementById('form-atur-jadwal');
        form.action = '/admin/konseling/' + id + '/setujui';
        
        // Prep fields
        form.querySelector('input[name="tanggal_konseling"]').value = date || '';
        form.querySelector('input[name="jam_konseling"]').value = time ? time.substring(0, 5) : '';
        form.querySelector('input[name="tempat"]').value = tempat || '';
        
        openModal('modal-atur-jadwal');
    }

    function openTolak(id, name) {
        document.getElementById('tolak-subtitle').textContent = 'Beri penjelasan alasan penolakan/pembatalan untuk ' + name;
        
        const form = document.getElementById('form-tolak');
        form.action = '/admin/konseling/' + id + '/tolak';
        
        openModal('modal-tolak');
    }

    function openCreate() {
        openModal('modal-create-konseling');
    }

    function confirmMulai(id, name) {
        Swal.fire({
            title: 'Mulai Sesi Konseling?',
            text: `Apakah Anda yakin ingin memulai sesi khusus untuk ${name}? Pastikan siswa sudah siap/hadir.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#005050', // primary
            cancelButtonColor: '#6f7979', // outline
            confirmButtonText: 'Ya, Mulai Sesi!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-xl shadow-sm px-5 py-2.5 text-sm font-bold',
                cancelButton: 'rounded-xl px-4 py-2.5 text-sm font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-mulai-' + id).submit();
            }
        });
    }
</script>
@endsection
