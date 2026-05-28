@extends('layouts.public')

@section('title', 'Tanya Jawab (FAQ) — Teman BK YPML')

@section('content')
    <!-- FAQ Section -->
    <section class="pt-32 pb-16 max-w-4xl mx-auto px-6 text-center w-full min-h-screen flex flex-col justify-center">
        <div class="mb-16">
            <span class="text-primary font-bold text-xs uppercase tracking-widest px-3.5 py-1.5 bg-primary/10 rounded-full border border-primary/20">Tanya Jawab</span>
            <h2 class="font-headline-lg text-xl sm:text-2xl lg:text-[26px] xl:text-[32px] font-extrabold text-on-background mt-4 leading-tight">
                Pertanyaan yang Sering Diajukan
            </h2>
            <p class="text-xs sm:text-sm text-on-surface-variant mt-2 font-medium text-justify">
                Ketahui beberapa hal penting sebelum kamu memulai sesi konseling pertamamu.
            </p>
        </div>

        <div class="space-y-4 text-left" id="faq-accordions">
            
            <!-- FAQ 1 -->
            <div class="faq-item bg-white border border-outline-variant/30 rounded-xl overflow-hidden shadow-sm">
                <button onclick="toggleFaq(0)" class="w-full px-6 py-4.5 flex items-center justify-between text-left focus:outline-none">
                    <span class="font-bold text-sm sm:text-base text-on-background">Apakah ceritaku dijamin rahasia?</span>
                    <span class="material-symbols-outlined text-outline transition-transform duration-300 faq-icon">expand_more</span>
                </button>
                <div class="faq-content hidden max-h-0 overflow-hidden transition-all duration-300 bg-background/50 border-t border-outline-variant/20">
                    <p class="px-6 py-4 text-xs sm:text-sm text-on-surface-variant leading-relaxed font-medium text-justify">
                        Tentu saja. Sesuai prinsip kode etik bimbingan konseling, kerahasiaan informasi siswa adalah yang utama. Seluruh cerita hanya diketahui oleh siswa dan Guru BK pendamping.
                    </p>
                </div>
            </div>

            <!-- FAQ 2 -->
            <div class="faq-item bg-white border border-outline-variant/30 rounded-xl overflow-hidden shadow-sm">
                <button onclick="toggleFaq(1)" class="w-full px-6 py-4.5 flex items-center justify-between text-left focus:outline-none">
                    <span class="font-bold text-sm sm:text-base text-on-background">Bagaimana cara konseling dilakukan?</span>
                    <span class="material-symbols-outlined text-outline transition-transform duration-300 faq-icon">expand_more</span>
                </button>
                <div class="faq-content hidden max-h-0 overflow-hidden transition-all duration-300 bg-background/50 border-t border-outline-variant/20">
                    <p class="px-6 py-4 text-xs sm:text-sm text-on-surface-variant leading-relaxed font-medium text-justify">
                        Platform ini digunakan untuk booking jadwal. Setelah pengajuan disetujui Guru BK, kamu bisa datang langsung ke ruang BK di sekolah sesuai jam pilihanmu atau berkoordinasi untuk bimbingan lewat jalur lain.
                    </p>
                </div>
            </div>

            <!-- FAQ 3 -->
            <div class="faq-item bg-white border border-outline-variant/30 rounded-xl overflow-hidden shadow-sm">
                <button onclick="toggleFaq(2)" class="w-full px-6 py-4.5 flex items-center justify-between text-left focus:outline-none">
                    <span class="font-bold text-sm sm:text-base text-on-background">Apakah saya bisa mengubah jadwal pertemuan?</span>
                    <span class="material-symbols-outlined text-outline transition-transform duration-300 faq-icon">expand_more</span>
                </button>
                <div class="faq-content hidden max-h-0 overflow-hidden transition-all duration-300 bg-background/50 border-t border-outline-variant/20">
                    <p class="px-6 py-4 text-xs sm:text-sm text-on-surface-variant leading-relaxed font-medium text-justify">
                        Bisa. Kamu bisa memantau status pengajuan atau melakukan pembatalan sesi bimbingan melalui menu Riwayat Konseling yang ada di dashboard siswamu.
                    </p>
                </div>
            </div>

        </div>
    </section>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            gsap.registerPlugin(ScrollTrigger);

            gsap.from('.faq-item', {
                y: 25,
                opacity: 0,
                duration: 0.5,
                stagger: 0.1,
                ease: 'power2.out'
            });
        });

        // FAQ Accordion logic using GSAP height transition
        function toggleFaq(index) {
            const items = document.querySelectorAll('.faq-item');
            const clickedItem = items[index];
            const content = clickedItem.querySelector('.faq-content');
            const icon = clickedItem.querySelector('.faq-icon');
            
            const isHidden = content.classList.contains('hidden');
            
            // Close all other items
            document.querySelectorAll('.faq-content').forEach(c => {
                c.classList.add('hidden');
                gsap.to(c, {maxHeight: 0, duration: 0.3, ease: 'power1.out'});
            });
            document.querySelectorAll('.faq-icon').forEach(i => {
                gsap.to(i, {rotation: 0, duration: 0.3});
            });

            if (isHidden) {
                content.classList.remove('hidden');
                gsap.to(content, {maxHeight: '150px', duration: 0.3, ease: 'power1.inOut'});
                gsap.to(icon, {rotation: 180, duration: 0.3});
            }
        }
    </script>
@endsection
