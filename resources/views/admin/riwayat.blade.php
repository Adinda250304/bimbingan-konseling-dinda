@extends('layouts.admin')
@section('title', 'Riwayat Konseling')
@section('nav-title', 'Selamat Datang, ' . auth()->user()->name . '!')

@section('content')
<div class="bg-white rounded-2xl shadow-sm p-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
        <h2 class="text-blue-600 font-bold text-xl">Riwayat Konseling</h2>

        <form method="GET" class="flex items-center gap-2 flex-wrap">
            {{-- Cari Nama --}}
            <div class="flex items-center gap-2 border border-gray-200 focus-within:border-blue-400 rounded-lg px-3 py-2 transition-colors bg-gray-50/50">
                <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama siswa..."
                    class="text-xs outline-none font-poppins text-gray-700 bg-transparent w-24">
            </div>

            {{-- Date Picker --}}
            <div class="flex items-center gap-2 border border-gray-200 focus-within:border-blue-400 rounded-lg px-3 py-2 transition-colors bg-gray-50/50">
                <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                    class="text-xs outline-none font-poppins text-gray-700 bg-transparent cursor-pointer">
            </div>

            {{-- Status --}}
            <select name="status" onchange="this.form.submit()"
                class="border border-gray-200 rounded-lg px-3 py-2 text-xs font-poppins text-gray-700 outline-none bg-gray-50/50 cursor-pointer focus:border-blue-400 transition-colors">
                <option value="">Semua Status</option>
                <option value="menunggu"  {{ request('status')==='menunggu'  ? 'selected' : '' }}>Menunggu</option>
                <option value="disetujui" {{ request('status')==='disetujui' ? 'selected' : '' }}>Berlangsung</option>
                <option value="selesai"   {{ request('status')==='selesai'   ? 'selected' : '' }}>Selesai</option>
                <option value="ditolak"   {{ request('status')==='ditolak'   ? 'selected' : '' }}>Ditolak</option>
            </select>

            <button type="submit"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold transition-colors">
                Cari
            </button>

            @if(request()->filled('search') || request()->filled('tanggal') || request()->filled('status'))
            <a href="{{ route('admin.riwayat') }}"
                class="px-3 py-2 bg-gray-50 border border-gray-200 text-gray-500 hover:text-red-600 hover:bg-red-50 hover:border-red-200 rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5 shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                Reset
            </a>
            @endif
        </form>
    </div>

    <div class="border border-gray-100 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100/80 text-[10px] text-gray-400 uppercase tracking-widest font-semibold">
                        <th class="px-6 py-4 whitespace-nowrap">Nama Siswa</th>
                        <th class="px-6 py-4 whitespace-nowrap">Waktu Pengajuan</th>
                        <th class="px-6 py-4 whitespace-nowrap">Detail Permasalahan</th>
                        <th class="px-6 py-4 whitespace-nowrap">Status</th>
                        <th class="px-6 py-4 whitespace-nowrap text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50/80 bg-white">
                    @forelse($konselings as $k)
                    @php
                        $bc = match($k->status){ 'selesai'=>'bg-emerald-50 text-emerald-600 border-emerald-200','disetujui'=>'bg-pink-50 text-pink-600 border-pink-200','ditolak'=>'bg-red-50 text-red-600 border-red-200', default=>'bg-yellow-50 text-yellow-600 border-yellow-200' };
                        $bl = match($k->status){ 'selesai'=>'Selesai','disetujui'=>'Berlangsung','ditolak'=>'Ditolak', default=>'Menunggu' };
                        $dc = match($k->status){ 'selesai'=>'bg-emerald-500','disetujui'=>'bg-pink-500','ditolak'=>'bg-red-500', default=>'bg-yellow-500' };
                    @endphp
                    <tr class="hover:bg-blue-50/20 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div>
                                    <div class="font-bold text-sm text-gray-800 group-hover:text-blue-600 transition-colors">{{ $k->siswa->name }}</div>
                                    <div class="text-[11px] text-gray-400 font-medium mt-0.5">{{ $k->siswa->kelas ?? 'Kelas —' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs font-semibold text-gray-600">{{ $k->created_at->format('d M Y') }}</div>
                            <div class="text-[10px] text-gray-400 mt-0.5">{{ $k->created_at->format('H:i') }} WIB</div>
                        </td>
                        <td class="px-6 py-4 max-w-[250px]">
                            <p class="text-xs text-gray-600 line-clamp-2 leading-relaxed" title="{{ $k->jenis_masalah }}">{{ $k->jenis_masalah }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold border {{ $bc }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $dc }}"></span>
                                {{ $bl }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <a href="{{ route('admin.konseling.show', $k) }}"
                               class="inline-flex items-center justify-center px-4 py-1.5 bg-white border border-gray-200 text-gray-600 rounded-xl text-xs font-semibold hover:bg-gray-50 hover:border-gray-300 hover:text-blue-600 transition-all shadow-sm">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                </div>
                                <h3 class="text-sm font-bold text-gray-700">Belum ada riwayat konseling</h3>
                                <p class="text-xs text-gray-400 mt-1">Data konseling siswa yang masuk akan tampil di sini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($konselings->hasPages())
    <div class="mt-5 flex justify-center">{{ $konselings->appends(request()->query())->links() }}</div>
    @endif
</div>
@endsection
