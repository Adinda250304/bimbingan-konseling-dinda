@extends('layouts.public')

@section('title', 'Teman BK YPML — Bimbingan & Konseling Sekolah')

@section('content')
    <!-- Hero Section -->
    <section class="relative pt-24 pb-16 md:py-20 overflow-hidden min-h-[calc(100vh-80px)] flex items-center bg-gradient-to-b from-primary/5 to-transparent">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center relative w-full">
            
            <!-- Left Side: Hero Info -->
            <div class="lg:col-span-7 flex flex-col items-start text-left hero-text">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-primary/10 border border-primary/20 text-primary text-xs font-bold uppercase tracking-wider mb-6">
                    <span class="material-symbols-outlined text-xs">sentiment_satisfied</span>
                    Teman Belajar &amp; Tumbuh Siswa
                </div>
                
                <h1 class="font-headline-lg text-xl sm:text-2xl lg:text-[1.625rem] xl:text-[2rem] font-extrabold text-on-background tracking-tight leading-[1.15] mb-5">
                    Ruang Aman Curhat &amp; <span class="text-primary">Bimbingan Siswa YPML</span>
                </h1>
                
                <p class="text-body-md text-on-surface-variant font-medium leading-relaxed max-w-xl mb-6 text-justify">
                    Teman BK hadir untuk membantumu berdiskusi seputar masalah pribadi, belajar, karir, pertemanan, dan keluarga. Temui Guru BK pilihanmu secara tatap muka dengan penjadwalan yang mudah dan rahasia.
                </p>
                
                <div class="flex flex-wrap gap-4 mb-10">
                    <a href="{{ route('login') }}" class="px-8 py-3.5 rounded-2xl bg-primary hover:bg-primary-container text-white font-bold text-base transition-all duration-300 shadow-md shadow-primary/10">
                        Masuk Ke Portal
                    </a>
                    <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-2xl border-2 border-outline-variant/60 text-on-surface-variant font-bold text-base hover:bg-surface-container-low transition-colors">
                        Daftar Akun Baru
                    </a>
                </div>


            </div>
            
            <!-- Right Side: Friendly counselor-student illustration -->
            <div class="lg:col-span-5 flex justify-center hero-visual">
                <div class="relative max-w-md w-full">
                    <img src="{{ asset('img/counseling_student_warm.png') }}" alt="Bimbingan Konseling Sekolah" class="rounded-[2rem] border border-outline-variant/30 shadow-xl w-full object-cover">
                </div>
            </div>

        </div>
    </section>

    <!-- Call to Action Banner -->
    <section class="py-16 max-w-7xl mx-auto px-6 cta-section text-center">
        <div class="bg-primary rounded-3xl p-8 sm:p-12 text-white relative overflow-hidden shadow-lg shadow-primary/10">
            <div class="relative z-10 max-w-xl mx-auto">
                <h2 class="font-headline-lg text-2xl sm:text-3xl font-extrabold mb-4 leading-tight">
                    Jangan Pendam Sendiri, <br>Kami Siap Mendengar Ceritamu
                </h2>
                <p class="text-xs sm:text-sm text-on-primary-container font-medium mb-8 leading-relaxed opacity-90">
                    Setiap hambatan memiliki solusi terbaik. Temui Guru BK di sekolah dengan nyaman dan aman kapan saja.
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('login') }}" class="px-7 py-3.5 rounded-xl bg-white hover:bg-surface-container-low text-primary font-bold text-sm transition-colors shadow-md">
                        Masuk Dasbor Siswa
                    </a>
                    <a href="{{ route('register') }}" class="px-7 py-3.5 rounded-xl border border-white/30 hover:bg-white/10 text-white font-bold text-sm transition-colors">
                        Daftar Akun Baru
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            gsap.registerPlugin(ScrollTrigger);

            // Hero section fade-in
            gsap.from('.hero-text > *', {
                y: 30,
                opacity: 0,
                duration: 0.8,
                stagger: 0.12,
                ease: 'power2.out'
            });

            gsap.from('.hero-visual', {
                scale: 0.96,
                opacity: 0,
                duration: 1,
                delay: 0.2,
                ease: 'power2.out'
            });

            // CTA reveal
            gsap.from('.cta-section', {
                scale: 0.97,
                opacity: 0,
                duration: 0.8,
                scrollTrigger: {
                    trigger: '.cta-section',
                    start: 'top 85%',
                    toggleActions: 'play none none none'
                }
            });
        });
    </script>
@endsection
