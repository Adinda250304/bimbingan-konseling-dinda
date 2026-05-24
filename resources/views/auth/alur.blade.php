@extends('layouts.public')

@section('title', 'Alur Bimbingan — Teman BK YPML')

@section('content')
    <!-- Alur Layanan / Timeline -->
    <section class="py-24 pt-36 bg-surface-container-low min-h-[80vh] flex flex-col justify-center relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 relative text-center">
            
            <div class="max-w-2xl mx-auto mb-16">
                <span class="text-primary font-bold text-xs uppercase tracking-widest px-3.5 py-1.5 bg-primary/10 rounded-full border border-primary/20">Langkah Mudah</span>
                <h2 class="font-headline-lg text-3xl font-extrabold text-on-background mt-4 leading-tight">
                    Alur Proses Bimbingan &amp; Konseling
                </h2>
                <p class="text-body-md text-on-surface-variant mt-3 font-medium">
                    Tahapan ringkas untuk memesan jadwal bimbingan tatap muka bersama Guru BK sekolah.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-left" id="alur-grid">
                
                <!-- Langkah 1 -->
                <div class="alur-card bg-white p-6 rounded-2xl border border-outline-variant/30 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-9 h-9 rounded-full bg-primary/15 text-primary font-bold flex items-center justify-center text-sm mb-4">1</div>
                    <h3 class="font-bold text-sm text-on-background mb-2">Pendaftaran Akun</h3>
                    <p class="text-[11px] text-on-surface-variant leading-relaxed font-medium">
                        Buat akun siswa baru dengan menggunakan data kelas lengkap di form registrasi.
                    </p>
                </div>

                <!-- Langkah 2 -->
                <div class="alur-card bg-white p-6 rounded-2xl border border-outline-variant/30 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-9 h-9 rounded-full bg-primary/15 text-primary font-bold flex items-center justify-center text-sm mb-4">2</div>
                    <h3 class="font-bold text-sm text-on-background mb-2">Ajukan Pertemuan</h3>
                    <p class="text-[11px] text-on-surface-variant leading-relaxed font-medium">
                        Pilih kategori masalah, tulis ringkasan keluhan, dan pilih Guru BK pendampingmu.
                    </p>
                </div>

                <!-- Langkah 3 -->
                <div class="alur-card bg-white p-6 rounded-2xl border border-outline-variant/30 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-9 h-9 rounded-full bg-primary/15 text-primary font-bold flex items-center justify-center text-sm mb-4">3</div>
                    <h3 class="font-bold text-sm text-on-background mb-2">Pilih Slot Waktu</h3>
                    <p class="text-[11px] text-on-surface-variant leading-relaxed font-medium">
                        Lihat tanggal dan jam kosong pada kalender Guru BK agar jadwal tidak bertabrakan.
                    </p>
                </div>

                <!-- Langkah 4 -->
                <div class="alur-card bg-white p-6 rounded-2xl border border-outline-variant/30 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-9 h-9 rounded-full bg-primary/15 text-primary font-bold flex items-center justify-center text-sm mb-4">4</div>
                    <h3 class="font-bold text-sm text-on-background mb-2">Selesai Konseling</h3>
                    <p class="text-[11px] text-on-surface-variant leading-relaxed font-medium">
                        Laksanakan sesi bimbingan secara tenang dan nyaman. Dapatkan saran tindak lanjut langsung di dasbor.
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

            gsap.from('.alur-card', {
                y: 35,
                opacity: 0,
                duration: 0.6,
                stagger: 0.1,
                ease: 'power2.out'
            });
        });
    </script>
@endsection
