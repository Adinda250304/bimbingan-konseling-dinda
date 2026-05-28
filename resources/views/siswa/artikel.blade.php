@extends('layouts.dashboard')
@section('title', 'Artikel Edukatif & Kesehatan Mental')
@section('nav-title', 'Artikel Siswa')

@section('styles')
<style>
    .shadow-subtle {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }
</style>
@endsection

@section('content')
<div class="max-w-6xl mx-auto space-y-10">
    <!-- Page Header -->
    <section>
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <h2 class="font-headline-lg text-headline-lg text-on-surface mb-2">Edukasi &amp; Kesehatan Mental</h2>
                <p class="text-body-lg text-on-surface-variant max-w-2xl">Jelajahi artikel-artikel pilihan untuk mendukung perjalanan akademik, karir, dan kesehatan mental Anda di sekolah.</p>
            </div>
            <div class="bg-primary-container/10 border border-primary/20 rounded-xl p-4 flex items-center gap-4 shrink-0">
                <span class="material-symbols-outlined text-primary text-4xl" style="font-variation-settings: 'FILL' 1;">book</span>
                <div>
                    <p class="font-bold text-primary text-body-sm leading-tight">Wawasan Edukasi</p>
                    <p class="text-body-sm text-on-surface-variant leading-tight mt-1">Belajar &amp; bertumbuh setiap hari.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Articles Grid -->
    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($artikels as $artikel)
            <div class="bg-white border border-outline-variant/30 rounded-2xl shadow-subtle overflow-hidden flex flex-col hover:shadow-md transition-shadow duration-300">
                <div class="h-48 overflow-hidden bg-surface-variant relative">
                    <img src="{{ $artikel->thumbnail }}" alt="{{ $artikel->judul }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">

                </div>
                <div class="p-6 flex-1 flex flex-col justify-between">
                    <div class="space-y-3">
                        <p class="text-[10px] font-bold text-primary uppercase tracking-wider">Oleh: {{ $artikel->author->name }}</p>
                        <h4 class="font-bold text-on-surface text-base line-clamp-2 leading-snug hover:text-primary transition-colors">
                            <a href="{{ route('siswa.artikel.detail', $artikel->id) }}">{{ $artikel->judul }}</a>
                        </h4>
                        <p class="text-on-surface-variant text-sm line-clamp-3 leading-relaxed">
                            {{ Str::limit(strip_tags($artikel->konten), 120) }}
                        </p>
                    </div>
                    <div class="pt-6 border-t border-outline-variant/10 mt-6 flex justify-between items-center">
                        <span class="text-xs text-on-surface-variant">{{ $artikel->created_at->format('d M Y') }}</span>
                        <a href="{{ route('siswa.artikel.detail', $artikel->id) }}" class="text-xs font-bold text-primary hover:underline inline-flex items-center gap-1 cursor-pointer">
                            Baca Selengkapnya
                            <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white border border-outline-variant/30 rounded-2xl py-16 text-center shadow-subtle flex flex-col items-center justify-center">
                <span class="material-symbols-outlined text-outline-variant text-5xl mb-4">article</span>
                <h4 class="font-bold text-on-surface text-base mb-1">Belum ada artikel</h4>
                <p class="text-sm text-on-surface-variant max-w-sm">Guru BK belum mengunggah artikel edukatif untuk saat ini. Silakan kembali lagi nanti!</p>
            </div>
        @endforelse
    </section>

    <!-- Pagination -->
    @if($artikels->hasPages())
        <div class="flex justify-center pt-4">
            {{ $artikels->links() }}
        </div>
    @endif
</div>
@endsection
