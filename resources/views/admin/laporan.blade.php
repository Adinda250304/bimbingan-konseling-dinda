@extends('layouts.admin')
@section('title', 'Rekap Laporan Konseling')

@section('content')
<div class="space-y-6">
    <!-- Header & Filter -->
    <div class="bg-[#F8F3F3] rounded-2xl shadow-sm p-6 border border-gray-100">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Rekap Laporan</h2>
                <p class="text-sm text-gray-500 mt-1">Pantau dan unduh riwayat konseling berdasarkan periode tertentu.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.laporan.pdf', request()->query()) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-semibold transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 9h1a1 1 0 110 2H9a1 1 0 010-2zM9 13h6a1 1 0 110 2H9a1 1 0 010-2z"/></svg>
                    Unduh PDF
                </a>
            </div>
        </div>

        <div class="flex flex-wrap gap-2 mb-6">
            <a href="{{ route('admin.laporan', ['start_date' => now()->subMonths(3)->toDateString(), 'end_date' => now()->toDateString()]) }}" 
               class="px-4 py-2 bg-gray-50 border border-gray-200 text-gray-600 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-2">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                3 Bulan Terakhir
            </a>
            <a href="{{ route('admin.laporan', ['start_date' => now()->subMonths(6)->toDateString(), 'end_date' => now()->toDateString()]) }}" 
               class="px-4 py-2 bg-gray-50 border border-gray-200 text-gray-600 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-2">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                6 Bulan Terakhir
            </a>
            <a href="{{ route('admin.laporan', ['start_date' => now()->startOfYear()->toDateString(), 'end_date' => now()->toDateString()]) }}" 
               class="px-4 py-2 bg-gray-50 border border-gray-200 text-gray-600 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-2">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Tahun Ini
            </a>
        </div>

        <form method="GET" action="{{ route('admin.laporan') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-gray-400 uppercase tracking-wider ml-1">Mulai Dari</label>
                <div class="relative">
                    <input type="date" name="start_date" value="{{ $start_date }}"
                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none">
                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                </div>
            </div>
            
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-gray-400 uppercase tracking-wider ml-1">Sampai Dengan</label>
                <div class="relative">
                    <input type="date" name="end_date" value="{{ $end_date }}"
                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none">
                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-bold text-gray-400 uppercase tracking-wider ml-1">Status</label>
                <select name="status" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none appearance-none cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Berlangsung</option>
                    <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold transition-all shadow-md flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    Filter Rekap
                </button>
            </div>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-[#F8F3F3] p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div>
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Sesi</div>
                <div class="text-xl font-black text-gray-800">{{ $stats['total'] }}</div>
            </div>
        </div>
        
        <div class="bg-[#F8F3F3] p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Selesai</div>
                <div class="text-xl font-black text-gray-800">{{ $stats['selesai'] }}</div>
            </div>
        </div>

        <div class="bg-[#F8F3F3] p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Berlangsung</div>
                <div class="text-xl font-black text-gray-800">{{ $stats['berlangsung'] }}</div>
            </div>
        </div>

        <div class="bg-[#F8F3F3] p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-rose-50 rounded-xl flex items-center justify-center text-rose-600">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Ditolak</div>
                <div class="text-xl font-black text-gray-800">{{ $stats['ditolak'] }}</div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-[#F8F3F3] rounded-2xl shadow-sm overflow-hidden border border-gray-400">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-100 border-b border-gray-400 text-[10px] text-gray-600 uppercase tracking-widest font-bold">
                        <th class="px-6 py-4 border-r border-gray-400 text-center">Siswa</th>
                        <th class="px-6 py-4 border-r border-gray-400 text-center">Waktu</th>
                        <th class="px-6 py-4 border-r border-gray-400 text-center">Permasalahan</th>
                        <th class="px-6 py-4 border-r border-gray-400 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Hasil</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($konselings as $k)
                    <tr class="hover:bg-gray-50 transition-colors border-b border-gray-400">
                        <td class="px-6 py-4 whitespace-nowrap border-r border-gray-400">
                            <div class="font-bold text-sm text-gray-800 ">{{ $k->siswa->name }}</div>
                            <div class="text-[11px] text-gray-400 ">{{ $k->siswa->kelas }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap border-r border-gray-400 ">
                            <div class="text-xs font-semibold text-gray-600">{{ $k->created_at->format('d M Y') }}</div>
                            <div class="text-[10px] text-gray-400">{{ $k->created_at->format('H:i') }} WIB</div>
                        </td>
                        <td class="px-6 py-4 border-r border-gray-400 ">
                            <p class="text-xs text-gray-600 leading-relaxed">{{ $k->jenis_masalah }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap border-r border-gray-400 text-center">
                            @php
                                $bc = match($k->status){ 
                                    'selesai'=>'bg-emerald-50 text-emerald-600 border-emerald-200',
                                    'berlangsung'=>'bg-pink-50 text-pink-600 border-pink-200',
                                    'disetujui'=>'bg-blue-50 text-blue-600 border-blue-200',
                                    'ditolak'=>'bg-red-50 text-red-600 border-red-200', 
                                    default=>'bg-yellow-50 text-yellow-600 border-yellow-200' 
                                };
                                $bl = match($k->status){ 
                                    'selesai'=>'Selesai',
                                    'berlangsung'=>'Berlangsung',
                                    'disetujui'=>'Terjadwal',
                                    'ditolak'=>'Ditolak', 
                                    default=>'Menunggu' 
                                };
                                $dc = match($k->status){ 
                                    'selesai'=>'bg-emerald-500',
                                    'berlangsung'=>'bg-pink-500',
                                    'disetujui'=>'bg-blue-500',
                                    'ditolak'=>'bg-red-500', 
                                    default=>'bg-yellow-500' 
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold border {{ $bc }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $dc }}"></span>
                                {{ $bl }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <p class="text-xs text-gray-500 italic">
                                {{ $k->hasil ? Str::limit($k->hasil->catatan_konselor, 50) : '-' }}
                            </p>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                </div>
                                <h3 class="text-sm font-bold text-gray-700">Tidak ada data untuk periode ini</h3>
                                <p class="text-xs text-gray-400 mt-1">Silakan sesuaikan filter tanggal di atas.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
