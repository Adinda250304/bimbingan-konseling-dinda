@extends('layouts.public')

@section('title', 'BK Sahabat Siswa — Teman BK YPML')

@section('content')
    <!-- Main Section: BK Sahabat Siswa -->
    <section class="pt-32 pb-16 bg-white overflow-hidden w-full min-h-screen flex items-center">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center w-full">
            
            <!-- Left: Group Counseling Illustration -->
            <div class="lg:col-span-5 relative flex justify-center bk-sahabat-img">
                <div class="relative max-w-md w-full">
                    <img src="{{ asset('img/group_counseling_warm.png') }}" alt="BK Sahabat Siswa" class="rounded-[2rem] border border-outline-variant/30 shadow-xl w-full object-cover">
                </div>
            </div>
            
            <!-- Right: Text Content & Empathy Points -->
            <div class="lg:col-span-7 bk-sahabat-text flex flex-col items-start">
                <span class="text-secondary font-bold text-xs uppercase tracking-widest px-3.5 py-1.5 bg-secondary-container/20 rounded-full border border-secondary/20">BK Bukan Polisi Sekolah</span>
                <h2 class="font-headline-lg text-lg sm:text-xl lg:text-[22px] xl:text-[26px] font-extrabold text-on-background mt-4 leading-tight text-left">
                    Tempat Nyaman Berbagi Cerita &amp; Solusi
                </h2>
                <p class="text-body-md text-on-surface-variant mt-4 leading-relaxed font-medium text-justify">
                    Kami mendefinisikan ulang peran bimbingan konseling di sekolah. Guru BK bukan untuk menghukum atau mencari kesalahan siswa, melainkan sebagai sahabat pendamping yang siap membantumu bertumbuh, meredakan kecemasan, serta merencanakan masa depan dengan tenang.
                </p>
                
                <!-- Empathy Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-8 w-full text-left">
                    <div class="flex gap-3">
                        <span class="w-8 h-8 rounded-xl bg-primary/10 flex items-center justify-center text-primary flex-shrink-0">
                            <span class="material-symbols-outlined text-sm">favorite</span>
                        </span>
                        <div>
                            <h4 class="font-bold text-sm text-on-background">Empati &amp; Rahasia</h4>
                            <p class="text-[11px] text-on-surface-variant mt-1 leading-relaxed text-justify">
                                Ceritamu dijamin 100% aman dan dirahasiakan bersama konselor pilihanmu.
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <span class="w-8 h-8 rounded-xl bg-primary/10 flex items-center justify-center text-primary flex-shrink-0">
                            <span class="material-symbols-outlined text-sm">school</span>
                        </span>
                        <div>
                            <h4 class="font-bold text-sm text-on-background">Konsultasi Belajar</h4>
                            <p class="text-[11px] text-on-surface-variant mt-1 leading-relaxed text-justify">
                                Diskusikan hambatan belajarmu agar prestasi sekolah tetap terjaga.
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <span class="w-8 h-8 rounded-xl bg-primary/10 flex items-center justify-center text-primary flex-shrink-0">
                            <span class="material-symbols-outlined text-sm">explore</span>
                        </span>
                        <div>
                            <h4 class="font-bold text-sm text-on-background">Arah Karir &amp; Kuliah</h4>
                            <p class="text-[11px] text-on-surface-variant mt-1 leading-relaxed text-justify">
                                Rencanakan studi lanjutan, jurusan kuliah, serta minat bakat masa depan.
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <span class="w-8 h-8 rounded-xl bg-primary/10 flex items-center justify-center text-primary flex-shrink-0">
                            <span class="material-symbols-outlined text-sm">diversity_3</span>
                        </span>
                        <div>
                            <h4 class="font-bold text-sm text-on-background">Hubungan Sosial &amp; Keluarga</h4>
                            <p class="text-[11px] text-on-surface-variant mt-1 leading-relaxed text-justify">
                                Temukan solusi konflik dengan teman sebaya atau hubungan keluarga di rumah.
                            </p>
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
            gsap.registerPlugin(ScrollTrigger);

            gsap.from('.bk-sahabat-img', {
                x: -40,
                opacity: 0,
                duration: 0.8,
                ease: 'power2.out'
            });
            gsap.from('.bk-sahabat-text', {
                x: 40,
                opacity: 0,
                duration: 0.8,
                delay: 0.1,
                ease: 'power2.out'
            });
        });
    </script>
@endsection
