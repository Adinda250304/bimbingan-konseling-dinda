@extends('layouts.admin')
@section('title', 'Detail Konseling')
@section('nav-title', 'Selamat Datang, ' . auth()->user()->name . '!')

@section('content')
@php
    $bc = match($konseling->status){ 'selesai'=>'bg-green-100 text-green-700','disetujui'=>'bg-pink-100 text-pink-700','ditolak'=>'bg-red-100 text-red-700', default=>'bg-yellow-100 text-yellow-700' };
    $bl = match($konseling->status){ 'selesai'=>'Selesai','disetujui'=>'Berlangsung','ditolak'=>'Ditolak', default=>'Menunggu' };
@endphp

<a href="{{ route('admin.jadwal') }}" class="inline-block text-sm text-gray-400 hover:text-blue-600 mb-4 transition">
    &larr; Kembali
</a>

<div class="bg-white rounded-2xl shadow-sm p-5 mb-4">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-blue-600 font-bold text-xl">Detail Konseling</h2>
        <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $bc }}">{{ $bl }}</span>
    </div>

    {{-- Siswa info --}}
    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl mb-5">
        <div class="w-11 h-11 bg-gray-200 rounded-full flex items-center justify-center flex-shrink-0">
            <svg class="w-7 h-7 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div>
            <div class="font-bold text-gray-800">{{ $konseling->siswa->name }}</div>
            <div class="text-sm text-gray-500">{{ $konseling->siswa->kelas ?? '—' }}</div>
            <div class="text-xs text-gray-400">{{ $konseling->siswa->email }}</div>
        </div>
    </div>

    <div class="space-y-3">
        <div class="grid grid-cols-2 gap-3">
            <div class="bg-gray-50 rounded-xl p-3">
                <p class="text-xs text-gray-400 mb-1">Jenis Masalah</p>
                <p class="text-sm font-semibold text-gray-800">{{ $konseling->jenis_masalah }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-3">
                <p class="text-xs text-gray-400 mb-1">Tipe Konseling</p>
                <p class="text-sm font-semibold text-gray-800">{{ ucfirst($konseling->jenis) }}</p>
            </div>
        </div>
        <div class="bg-gray-50 rounded-xl p-3">
            <p class="text-xs text-gray-400 mb-1">Deskripsi Masalah</p>
            <p class="text-sm text-gray-700">{{ $konseling->deskripsi_masalah }}</p>
        </div>
        @if($konseling->tanggal_konseling)
        <div class="grid grid-cols-2 gap-3">
            <div class="bg-gray-50 rounded-xl p-3">
                <p class="text-xs text-gray-400 mb-1">Tanggal</p>
                <p class="text-sm font-semibold text-gray-800">{{ $konseling->tanggal_konseling->format('d M Y') }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-3">
                <p class="text-xs text-gray-400 mb-1">Jam</p>
                <p class="text-sm font-semibold text-gray-800">{{ $konseling->jam_konseling ? \Carbon\Carbon::parse($konseling->jam_konseling)->format('H:i') : '—' }}</p>
            </div>
        </div>
        @endif
        @if($konseling->alasan_penolakan)
        <div class="bg-red-50 border border-red-100 rounded-xl p-3">
            <p class="text-xs text-red-400 mb-1">Alasan Penolakan</p>
            <p class="text-sm text-red-700">{{ $konseling->alasan_penolakan }}</p>
        </div>
        @endif
    </div>
</div>

@if($konseling->status === 'menunggu')
<div class="bg-white rounded-2xl shadow-sm p-5 mb-4">
    <h3 class="font-bold text-gray-800 mb-4">Tindakan</h3>
    <div class="flex gap-3 flex-wrap">
        <form action="{{ route('admin.konseling.status', $konseling) }}" method="POST" class="flex-1 min-w-[140px]">
            @csrf @method('PUT')
            <input type="hidden" name="status" value="disetujui">
            <input type="hidden" name="tanggal_konseling" value="{{ now()->toDateString() }}">
            <button type="submit"
                class="w-full py-2.5 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-xl text-sm transition">
                Setujui & Jadwalkan
            </button>
        </form>
        <button onclick="openModal('modal-tolak')"
            class="flex-1 min-w-[140px] py-2.5 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-xl text-sm transition">
            Tolak
        </button>
    </div>
</div>
@endif

@if($konseling->hasil)
<div class="bg-white rounded-2xl shadow-sm p-5">
    <h3 class="font-bold text-gray-800 mb-3">Hasil & Saran</h3>
    <div class="space-y-3">
        <div class="bg-gray-50 rounded-xl p-3">
            <p class="text-xs text-gray-400 mb-1">Hasil Konseling</p>
            <p class="text-sm text-gray-700">{{ $konseling->hasil->catatan_konselor }}</p>
        </div>
        <div class="bg-gray-50 rounded-xl p-3">
            <p class="text-xs text-gray-400 mb-1">Saran & Tindak Lanjut</p>
            <p class="text-sm text-gray-700">{{ $konseling->hasil->saran }}</p>
        </div>
    </div>
</div>
@endif

{{-- Tindak Lanjut Section --}}
@if($konseling->status === 'selesai' || $konseling->tindakLanjut->count() > 0)
<div class="bg-white rounded-2xl shadow-sm p-5 mt-4">
    <div class="flex items-center gap-3 mb-5">
        <h3 class="font-bold text-gray-800 text-lg tracking-tight">Rencana Tindak Lanjut</h3>
    </div>

    @if($konseling->tindakLanjut->count() > 0)
        <div class="space-y-4">
            @foreach($konseling->tindakLanjut as $tl)
            <div class="rounded-2xl p-5 border border-gray-100 hover:shadow-lg hover:border-blue-100 transition-all duration-300">
                <div class="flex flex-col md:flex-row gap-6 md:gap-8">
                    
                    {{-- Kiri: Detail Dokumen --}}
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-2 sm:gap-3 mb-4">
                            <h4 class="text-[13px] font-extrabold text-blue-900 uppercase tracking-widest">
                                {{ $tl->jenis_label }}
                            </h4>
                            <div class="hidden sm:block w-1.5 h-1.5 rounded-full bg-gray-300"></div>
                            <p class="text-[12px] font-medium text-gray-500 flex items-center gap-1.5">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $tl->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>

                        <div class="pl-4 py-2 border-l-[3px] border-blue-400 bg-gradient-to-r from-blue-50/40 to-transparent rounded-r-xl mb-5">
                            <p class="text-[13px] text-gray-700 leading-relaxed font-medium">"{{ $tl->catatan }}"</p>
                        </div>

                        <div class="flex flex-wrap gap-4 md:gap-6">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full flex items-center justify-center {{ $tl->status_wa === 'terkirim' ? 'bg-emerald-50 text-emerald-500' : ($tl->status_wa === 'gagal' ? 'bg-red-50 text-red-500' : 'bg-gray-100 text-gray-400') }}">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                </div>
                                <span class="text-[11px] font-bold uppercase {{ $tl->status_wa === 'terkirim' ? 'text-emerald-700' : ($tl->status_wa === 'gagal' ? 'text-red-700' : 'text-gray-500') }}">
                                    WA {{ ucfirst($tl->status_wa) }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full flex items-center justify-center {{ $tl->status_email === 'terkirim' ? 'bg-blue-50 text-blue-500' : ($tl->status_email === 'gagal' ? 'bg-red-50 text-red-500' : 'bg-gray-100 text-gray-400') }}">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                                </div>
                                <span class="text-[11px] font-bold uppercase {{ $tl->status_email === 'terkirim' ? 'text-blue-700' : ($tl->status_email === 'gagal' ? 'text-red-700' : 'text-gray-500') }}">
                                    Email {{ ucfirst($tl->status_email) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Kanan: Aksi --}}
                    <div class="flex flex-col justify-center min-w-[210px] md:pl-8 md:border-l border-dashed border-gray-200 mt-4 md:mt-0 gap-2.5">
                        
                        <a href="{{ route('admin.tindak-lanjut.pdf', $tl) }}" target="_blank"
                           class="group flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-800 hover:text-white text-gray-700 rounded-xl text-xs font-semibold transition-all duration-300">
                            <div class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Buka PDF
                            </div>
                            <svg class="w-4 h-4 opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                        
                        <form action="{{ route('admin.tindak-lanjut.wa', $tl) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full group flex items-center justify-between px-4 py-3 bg-emerald-50 hover:bg-emerald-500 hover:text-white text-emerald-700 hover:shadow-md hover:shadow-emerald-200 border border-emerald-100/50 rounded-xl text-xs font-semibold transition-all duration-300">
                                <div class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-emerald-500 group-hover:text-emerald-100" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                    Kirim WhatsApp
                                </div>
                                <svg class="w-4 h-4 opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </form>

                        <form action="{{ route('admin.tindak-lanjut.email', $tl) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full group flex items-center justify-between px-4 py-3 bg-blue-50 hover:bg-blue-600 hover:text-white text-blue-700 hover:shadow-md hover:shadow-blue-200 border border-blue-100/50 rounded-xl text-xs font-semibold transition-all duration-300">
                                <div class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-blue-500 group-hover:text-blue-200" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                                    Kirim via Email
                                </div>
                                <svg class="w-4 h-4 opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </form>
                    </div>

                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="flex flex-col items-center justify-center py-10 px-4 border border-dashed border-gray-200 rounded-xl">
            <div class="w-14 h-14 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                <svg class="w-7 h-7 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <p class="text-[13px] text-gray-500 font-medium">Belum ada tindak lanjut tercatat pada sesi ini.</p>
        </div>
    @endif
</div>
@endif

@endsection

@section('modals')
<div id="modal-tolak" class="hidden fixed inset-0 bg-black/40 z-[100] items-center justify-center p-4">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl">
        <h3 class="font-bold text-lg text-gray-800 mb-4">Alasan Penolakan</h3>
        <form action="{{ route('admin.konseling.status', $konseling) }}" method="POST">
            @csrf @method('PUT')
            <input type="hidden" name="status" value="ditolak">
            <textarea name="alasan_penolakan" rows="4" required placeholder="Tuliskan alasan penolakan..."
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm outline-none focus:border-blue-400 font-poppins resize-y mb-4 transition"></textarea>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeModal('modal-tolak')"
                    class="px-6 py-2.5 border-2 border-gray-300 rounded-xl text-sm font-semibold text-gray-700 hover:border-gray-500 bg-white transition">Batal</button>
                <button type="submit"
                    class="px-6 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-xl text-sm font-semibold transition">Tolak</button>
            </div>
        </form>
    </div>
</div>

@endsection
