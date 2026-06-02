@extends('layouts.public')

@section('title', 'Alur Bimbingan — Teman BK YPML')

@section('styles')
    <style>
        .blur-orb-alur {
            filter: blur(120px);
            opacity: 0.12;
            pointer-events: none;
        }
    </style>
@endsection

@section('content')
    <!-- Alur Layanan / Timeline -->
    <section class="pt-32 pb-16 bg-surface-container-low relative overflow-hidden w-full min-h-screen flex flex-col justify-center">
        
        <!-- Atmospheric decorations -->
        <div class="absolute top-1/4 right-1/10 w-96 h-96 rounded-full bg-primary-container blur-orb-alur"></div>
        <div class="absolute bottom-1/4 left-1/10 w-96 h-96 rounded-full bg-tertiary-container blur-orb-alur"></div>

        <div class="max-w-7xl mx-auto px-6 relative text-center w-full z-10">
            
            <!-- Header Text -->
            <div class="max-w-2xl mx-auto mb-20 hero-text">
                <span class="text-primary font-bold text-xs uppercase tracking-wider px-4 py-1.5 bg-primary/10 rounded-full border border-primary/20">Langkah Mudah</span>
                <h2 class="font-headline-lg text-xl sm:text-2xl lg:text-[1.625rem] xl:text-[2rem] font-extrabold text-on-background mt-4 leading-tight">
                    Alur Proses Bimbingan &amp; Konseling
                </h2>
                <p class="text-body-md text-on-surface-variant mt-4 font-medium text-justify">
                    Kami mempermudah proses penjadwalan bimbingan tatap muka agar kamu bisa berkonsultasi secara tenang dan cepat.
                </p>
            </div>

            <!-- Timeline wrapper -->
            <div class="relative w-full">
                
                <!-- Connecting Line (Desktop Only) -->
                <div class="hidden lg:block absolute top-[2.75rem] left-[8%] right-[8%] h-[0.125rem] bg-gradient-to-r from-primary/10 via-primary/40 to-primary/10 z-0"></div>

                <!-- Timeline Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-10 text-left relative z-10" id="alur-grid">
                    
                    <!-- Langkah 1 -->
                    <div class="alur-card-wrapper">
                        <div class="alur-card group relative bg-white p-8 rounded-3xl border border-outline-variant/30 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 flex flex-col justify-between min-h-[13.75rem]">
                            <!-- Step Badge -->
                            <div class="absolute -top-5 left-8 w-10 h-10 rounded-xl bg-primary text-white font-extrabold flex items-center justify-center shadow-md shadow-primary/20 z-20 text-sm group-hover:scale-110 transition-transform duration-300">
                                1
                            </div>
                            <div class="flex justify-between items-start pt-2">
                                <h3 class="font-bold text-base text-on-background group-hover:text-primary transition-colors">Daftar Akun</h3>
                                <span class="material-symbols-outlined text-outline group-hover:text-primary transition-colors text-2xl">person_add</span>
                            </div>
                            <p class="text-xs text-on-surface-variant leading-relaxed font-medium mt-4 text-justify">
                                Buat akun siswa barumu secara mudah dengan mengisikan nama lengkap, kelas, serta email sekolah yang aktif.
                            </p>
                            <div class="w-full h-1 bg-primary/10 rounded-full overflow-hidden mt-6">
                                <div class="w-1/4 h-full bg-primary transition-all duration-500 group-hover:w-full"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Langkah 2 -->
                    <div class="alur-card-wrapper">
                        <div class="alur-card group relative bg-white p-8 rounded-3xl border border-outline-variant/30 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 flex flex-col justify-between min-h-[13.75rem]">
                            <!-- Step Badge -->
                            <div class="absolute -top-5 left-8 w-10 h-10 rounded-xl bg-primary text-white font-extrabold flex items-center justify-center shadow-md shadow-primary/20 z-20 text-sm group-hover:scale-110 transition-transform duration-300">
                                2
                            </div>
                            <div class="flex justify-between items-start pt-2">
                                <h3 class="font-bold text-base text-on-background group-hover:text-primary transition-colors">Ajukan Sesi</h3>
                                <span class="material-symbols-outlined text-outline group-hover:text-primary transition-colors text-2xl">chat_bubble</span>
                            </div>
                            <p class="text-xs text-on-surface-variant leading-relaxed font-medium mt-4 text-justify">
                                Tuliskan ringkasan singkat topik masalah (pribadi, sosial, dll.) dan pilih Guru BK pendamping yang kamu inginkan.
                            </p>
                            <div class="w-full h-1 bg-primary/10 rounded-full overflow-hidden mt-6">
                                <div class="w-1/2 h-full bg-primary transition-all duration-500 group-hover:w-full"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Langkah 3 -->
                    <div class="alur-card-wrapper">
                        <div class="alur-card group relative bg-white p-8 rounded-3xl border border-outline-variant/30 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 flex flex-col justify-between min-h-[13.75rem]">
                            <!-- Step Badge -->
                            <div class="absolute -top-5 left-8 w-10 h-10 rounded-xl bg-primary text-white font-extrabold flex items-center justify-center shadow-md shadow-primary/20 z-20 text-sm group-hover:scale-110 transition-transform duration-300">
                                3
                            </div>
                            <div class="flex justify-between items-start pt-2">
                                <h3 class="font-bold text-base text-on-background group-hover:text-primary transition-colors">Pilih Waktu</h3>
                                <span class="material-symbols-outlined text-outline group-hover:text-primary transition-colors text-2xl">calendar_month</span>
                            </div>
                            <p class="text-xs text-on-surface-variant leading-relaxed font-medium mt-4 text-justify">
                                Lihat kalender jadwal kosong Guru BK langsung di aplikasi dan pilih slot waktu pertemuan yang paling sesuai dengan jadwalmu.
                            </p>
                            <div class="w-full h-1 bg-primary/10 rounded-full overflow-hidden mt-6">
                                <div class="w-3/4 h-full bg-primary transition-all duration-500 group-hover:w-full"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Langkah 4 -->
                    <div class="alur-card-wrapper">
                        <div class="alur-card group relative bg-white p-8 rounded-3xl border border-outline-variant/30 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 flex flex-col justify-between min-h-[13.75rem]">
                            <!-- Step Badge -->
                            <div class="absolute -top-5 left-8 w-10 h-10 rounded-xl bg-primary text-white font-extrabold flex items-center justify-center shadow-md shadow-primary/20 z-20 text-sm group-hover:scale-110 transition-transform duration-300">
                                4
                            </div>
                            <div class="flex justify-between items-start pt-2">
                                <h3 class="font-bold text-base text-on-background group-hover:text-primary transition-colors">Bimbingan</h3>
                                <span class="material-symbols-outlined text-outline group-hover:text-primary transition-colors text-2xl">check_circle</span>
                            </div>
                            <p class="text-xs text-on-surface-variant leading-relaxed font-medium mt-4 text-justify">
                                Lakukan pertemuan tatap muka di ruang BK yang nyaman. Dapatkan rekomendasi atau saran tindak lanjut langsung di dasbormu.
                            </p>
                            <div class="w-full h-1 bg-primary/10 rounded-full overflow-hidden mt-6">
                                <div class="w-full h-full bg-primary transition-all duration-500 group-hover:w-full"></div>
                            </div>
                        </div>
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

            gsap.from('.alur-card-wrapper', {
                scale: 0.95,
                opacity: 0,
                duration: 0.8,
                stagger: 0.15,
                ease: 'power3.out',
                delay: 0.2
            });
        });
    </script>
@endsection
