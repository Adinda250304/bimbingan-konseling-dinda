@extends('layouts.dashboard')
@section('title', $artikel->judul)
@section('nav-title', 'Detail Artikel')

@section('styles')
<style>
    .shadow-subtle {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }
</style>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <!-- Back Button -->
    <div>
        <a href="{{ route('siswa.artikel.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-primary hover:text-primary-container transition-colors cursor-pointer group">
            <span class="material-symbols-outlined text-[1.125rem] group-hover:-translate-x-1 transition-transform">arrow_back</span>
            Kembali ke Daftar Artikel
        </a>
    </div>

    <!-- Article Content -->
    <article class="bg-white border border-outline-variant/30 rounded-3xl shadow-subtle overflow-hidden">
        <!-- Thumbnail -->
        <div class="h-64 md:h-96 w-full overflow-hidden bg-surface-variant relative">
            <img src="{{ $artikel->thumbnail }}" alt="{{ $artikel->judul }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
        </div>

        <!-- Body -->
        <div class="p-6 md:p-10 space-y-6">
            <!-- Meta Info -->
            <div class="flex flex-wrap items-center gap-4 text-xs text-on-surface-variant">
                <span class="px-3 py-1 rounded-full bg-primary/10 text-primary font-bold uppercase">
                    Oleh: {{ $artikel->author->name }}
                </span>
                <span class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-[1rem]">calendar_today</span>
                    {{ $artikel->created_at->format('d M Y') }}
                </span>

            </div>

            <!-- Title -->
            <h1 class="font-display text-2xl md:text-4xl font-bold text-on-surface leading-tight">
                {{ $artikel->judul }}
            </h1>

            <hr class="border-outline-variant/30">

            <!-- Content text -->
            <div class="text-on-surface-variant text-sm md:text-base leading-relaxed md:leading-loose whitespace-pre-line space-y-4">
                {!! nl2br(e($artikel->konten)) !!}
            </div>
        </div>
    </article>

    <!-- Recommendation Section -->
    @if($rekomendasi->count() > 0)
        <section class="space-y-6 pt-6">
            <h3 class="font-headline-sm text-headline-sm font-bold text-on-surface">Rekomendasi Artikel Lainnya</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($rekomendasi as $rek)
                    <div class="bg-white border border-outline-variant/30 rounded-2xl shadow-subtle overflow-hidden flex flex-col hover:shadow-md transition-shadow duration-300">
                        <div class="h-32 overflow-hidden bg-surface-variant relative">
                            <img src="{{ $rek->thumbnail }}" alt="{{ $rek->judul }}" class="w-full h-full object-cover">

                        </div>
                        <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                            <h5 class="font-bold text-on-surface text-sm line-clamp-2 hover:text-primary transition-colors">
                                <a href="{{ route('siswa.artikel.detail', $rek->id) }}">{{ $rek->judul }}</a>
                            </h5>
                            <div class="flex justify-between items-center text-[0.6875rem] text-on-surface-variant pt-2 border-t border-outline-variant/10">
                                <span>{{ $rek->created_at->format('d M Y') }}</span>
                                <a href="{{ route('siswa.artikel.detail', $rek->id) }}" class="font-bold text-primary hover:underline cursor-pointer">Baca</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
