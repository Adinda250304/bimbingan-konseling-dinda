@extends('layouts.dashboard')
@section('title', 'Riwayat Konseling')
@section('nav-title', 'Riwayat Konseling')

@section('content')
<div class="bg-white rounded-2xl shadow-sm p-5">
    <h2 class="text-blue-600 font-bold text-xl mb-4">Riwayat Konseling</h2>

    @forelse($konselings as $k)
    @php
        $bc = match($k->status){ 'selesai'=>'bg-green-100 text-green-700','disetujui'=>'bg-pink-100 text-pink-700','ditolak'=>'bg-red-100 text-red-700', default=>'bg-yellow-100 text-yellow-700' };
        $bl = match($k->status){ 'selesai'=>'Selesai','disetujui'=>'Berlangsung','ditolak'=>'Ditolak', default=>'Menunggu' };
    @endphp
    <div class="border border-gray-100 rounded-xl p-4 mb-3 hover:shadow-sm transition">
        <div class="flex items-start justify-between gap-3">
            <div class="flex-1 min-w-0">
                <div class="font-bold text-sm text-gray-800">{{ $k->jenis_masalah }}</div>
                <div class="text-xs text-gray-400 mt-0.5">
                    @if($k->tanggal_konseling){{ $k->tanggal_konseling->format('d M Y') }}
                    @elseif($k->jadwal){{ $k->jadwal->hari }}, {{ \Carbon\Carbon::parse($k->jadwal->jam_mulai)->format('H:i') }}
                    @else Diajukan {{ $k->created_at->format('d M Y') }}
                    @endif
                </div>
                <div class="text-xs text-gray-500 mt-1">{{ Str::limit($k->deskripsi_masalah, 80) }}</div>
                @if($k->status === 'ditolak' && $k->alasan_penolakan)
                <div class="mt-2 text-xs text-red-600 bg-red-50 rounded-lg px-3 py-1.5">
                    Alasan: {{ $k->alasan_penolakan }}
                </div>
                @endif
            </div>
            <div class="flex flex-col items-end gap-2 shrink-0">
                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $bc }}">{{ $bl }}</span>
                @if(($k->status === 'selesai' || $k->status === 'disetujui') && $k->hasil)
                <button onclick="openModal('modal-hasil-{{ $k->id }}')"
                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg transition text-[11px] font-bold tracking-wide whitespace-nowrap">
                    Laporan Konseling
                </button>
                @endif
            </div>
        </div>
    </div>

    @if(($k->status === 'selesai' || $k->status === 'disetujui') && $k->hasil)
    <div id="modal-hasil-{{ $k->id }}" class="hidden fixed inset-0 bg-black/40 z-[100] items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl flex flex-col max-h-[90vh]">
            {{-- Header: fixed --}}
            <div class="flex justify-between items-center px-6 pt-5 pb-4 border-b border-gray-100 shrink-0">
                <div>
                    <h3 class="text-blue-600 font-bold text-base">Laporan Konseling</h3>
                    <p class="text-[11px] text-gray-400 mt-0.5">{{ $k->jenis_masalah }}</p>
                </div>
                <button onclick="closeModal('modal-hasil-{{ $k->id }}')" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
            </div>
            {{-- Body: scrollable --}}
            <div class="overflow-y-auto px-6 py-4 space-y-4 flex-1">
                <div>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Catatan / Analisis Konselor</p>
                    <div class="text-sm text-gray-700 bg-gray-50 border border-gray-100 rounded-xl p-3 leading-relaxed whitespace-pre-wrap">{{ $k->hasil->catatan_konselor ?? '—' }}</div>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Saran / Rekomendasi</p>
                    <div class="text-sm text-gray-700 bg-gray-50 border border-gray-100 rounded-xl p-3 leading-relaxed whitespace-pre-wrap">{{ $k->hasil->saran ?? '—' }}</div>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1.5">Tindak Lanjut & Monitoring</p>
                    <div class="text-sm text-gray-700 bg-gray-50 border border-gray-100 rounded-xl p-3 leading-relaxed whitespace-pre-wrap">{{ $k->hasil->tindak_lanjut ?? '—' }}</div>
                </div>
            </div>
            {{-- Footer: fixed --}}
            <div class="px-6 py-4 border-t border-gray-100 flex justify-end shrink-0">
                <button onclick="closeModal('modal-hasil-{{ $k->id }}')" class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition">Tutup Laporan</button>
            </div>
        </div>
    </div>
    @endif
    @empty
    <div class="py-12 text-center text-gray-400 text-sm">
        Belum ada riwayat konseling.
        <div class="mt-2">
            <a href="{{ route('siswa.pengajuan') }}" class="text-blue-600 font-semibold hover:underline">Ajukan sekarang</a>
        </div>
    </div>
    @endforelse

    @if($konselings->hasPages())
    <div class="mt-4 flex justify-center">{{ $konselings->links() }}</div>
    @endif
</div>
@endsection
