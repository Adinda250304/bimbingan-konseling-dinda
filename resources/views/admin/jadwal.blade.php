@extends('layouts.admin')
@section('title', 'Kelola Jadwal')
@section('nav-title', 'Jadwal Konseling')

@section('content')
<div class="flex items-center justify-between mb-5 flex-wrap gap-3">
    <div>
        <h2 class="text-blue-600 font-bold text-xl">Manajemen Jadwal</h2>
        <p class="text-xs text-gray-400 mt-0.5">Kelola pengajuan dan sesi konseling siswa</p>
    </div>

    {{-- Search + Filter --}}
    <form method="GET" class="flex items-center gap-2 flex-wrap">
        <div class="flex items-center gap-2 border-2 border-gray-200 rounded-xl px-3 py-2 bg-white">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari Siswa..."
                class="text-sm outline-none font-poppins text-gray-700 bg-transparent w-32">
            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
            </svg>
        </div>
        <select name="status" onchange="this.form.submit()"
            class="px-3 py-2 border-2 border-gray-200 rounded-xl text-sm font-poppins text-gray-700 outline-none bg-white cursor-pointer">
            <option value="">Semua Status</option>
            <option value="terjadwal"   {{ $status==='terjadwal'   ? 'selected' : '' }}>Menunggu Persetujuan</option>
            <option value="berlangsung" {{ $status==='berlangsung' ? 'selected' : '' }}>Terjadwal</option>
            <option value="selesai"     {{ $status==='selesai'     ? 'selected' : '' }}>Berlangsung</option>
        </select>
    </form>
</div>

{{-- ══ KANBAN BOARD LAYOUT ══ --}}
@php
    $menunggu_list    = $konselings->where('status', 'menunggu');
    $terjadwal_list   = $konselings->where('status', 'disetujui');
    $berlangsung_list = $konselings->where('status', 'berlangsung');
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
    
    {{-- KOLOM: MENUNGGU PERSETUJUAN --}}
    <div class="flex flex-col bg-gray-50/60 border border-gray-100 rounded-2xl p-4 min-h-[500px]">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-200/60">
            <h3 class="font-bold text-gray-700 flex items-center gap-2 text-sm">
                <span class="w-2.5 h-2.5 rounded-full bg-yellow-400 shadow-[0_0_8px_rgba(250,204,21,0.6)]"></span>
                Menunggu Persetujuan
            </h3>
            <span class="text-xs font-semibold bg-white border border-gray-200 text-gray-600 px-2 py-1 rounded-full shadow-sm">{{ $menunggu_list->count() }}</span>
        </div>
        
        <div class="space-y-3 flex-1">
            @forelse($menunggu_list as $k)
            <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] hover:shadow-[0_8px_20px_-6px_rgba(6,81,237,0.1)] transition-all group">
                <div class="flex items-start justify-between gap-2 mb-3">
                    <div>
                        <h4 class="font-bold text-sm text-gray-800 group-hover:text-blue-600 transition-colors">{{ $k->siswa->name }}</h4>
                        <p class="text-[11px] text-gray-400 font-medium">{{ $k->siswa->kelas ?? 'Kelas —' }}</p>
                        @if($k->guru)
                        <span class="inline-flex items-center gap-1 mt-1 text-[10px] font-semibold text-purple-600 bg-purple-50 border border-purple-100 px-2 py-0.5 rounded-full">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                            Minta: {{ $k->guru->name }}
                        </span>
                        @endif
                    </div>
                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-yellow-100 to-yellow-50 text-yellow-600 flex items-center justify-center text-xs font-bold shrink-0">
                        {{ strtoupper(substr($k->siswa->name, 0, 2)) }}
                    </div>
                </div>
                <div class="bg-gray-50 rounded-lg p-2.5 mb-3 border border-gray-100/50">
                    <p class="text-xs text-gray-600 line-clamp-2 leading-relaxed" title="{{ $k->jenis_masalah }}">{{ $k->jenis_masalah }}</p>
                </div>
                <div class="flex items-center justify-between mt-auto pt-2 border-t border-gray-50">
                    <span class="text-[10px] text-gray-400">{{ $k->created_at->diffForHumans() }}</span>
                    <div class="flex gap-1.5">
                        <button onclick="openTolak({{ $k->id }}, '{{ addslashes($k->siswa->name) }}')" class="px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-md transition text-xs font-semibold" title="Tolak Pengajuan">Tolak</button>
                        <button onclick="openSetujui({{ $k->id }}, '{{ addslashes($k->siswa->name) }}')" class="px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-md transition text-xs font-semibold" title="Setujui & Jadwalkan">Setujui</button>
                    </div>
                </div>
            </div>
            @empty
            <div class="flex flex-col items-center justify-center h-full text-center p-6 border-2 border-dashed border-gray-200 rounded-xl">
                <p class="text-xs text-gray-400 font-medium">Belum ada antrean baru</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- KOLOM: TERJADWAL --}}
    <div class="flex flex-col bg-blue-50/30 border border-blue-100/50 rounded-2xl p-4 min-h-[500px]">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-blue-100">
            <h3 class="font-bold text-gray-700 flex items-center gap-2 text-sm">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-500 shadow-[0_0_8px_rgba(59,130,246,0.6)]"></span>
                Terjadwal
            </h3>
            <span class="text-xs font-semibold bg-white border border-blue-100 text-blue-700 px-2 py-1 rounded-full shadow-sm">{{ $terjadwal_list->count() }}</span>
        </div>
        
        <div class="space-y-3 flex-1">
            @forelse($terjadwal_list as $k)
            <div class="bg-white border border-blue-100/50 rounded-xl p-4 shadow-[0_2px_10px_-3px_rgba(59,130,246,0.05)] hover:shadow-[0_8px_20px_-6px_rgba(59,130,246,0.1)] transition-all group relative">
                {{-- Date Ribbon --}}
                <div class="absolute -top-2.5 right-4 bg-blue-600 text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full shadow-sm border-2 border-white">
                     {{ $k->tanggal_konseling ? $k->tanggal_konseling->format('d M') : 'TBA' }}
                </div>

                <div class="flex items-start gap-3 mb-3 mt-1">
                    <div class="w-9 h-9 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-bold shrink-0 ring-4 ring-white shadow-sm">
                        {{ strtoupper(substr($k->siswa->name, 0, 2)) }}
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-gray-800">{{ $k->siswa->name }}</h4>
                        <div class="flex items-center gap-1 text-[11px] font-medium mt-0.5 {{ $k->tanggal_konseling?->isToday() ? 'text-amber-600' : 'text-blue-500' }}">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $k->tanggal_konseling?->isToday() ? 'Hari ini' : ($k->tanggal_konseling ? $k->tanggal_konseling->translatedFormat('l') : '') }} {{ $k->jam_konseling ? \Carbon\Carbon::parse($k->jam_konseling)->format('H:i') : '' }}
                        </div>
                    </div>
                </div>
                
                <div class="text-[11px] text-gray-500 mb-4 bg-gray-50/50 p-2 rounded-lg border border-gray-50">
                    {{ Str::limit($k->jenis_masalah, 50) }}
                </div>

                <form action="{{ route('admin.konseling.advance', $k) }}" method="POST" class="mt-auto" id="form-mulai-{{ $k->id }}">
                    @csrf
                    <button type="button" onclick="confirmMulai('{{ $k->id }}', '{{ addslashes($k->siswa->name) }}')"
                        class="w-full py-2 bg-white border border-blue-200 text-blue-600 hover:bg-blue-50 rounded-lg transition text-[11px] font-bold tracking-wide flex items-center justify-center gap-1.5 focus:ring-2 focus:ring-blue-100">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        MULAI SESI
                    </button>
                </form>
            </div>
            @empty
            <div class="flex flex-col items-center justify-center h-full text-center p-6 border-2 border-dashed border-blue-100 rounded-xl">
                <p class="text-xs text-blue-400/80 font-medium">Belum ada sesi terjadwal</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- KOLOM: SEDANG BERLANGSUNG --}}
    <div class="flex flex-col bg-emerald-50/30 border border-emerald-100/50 rounded-2xl p-4 min-h-[500px]">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-emerald-100">
            <h3 class="font-bold text-gray-700 flex items-center gap-2 text-sm">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.8)] animate-pulse"></span>
                Sedang Berlangsung
            </h3>
            <span class="text-xs font-semibold bg-white border border-emerald-100 text-emerald-700 px-2 py-1 rounded-full shadow-sm">{{ $berlangsung_list->count() }}</span>
        </div>
        
        <div class="space-y-3 flex-1">
            @forelse($berlangsung_list as $k)
            <div class="bg-white border-2 border-emerald-400/30 rounded-xl p-4 shadow-[0_4px_15px_-3px_rgba(16,185,129,0.15)] relative overflow-hidden group">
                <div class="absolute top-0 left-0 w-1 h-full bg-emerald-400"></div>
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-sm font-bold shrink-0">
                        {{ strtoupper(substr($k->siswa->name, 0, 2)) }}
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-sm text-gray-800">{{ $k->siswa->name }}</h4>
                        <p class="text-[11px] text-gray-500 line-clamp-1 mt-0.5">{{ $k->jenis_masalah }}</p>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-emerald-50">
                    <a href="{{ route('admin.konseling.hasil', $k) }}"
                        class="w-full block text-center py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg transition text-[11px] font-bold tracking-wide shadow-sm shadow-emerald-200">
                        SELESAIKAN SESI
                    </a>
                </div>
            </div>
            @empty
            <div class="flex flex-col items-center justify-center h-full text-center p-6 border-2 border-dashed border-emerald-100 rounded-xl">
                <svg class="w-8 h-8 text-emerald-200 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                <p class="text-[11px] text-emerald-500/70 font-medium">Tidak ada sesi yang <br>sedang jalan</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

@endsection

{{-- ══ MODALS ══ --}}
@section('modals')

{{-- Modal: Setujui & Jadwalkan --}}
<div id="modal-setujui" class="hidden fixed inset-0 bg-black/40 z-[100] items-center justify-center p-4">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl">
        <div class="flex justify-between items-center mb-5">
            <h3 class="font-bold text-lg text-gray-800">Setujui & Tentukan Jadwal</h3>
            <button onclick="closeModal('modal-setujui')" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
        </div>
        <p class="text-sm text-gray-500 mb-4">Siswa: <span id="setujui-name" class="font-semibold text-gray-800"></span></p>
        <form id="setujui-form" method="POST" class="space-y-3">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Konseling</label>
                <input type="date" name="tanggal_konseling" required min="{{ today()->toDateString() }}"
                    class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm outline-none focus:border-blue-400 font-poppins transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jam</label>
                <input type="time" name="jam_konseling" required
                    class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm outline-none focus:border-blue-400 font-poppins transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tempat (opsional)</label>
                <input type="text" name="tempat" placeholder="contoh: Ruang BK 1"
                    class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm outline-none focus:border-blue-400 font-poppins transition">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal('modal-setujui')"
                    class="flex-1 py-2.5 border-2 border-gray-300 rounded-xl text-sm font-semibold text-gray-700 hover:border-gray-400 transition">Batal</button>
                <button type="submit"
                    class="flex-1 py-2.5 bg-green-500 hover:bg-green-600 text-white rounded-xl text-sm font-semibold transition">Setujui</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Tolak --}}
<div id="modal-tolak" class="hidden fixed inset-0 bg-black/40 z-[100] items-center justify-center p-4">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl">
        <div class="flex justify-between items-center mb-5">
            <h3 class="font-bold text-lg text-gray-800">Tolak Pengajuan</h3>
            <button onclick="closeModal('modal-tolak')" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
        </div>
        <p class="text-sm text-gray-500 mb-4">Siswa: <span id="tolak-name" class="font-semibold text-gray-800"></span></p>
        <form id="tolak-form" method="POST" class="space-y-3">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Alasan Penolakan</label>
                <textarea name="alasan_penolakan" rows="3" required placeholder="Tuliskan alasan penolakan..."
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm outline-none focus:border-red-400 font-poppins resize-y transition"></textarea>
            </div>
            <div class="flex gap-3 pt-1">
                <button type="button" onclick="closeModal('modal-tolak')"
                    class="flex-1 py-2.5 border-2 border-gray-300 rounded-xl text-sm font-semibold text-gray-700 hover:border-gray-400 transition">Batal</button>
                <button type="submit"
                    class="flex-1 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-xl text-sm font-semibold transition">Tolak</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function openSetujui(id, name) {
        document.getElementById('setujui-name').textContent = name;
        document.getElementById('setujui-form').action = '/admin/konseling/' + id + '/setujui';
        openModal('modal-setujui');
    }
    function openTolak(id, name) {
        document.getElementById('tolak-name').textContent = name;
        document.getElementById('tolak-form').action = '/admin/konseling/' + id + '/tolak';
        openModal('modal-tolak');
    }
</script>
<script>
    function confirmMulai(id, name) {
        Swal.fire({
            title: 'Mulai Sesi Konseling?',
            text: `Apakah Anda yakin ingin memulai sesi khusus untuk ${name}? Pastikan siswa sudah hadir.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563eb', /* blue-600 */
            cancelButtonColor: '#9ca3af', /* gray-400 */
            confirmButtonText: 'Ya, Mulai Sesi!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-3xl',
                confirmButton: 'rounded-xl shadow-sm px-5',
                cancelButton: 'rounded-xl px-4'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-mulai-' + id).submit();
            }
        });
    }
</script>
@endsection
