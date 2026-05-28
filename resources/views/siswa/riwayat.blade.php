@extends('layouts.dashboard')
@section('title', 'Riwayat Konseling')
@section('nav-title', 'Riwayat Konseling')

@section('content')
@php
    $totalSesi = auth()->user()->konselings()->count();
    $selesaiSesi = auth()->user()->konselings()->where('status', 'selesai')->count();
    $rateSelesai = $totalSesi > 0 ? round(($selesaiSesi / $totalSesi) * 100) : 0;
    
    $fokusGroups = auth()->user()->konselings()
        ->selectRaw('jenis_masalah, count(*) as count')
        ->groupBy('jenis_masalah')
        ->orderByDesc('count')
        ->get();
@endphp

<div class="max-w-6xl mx-auto space-y-10">
    <!-- Page Header -->
    <section>
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <h2 class="font-headline-lg text-headline-lg text-on-surface mb-2">Riwayat Konseling</h2>
                <p class="text-body-lg text-on-surface-variant max-w-2xl">Arsip perjalanan konseling Anda. Semua informasi di sini bersifat rahasia dan hanya dapat diakses oleh Anda dan Guru BK terkait.</p>
            </div>
            <div class="bg-primary-container/10 border border-primary/20 rounded-xl p-4 flex items-center gap-4 shrink-0">
                <span class="material-symbols-outlined text-primary text-4xl" style="font-variation-settings: 'FILL' 1;">security</span>
                <div>
                    <p class="font-bold text-primary text-body-sm leading-tight">Privasi Terjamin</p>
                    <p class="text-body-sm text-on-surface-variant leading-tight mt-1">Data Anda dienkripsi secara aman.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats/Summary Bento Grid -->
    <section class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Sesi -->
        <div class="md:col-span-1 bg-white border border-outline-variant/30 p-6 rounded-2xl shadow-[0px_4px_20px_rgba(16,106,106,0.04)]">
            <p class="text-label-md text-on-surface-variant uppercase mb-1">Total Sesi</p>
            <p class="text-display-lg font-display-lg text-primary">{{ $totalSesi }}</p>
            <p class="text-body-sm text-on-surface-variant mt-2 flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">trending_up</span>
                Keseluruhan sesi
            </p>
        </div>

        <!-- Status Selesai -->
        <div class="md:col-span-1 bg-white border border-outline-variant/30 p-6 rounded-2xl shadow-[0px_4px_20px_rgba(16,106,106,0.04)]">
            <p class="text-label-md text-on-surface-variant uppercase mb-1">Status Selesai</p>
            <p class="text-display-lg font-display-lg text-secondary">{{ $rateSelesai }}%</p>
            <div class="w-full bg-surface-container rounded-full h-1.5 mt-4">
                <div class="bg-secondary h-1.5 rounded-full" style="width: {{ $rateSelesai }}%"></div>
            </div>
        </div>

        <!-- Fokus Terbanyak -->
        <div class="md:col-span-2 relative overflow-hidden bg-white border border-outline-variant/30 p-6 rounded-2xl shadow-[0px_4px_20px_rgba(16,106,106,0.04)]">
            <div class="relative z-10">
                <p class="text-label-md text-on-surface-variant uppercase mb-2">Fokus Topik Masalah</p>
                <div class="flex flex-wrap gap-2 mt-3">
                    @forelse($fokusGroups as $group)
                        @php
                            $bg = match(strtolower($group->jenis_masalah)) {
                                'akademik' => 'bg-primary/10 text-primary',
                                'pribadi' => 'bg-tertiary/10 text-tertiary',
                                'karir' => 'bg-secondary/10 text-secondary',
                                'sosial' => 'bg-blue-100 text-blue-700 border border-blue-700/10',
                                'keluarga' => 'bg-purple-100 text-purple-700 border border-purple-700/10',
                                default => 'bg-outline-variant/10 text-outline'
                            };
                        @endphp
                        <span class="px-3 py-1.5 {{ $bg }} rounded-full text-body-sm font-bold">{{ $group->jenis_masalah }} ({{ $group->count }})</span>
                    @empty
                        <span class="text-xs text-on-surface-variant italic">Belum ada fokus topik masalah</span>
                    @endforelse
                </div>
            </div>
            <span class="absolute -right-4 -bottom-4 material-symbols-outlined text-8xl text-primary/5">analytics</span>
        </div>
    </section>

    <!-- History List -->
    <section class="space-y-4">
        <div class="flex items-center justify-between px-2 mb-4">
            <h3 class="font-headline-sm text-headline-sm text-on-surface">Daftar Sesi Lampau</h3>
            <div class="flex gap-2">
                <a href="{{ route('siswa.pengajuan') }}" class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg text-body-sm font-bold shadow-sm hover:opacity-90 transition-all cursor-pointer">
                    <span class="material-symbols-outlined text-sm">add</span>
                    Ajukan Sesi Baru
                </a>
            </div>
        </div>

        <div class="space-y-4">
            @forelse($konselings as $k)
                @php
                    $targetDate = $k->tanggal_konseling ?: ($k->jadwal ? \Carbon\Carbon::parse($k->jadwal->tanggal) : $k->created_at);
                    
                    $month = strtoupper($targetDate->translatedFormat('M'));
                    $day = $targetDate->format('d');
                    $year = $targetDate->format('Y');

                    $bc = match($k->status) {
                        'selesai' => 'bg-green-100 text-green-700 border border-green-700/20',
                        'disetujui' => 'bg-[#E8F0FE] text-[#1A73E8] border border-[#1A73E8]/20',
                        'ditolak' => 'bg-[#FCE8E6] text-[#C5221F] border border-[#C5221F]/20',
                        default => 'bg-[#FFF8E1] text-[#F57F17] border border-[#F57F17]/20'
                    };
                    $bl = match($k->status) {
                        'selesai' => 'Selesai',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                        default => 'Menunggu'
                    };

                    $categoryClass = match(strtolower($k->jenis_masalah)) {
                        'akademik' => 'bg-primary/10 text-primary',
                        'pribadi' => 'bg-tertiary/10 text-tertiary',
                        'karir' => 'bg-secondary/10 text-secondary',
                        'sosial' => 'bg-blue-100 text-blue-700 border border-blue-700/10',
                        'keluarga' => 'bg-purple-100 text-purple-700 border border-purple-700/10',
                        default => 'bg-outline-variant/10 text-outline'
                    };
                @endphp

                <!-- Session Item -->
                <div class="group bg-white border border-outline-variant/30 rounded-2xl p-6 shadow-[0px_4px_20px_rgba(16,106,106,0.04)] hover:shadow-md transition-all duration-300">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-6">
                            <!-- Date Badge -->
                            <div class="flex flex-col items-center justify-center bg-surface-container-low w-20 h-20 rounded-xl shrink-0">
                                <span class="text-label-md text-primary font-bold">{{ $month }}</span>
                                <span class="text-headline-md font-extrabold text-on-surface leading-none py-0.5">{{ $day }}</span>
                                <span class="text-label-md text-on-surface-variant font-medium">{{ $year }}</span>
                            </div>
                            
                            <!-- Session Details -->
                            <div>
                                <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                                    <span class="px-2.5 py-0.5 {{ $categoryClass }} rounded text-[10px] font-bold uppercase tracking-wider">{{ $k->jenis_masalah }}</span>
                                    <span class="px-2.5 py-0.5 {{ $bc }} rounded text-[10px] font-bold uppercase tracking-wider">{{ $bl }}</span>
                                    @if($k->rating)
                                        <span class="flex items-center gap-0.5 text-[11px] text-amber-500 font-bold bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                                            @for($i=1; $i<=5; $i++)
                                                <span class="material-symbols-outlined text-[12px] {{ $i <= $k->rating ? 'text-amber-500' : 'text-gray-300' }}" style="font-variation-settings: 'FILL' {{ $i <= $k->rating ? 1 : 0 }};">star</span>
                                            @endfor
                                        </span>
                                    @endif
                                </div>
                                <h4 class="font-headline-sm text-headline-sm text-on-surface mb-1.5">{{ Str::limit($k->deskripsi_masalah, 65) }}</h4>
                                <div class="flex items-center gap-4 text-body-sm text-on-surface-variant flex-wrap">
                                    <div class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm">person</span>
                                        <span>{{ $k->guru ? $k->guru->name : 'Guru BK' }}</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm">schedule</span>
                                        <span>{{ $k->jam_konseling ? \Carbon\Carbon::parse($k->jam_konseling)->format('H:i') . ' WIB' : 'TBD' }}</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm">room</span>
                                        <span>{{ $k->tempat ?? ($k->jadwal ? $k->jadwal->tempat : 'TBD') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 shrink-0 w-full md:w-auto mt-4 md:mt-0">
                            @if($k->status === 'selesai' && is_null($k->rating))
                                <button class="flex items-center justify-center gap-2 px-5 py-2.5 bg-secondary text-white rounded-xl font-bold text-body-sm hover:bg-secondary/90 transition-all cursor-pointer w-full sm:w-auto" onclick="openRatingModal({{ $k->id }}, '{{ $k->guru ? $k->guru->name : 'Guru BK' }}')">
                                    <span class="material-symbols-outlined text-sm">star</span>
                                    Beri Ulasan
                                </button>
                            @endif
                            <button class="flex items-center justify-center gap-2 px-6 py-2.5 bg-primary/10 text-primary border border-primary/20 rounded-xl font-bold text-body-sm hover:bg-primary/20 transition-all cursor-pointer w-full sm:w-auto" onclick="toggleNote('note-{{ $k->id }}')">
                                <span class="material-symbols-outlined text-sm">visibility</span>
                                Lihat Catatan
                            </button>
                        </div>
                    </div>

                    <!-- Collapsible Note Panel -->
                    <div class="hidden mt-6 pt-6 border-t border-dashed border-outline-variant" id="note-{{ $k->id }}">
                        @if($k->status === 'selesai' && ($k->hasil || $k->rating))
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Counselor Notes -->
                                <div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant/30">
                                    <h5 class="font-bold text-on-surface text-body-sm mb-3 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-primary">description</span>
                                        Catatan Sesi BK
                                    </h5>
                                    <p class="text-body-sm text-on-surface-variant italic leading-relaxed">
                                        "{{ ($k->hasil && $k->hasil->catatan_konselor) ? $k->hasil->catatan_konselor : 'Catatan sesi belum diisi oleh konselor.' }}"
                                    </p>
                                </div>

                                <!-- Counselor Recommendations -->
                                <div class="bg-primary/5 p-5 rounded-xl border border-primary/10">
                                    <h5 class="font-bold text-primary text-body-sm mb-3 flex items-center gap-2">
                                        <span class="material-symbols-outlined">lightbulb</span>
                                        Rekomendasi Guru BK
                                    </h5>
                                    @if($k->hasil && $k->hasil->saran)
                                        @php
                                            $saranLines = explode("\n", str_replace("\r", "", trim($k->hasil->saran)));
                                        @endphp
                                        <ul class="text-body-sm text-on-surface-variant space-y-2 list-disc pl-4 leading-relaxed">
                                            @foreach($saranLines as $line)
                                                @if(trim($line))
                                                    <li>{{ ltrim(trim($line), '-*• ') }}</li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-body-sm text-on-surface-variant italic leading-relaxed">Tidak ada rekomendasi khusus.</p>
                                    @endif
                                </div>

                                <!-- Student Feedback / Rating -->
                                <div class="bg-secondary/5 p-5 rounded-xl border border-secondary/10">
                                    <h5 class="font-bold text-secondary text-body-sm mb-3 flex items-center gap-2">
                                        <span class="material-symbols-outlined">reviews</span>
                                        Penilaian & Ulasan Saya
                                    </h5>
                                    @if($k->rating)
                                        <div class="flex items-center gap-0.5 mb-2">
                                            @for($i=1; $i<=5; $i++)
                                                <span class="material-symbols-outlined text-sm {{ $i <= $k->rating ? 'text-amber-500' : 'text-gray-300' }}" style="font-variation-settings: 'FILL' {{ $i <= $k->rating ? 1 : 0 }};">star</span>
                                            @endfor
                                        </div>
                                        <p class="text-body-sm text-on-surface-variant italic leading-relaxed">
                                            "{{ $k->feedback_siswa ?? 'Tidak menuliskan pesan tambahan.' }}"
                                        </p>
                                    @else
                                        <p class="text-body-sm text-on-surface-variant italic leading-relaxed mb-4">Anda belum memberikan ulasan bintang dan umpan balik.</p>
                                        <button class="flex items-center gap-2 px-4 py-2 bg-secondary text-white rounded-lg font-bold text-xs hover:bg-secondary/90 transition-all cursor-pointer" onclick="openRatingModal({{ $k->id }}, '{{ $k->guru ? $k->guru->name : 'Guru BK' }}')">
                                            <span class="material-symbols-outlined text-xs">star</span>
                                            Beri Ulasan Sekarang
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @elseif($k->status === 'ditolak')
                            <div class="bg-error-container/10 p-5 rounded-xl border border-error-container/20">
                                <h5 class="font-bold text-error text-body-sm mb-2 flex items-center gap-2">
                                    <span class="material-symbols-outlined">cancel</span>
                                    Pengajuan Ditolak
                                </h5>
                                <p class="text-body-sm text-on-surface-variant leading-relaxed">
                                    <strong>Alasan Penolakan:</strong> {{ $k->alasan_penolakan ?? 'Tidak ada alasan penolakan tertulis.' }}
                                </p>
                            </div>
                        @elseif($k->status === 'disetujui')
                            <div class="bg-primary-container/10 p-5 rounded-xl border border-primary/20">
                                <h5 class="font-bold text-primary text-body-sm mb-2 flex items-center gap-2">
                                    <span class="material-symbols-outlined">check_circle</span>
                                    Sesi Disetujui & Terjadwal
                                </h5>
                                <p class="text-body-sm text-on-surface-variant leading-relaxed">
                                    Silakan datang tepat waktu untuk sesi konseling Anda di:
                                    <br><strong>Lokasi / Ruangan:</strong> {{ $k->tempat ?? ($k->jadwal ? $k->jadwal->tempat : 'Ruang Bimbingan Konseling (BK)') }}
                                    <br><strong>Waktu:</strong> {{ $k->jam_konseling ? \Carbon\Carbon::parse($k->jam_konseling)->format('H:i') . ' WIB' : 'TBD' }}
                                    @if($k->link_meeting)
                                        <br><strong>Link Virtual Meeting:</strong> <a href="{{ $k->link_meeting }}" target="_blank" class="text-primary hover:underline font-bold">{{ $k->link_meeting }}</a>
                                    @endif
                                </p>
                            </div>
                        @else
                            <div class="bg-surface-container-low p-6 rounded-xl flex items-center gap-4">
                                <span class="material-symbols-outlined text-4xl text-outline-variant">hourglass_empty</span>
                                <div>
                                    <h5 class="font-bold text-on-surface text-body-sm leading-tight">Menunggu Persetujuan</h5>
                                    <p class="text-body-sm text-on-surface-variant mt-1.5 leading-relaxed">Pengajuan Anda saat ini masih dalam antrean persetujuan. Anda akan menerima notifikasi segera setelah status diperbarui oleh Guru BK.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl p-12 border border-outline-variant/30 shadow-subtle text-center">
                    <div class="w-16 h-16 bg-surface-container-low rounded-full flex items-center justify-center mx-auto mb-4 text-outline-variant">
                        <span class="material-symbols-outlined text-[32px]">history</span>
                    </div>
                    <h3 class="font-bold text-on-surface text-base mb-1">Belum Ada Riwayat Sesi</h3>
                    <p class="text-xs text-on-surface-variant max-w-xs mx-auto mb-6">Kamu belum memiliki riwayat sesi konseling yang terselesaikan.</p>
                    <a href="{{ route('siswa.pengajuan') }}" class="inline-flex items-center gap-1.5 px-6 py-3 bg-primary hover:bg-primary-container text-white font-bold rounded-xl text-sm transition shadow-md">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                        Ajukan Konseling Sekarang
                    </a>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Pagination Footer -->
    @if($konselings->hasPages())
        <footer class="mt-8 flex justify-center">
            {{ $konselings->appends(request()->query())->links() }}
        </footer>
    @endif
</div>

<!-- Ulasan / Rating Modal -->
<div id="rating-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm hidden opacity-0 transition-all duration-300">
    <div class="bg-white w-full max-w-md rounded-2xl p-6 shadow-xl border border-outline-variant/30 transform scale-95 transition-all duration-300" id="rating-modal-card">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-headline-sm text-headline-sm font-bold text-on-surface">Ulasan Sesi Bimbingan</h3>
            <button onclick="closeRatingModal()" class="text-on-surface-variant hover:text-primary transition-colors cursor-pointer focus:outline-none">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        
        <p class="text-body-sm text-on-surface-variant mb-6 leading-relaxed">Bagikan ulasanmu tentang pelayanan konseling bersama <strong id="rating-guru-name">Guru BK</strong> demi membantu meningkatkan layanan kami.</p>
        
        <form id="rating-form" method="POST" action="" class="space-y-5">
            @csrf
            
            <!-- Star Rating Selection -->
            <div class="flex flex-col items-center gap-2">
                <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Berikan Penilaian Bintang:</span>
                <div class="flex gap-2" id="star-selector">
                    @for($i=1; $i<=5; $i++)
                        <button type="button" onclick="setRatingValue({{ $i }})" class="text-gray-300 hover:text-amber-400 transition-colors duration-200 cursor-pointer focus:outline-none" data-star="{{ $i }}">
                            <span class="material-symbols-outlined text-4xl" style="font-variation-settings: 'FILL' 0;">star</span>
                        </button>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="rating-value" required>
            </div>
            
            <!-- Feedback comment -->
            <div class="space-y-1.5 text-left">
                <label for="feedback_siswa" class="font-label-md text-xs font-bold text-on-surface-variant flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">chat_bubble</span>
                    Tanggapan / Umpan Balik (Opsional)
                </label>
                <textarea name="feedback_siswa" id="feedback_siswa" rows="4" 
                          class="w-full bg-surface-container-low border border-outline-variant/60 rounded-xl px-4 py-3 text-body-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all placeholder:text-on-surface-variant/40" 
                          placeholder="Ceritakan tingkat kenyamananmu, keramahan konselor, atau pesan positif lainnya..."></textarea>
            </div>
            
            <!-- Action buttons -->
            <div class="flex gap-3 justify-end pt-2">
                <button type="button" onclick="closeRatingModal()" class="px-5 py-2.5 border border-outline-variant/60 text-on-surface-variant font-bold rounded-xl text-body-sm hover:bg-surface-container-low transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 bg-primary text-white font-bold rounded-xl text-body-sm hover:opacity-90 transition-all cursor-pointer">
                    Kirim Penilaian
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleNote(id) {
        const note = document.getElementById(id);
        const isHidden = note.classList.contains('hidden');
        
        // Close all other notes first for focus
        document.querySelectorAll('[id^="note-"]').forEach(el => {
            if(el.id !== id) el.classList.add('hidden');
        });

        if (isHidden) {
            note.classList.remove('hidden');
            note.style.opacity = '0';
            note.style.transform = 'translateY(10px)';
            setTimeout(() => {
                note.style.transition = 'all 0.4s ease-out';
                note.style.opacity = '1';
                note.style.transform = 'translateY(0)';
            }, 10);
        } else {
            note.classList.add('hidden');
        }
    }

    // Rating Modal JavaScript Logic
    function openRatingModal(konselingId, guruName) {
        const modal = document.getElementById('rating-modal');
        const modalIdCard = document.getElementById('rating-modal-card');
        const form = document.getElementById('rating-form');
        const nameSpan = document.getElementById('rating-guru-name');
        
        nameSpan.innerText = guruName;
        form.action = `/siswa/konseling/${konselingId}/rate`;
        
        // Reset form input values
        setRatingValue(0);
        document.getElementById('feedback_siswa').value = '';
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalIdCard.classList.remove('scale-95');
        }, 30);
    }
    
    function closeRatingModal() {
        const modal = document.getElementById('rating-modal');
        const modalIdCard = document.getElementById('rating-modal-card');
        
        modal.classList.add('opacity-0');
        modalIdCard.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
    
    function setRatingValue(val) {
        document.getElementById('rating-value').value = val > 0 ? val : '';
        
        const starButtons = document.querySelectorAll('#star-selector button');
        starButtons.forEach(btn => {
            const starIdx = parseInt(btn.getAttribute('data-star'));
            const starIcon = btn.querySelector('span');
            if (starIdx <= val) {
                btn.classList.add('text-amber-500');
                btn.classList.remove('text-gray-300');
                starIcon.style.fontVariationSettings = "'FILL' 1";
            } else {
                btn.classList.remove('text-amber-500');
                btn.classList.add('text-gray-300');
                starIcon.style.fontVariationSettings = "'FILL' 0";
            }
        });
    }
</script>
@endsection
