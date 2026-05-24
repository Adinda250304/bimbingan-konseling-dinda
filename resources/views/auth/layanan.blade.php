@extends('layouts.public')

@section('title', 'Layanan Kami — Teman BK YPML')

@section('content')
    <!-- Section: Layanan Bimbingan Kami -->
    <section class="py-24 pt-36 max-w-7xl mx-auto px-6 text-center min-h-[80vh] flex flex-col justify-center">
        <div class="max-w-2xl mx-auto mb-16">
            <span class="text-primary font-bold text-xs uppercase tracking-widest px-3.5 py-1.5 bg-primary/10 rounded-full border border-primary/20">Layanan Bimbingan</span>
            <h2 class="font-headline-lg text-3xl font-extrabold text-on-background mt-4 leading-tight">
                Topik Konseling yang Dapat Kamu Pilih
            </h2>
            <p class="text-body-md text-on-surface-variant mt-3 font-medium">
                Pilih bidang permasalahan yang ingin kamu konsultasikan secara spesifik bersama konselor sekolah.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6" id="layanan-grid">
            
            <!-- Card 1: Pribadi -->
            <div class="layanan-card bg-white border border-outline-variant/30 rounded-2xl p-6 text-left shadow-sm hover:shadow-md transition-shadow">
                <span class="material-symbols-outlined text-3xl text-primary mb-4 block">person</span>
                <h3 class="font-bold text-base text-on-background mb-2">Konseling Pribadi</h3>
                <p class="text-xs text-on-surface-variant leading-relaxed font-medium">
                    Membahas pengembangan diri, pemahaman emosi, konsep diri, serta cara mengatasi stres personal.
                </p>
            </div>

            <!-- Card 2: Belajar/Akademik -->
            <div class="layanan-card bg-white border border-outline-variant/30 rounded-2xl p-6 text-left shadow-sm hover:shadow-md transition-shadow">
                <span class="material-symbols-outlined text-3xl text-primary mb-4 block">book</span>
                <h3 class="font-bold text-base text-on-background mb-2">Konseling Belajar</h3>
                <p class="text-xs text-on-surface-variant leading-relaxed font-medium">
                    Membantu mengatasi kesulitan belajar, efektivitas cara belajar, serta persiapan menghadapi ujian sekolah.
                </p>
            </div>

            <!-- Card 3: Sosial & Pertemanan -->
            <div class="layanan-card bg-white border border-outline-variant/30 rounded-2xl p-6 text-left shadow-sm hover:shadow-md transition-shadow">
                <span class="material-symbols-outlined text-3xl text-primary mb-4 block">groups</span>
                <h3 class="font-bold text-base text-on-background mb-2">Konseling Sosial</h3>
                <p class="text-xs text-on-surface-variant leading-relaxed font-medium">
                    Membimbing adaptasi lingkungan sekolah, membina pertemanan sehat, serta penyelesaian perselisihan kelompok.
                </p>
            </div>

            <!-- Card 4: Keluarga -->
            <div class="layanan-card bg-white border border-outline-variant/30 rounded-2xl p-6 text-left shadow-sm hover:shadow-md transition-shadow">
                <span class="material-symbols-outlined text-3xl text-primary mb-4 block">family_restroom</span>
                <h3 class="font-bold text-base text-on-background mb-2">Konseling Keluarga</h3>
                <p class="text-xs text-on-surface-variant leading-relaxed font-medium">
                    Ruang khusus mendiskusikan penyesuaian masalah komunikasi dengan orang tua atau situasi di rumah.
                </p>
            </div>

        </div>
    </section>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            gsap.registerPlugin(ScrollTrigger);

            gsap.from('.layanan-card', {
                y: 35,
                opacity: 0,
                duration: 0.6,
                stagger: 0.1,
                ease: 'power2.out'
            });
        });
    </script>
@endsection
