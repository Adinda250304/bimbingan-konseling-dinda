@extends('layouts.public')

@section('title', 'Layanan Kami — Teman BK YPML')

@section('styles')
    <style>
        .blur-orb-layanan {
            filter: blur(120px);
            opacity: 0.15;
            pointer-events: none;
        }
    </style>
@endsection

@section('content')
    <!-- Section: Layanan Bimbingan Kami -->
    <section class="pt-32 pb-16 max-w-7xl mx-auto px-6 text-center w-full min-h-screen flex flex-col justify-center relative overflow-hidden">
        
        <!-- Subtle Decorative Background Blurs -->
        <div class="absolute top-1/4 left-1/10 w-96 h-96 rounded-full bg-primary blur-orb-layanan"></div>
        <div class="absolute bottom-1/4 right-1/10 w-96 h-96 rounded-full bg-secondary blur-orb-layanan"></div>

        <!-- Header content -->
        <div class="max-w-2xl mx-auto mb-16 relative z-10 hero-text">
            <span class="text-primary font-bold text-xs uppercase tracking-wider px-4 py-1.5 bg-primary/10 rounded-full border border-primary/20">Layanan Bimbingan</span>
            <h2 class="font-headline-lg text-xl sm:text-2xl lg:text-[26px] xl:text-[32px] font-extrabold text-on-background mt-4 leading-tight">
                Topik Konseling yang Dapat Kamu Pilih
            </h2>
            <p class="text-body-md text-on-surface-variant mt-4 font-medium text-justify">
                Pilih bidang permasalahan yang ingin kamu konsultasikan secara spesifik bersama konselor sekolah. BK adalah ruang aman tanpa penghakiman.
            </p>
        </div>

        <!-- Services Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative z-10" id="layanan-grid">
            
            <!-- Card 1: Pribadi (Teal Theme) -->
            <div class="layanan-card-wrapper">
                <div class="layanan-card group bg-white border border-outline-variant/30 rounded-3xl p-8 text-left shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 flex flex-col justify-between h-full relative overflow-hidden">
                    <div class="absolute -right-8 -top-8 w-24 h-24 rounded-full bg-primary/5 group-hover:scale-150 transition-transform duration-500"></div>
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center mb-6 transition-transform duration-300 group-hover:scale-110">
                            <span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 1;">person</span>
                        </div>
                        <h3 class="font-bold text-lg text-on-background mb-3 group-hover:text-primary transition-colors">Konseling Pribadi</h3>
                        <p class="text-xs text-on-surface-variant leading-relaxed font-medium text-justify">
                            Membantu pengembangan karakter, pemahaman emosi, konsep diri yang sehat, serta melatih cara mengelola kecemasan atau stres pribadi secara bijak.
                        </p>
                    </div>
                    <div class="mt-6 flex items-center gap-1.5 text-xs font-bold text-primary opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        Mulai Sesi <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </div>
                </div>
            </div>

            <!-- Card 2: Belajar/Akademik (Blue/Indigo Theme) -->
            <div class="layanan-card-wrapper">
                <div class="layanan-card group bg-white border border-outline-variant/30 rounded-3xl p-8 text-left shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 flex flex-col justify-between h-full relative overflow-hidden">
                    <div class="absolute -right-8 -top-8 w-24 h-24 rounded-full bg-secondary/5 group-hover:scale-150 transition-transform duration-500"></div>
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-secondary/10 text-secondary flex items-center justify-center mb-6 transition-transform duration-300 group-hover:scale-110">
                            <span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 1;">school</span>
                        </div>
                        <h3 class="font-bold text-lg text-on-background mb-3 group-hover:text-secondary transition-colors">Konseling Belajar</h3>
                        <p class="text-xs text-on-surface-variant leading-relaxed font-medium text-justify">
                            Menemukan metode belajar yang cocok untukmu, cara mengatasi malas, meredakan panik sebelum ujian, serta menyusun strategi mencapai target akademik.
                        </p>
                    </div>
                    <div class="mt-6 flex items-center gap-1.5 text-xs font-bold text-secondary opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        Mulai Sesi <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </div>
                </div>
            </div>

            <!-- Card 3: Sosial (Orange/Amber Theme) -->
            <div class="layanan-card-wrapper">
                <div class="layanan-card group bg-white border border-outline-variant/30 rounded-3xl p-8 text-left shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 flex flex-col justify-between h-full relative overflow-hidden">
                    <div class="absolute -right-8 -top-8 w-24 h-24 rounded-full bg-tertiary/5 group-hover:scale-150 transition-transform duration-500"></div>
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-tertiary/10 text-tertiary flex items-center justify-center mb-6 transition-transform duration-300 group-hover:scale-110">
                            <span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 1;">groups</span>
                        </div>
                        <h3 class="font-bold text-lg text-on-background mb-3 group-hover:text-tertiary transition-colors">Konseling Sosial</h3>
                        <p class="text-xs text-on-surface-variant leading-relaxed font-medium text-justify">
                            Mendukungmu beradaptasi dengan lingkungan kelas, membangun pertemanan yang sehat, serta menyelesaikan perselisihan atau konflik dengan teman sebaya.
                        </p>
                    </div>
                    <div class="mt-6 flex items-center gap-1.5 text-xs font-bold text-tertiary opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        Mulai Sesi <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </div>
                </div>
            </div>

            <!-- Card 4: Keluarga (Rose Theme) -->
            <div class="layanan-card-wrapper">
                <div class="layanan-card group bg-white border border-outline-variant/30 rounded-3xl p-8 text-left shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 flex flex-col justify-between h-full relative overflow-hidden">
                    <div class="absolute -right-8 -top-8 w-24 h-24 rounded-full bg-red-500/5 group-hover:scale-150 transition-transform duration-500"></div>
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-red-500/10 text-red-600 flex items-center justify-center mb-6 transition-transform duration-300 group-hover:scale-110">
                            <span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 1;">family_restroom</span>
                        </div>
                        <h3 class="font-bold text-lg text-on-background mb-3 group-hover:text-red-600 transition-colors">Konseling Keluarga</h3>
                        <p class="text-xs text-on-surface-variant leading-relaxed font-medium text-justify">
                            Ruang aman khusus untuk menceritakan tekanan komunikasi di rumah, menyelaraskan ekspektasi orang tua, atau berdiskusi tentang kenyamanan keluarga.
                        </p>
                    </div>
                    <div class="mt-6 flex items-center gap-1.5 text-xs font-bold text-red-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        Mulai Sesi <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            gsap.from('.hero-text > *', {
                y: 30,
                opacity: 0,
                duration: 0.8,
                stagger: 0.12,
                ease: 'power3.out'
            });

            gsap.from('.layanan-card-wrapper', {
                y: 45,
                opacity: 0,
                duration: 0.8,
                stagger: 0.15,
                ease: 'power3.out',
                delay: 0.2
            });
        });
    </script>
@endsection
