@extends('layouts.admin')
@section('title', 'Detail Konseling')
@section('nav-title', 'Detail Sesi Konseling')

@section('content')
@php
    $bc = match($konseling->status) {
        'selesai' => 'bg-emerald-50 text-emerald-700 border-emerald-200/50',
        'disetujui' => 'bg-sky-50 text-sky-700 border-sky-200/50',
        'berlangsung' => 'bg-purple-50 text-purple-700 border-purple-200/50',
        'ditolak' => 'bg-red-50 text-red-700 border-red-200/50',
        default => 'bg-amber-50 text-amber-700 border-amber-200/50'
    };
    
    $bl = match($konseling->status) {
        'selesai' => 'Selesai',
        'disetujui' => 'Terjadwal',
        'berlangsung' => 'Berlangsung',
        'ditolak' => 'Ditolak',
        default => 'Menunggu Persetujuan'
    };
    
    $icon = match(strtolower($konseling->jenis_masalah)) {
        'akademik' => 'school',
        'pribadi'  => 'psychology',
        'sosial'   => 'groups',
        'karir'    => 'work',
        'keluarga' => 'family_restroom',
        default    => 'event_available',
    };
    
    $categoryColor = match(strtolower($konseling->jenis_masalah)) {
        'akademik' => 'text-primary bg-primary/5 border-primary/20',
        'pribadi'  => 'text-tertiary bg-tertiary/5 border-tertiary/20',
        'sosial'   => 'text-blue-700 bg-blue-50 border-blue-200',
        'karir'    => 'text-amber-700 bg-amber-50 border-amber-200',
        'keluarga' => 'text-purple-700 bg-purple-50 border-purple-200',
        default    => 'text-outline bg-surface border-outline-variant/30',
    };
@endphp

<!-- Back Button & Page Header -->
<div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <a href="{{ route('admin.jadwal') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-outline-variant/30 text-on-surface-variant hover:text-primary hover:bg-primary-container/10 hover:border-primary rounded-xl text-sm font-semibold transition-all duration-300">
        <span class="material-symbols-outlined text-[1.125rem]">arrow_back</span>
        Kembali ke Jadwal
    </a>
</div>

@if($errors->any())
<div class="mb-6 bg-error-container/20 border border-error-container/30 text-error rounded-xl px-4 py-3 text-sm font-medium">
    <ul class="list-disc list-inside space-y-1">
        @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- Grid Layout -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
    
    <!-- Left Column: Details & Results -->
    <div class="lg:col-span-8 space-y-8">
        
        <!-- Main Details Card -->
        <div class="bg-white border border-outline-variant/30 rounded-[1.5rem] p-6 md:p-8 shadow-[0px_4px_20px_rgba(16,106,106,0.02)] space-y-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-6 border-b border-outline-variant/20">
                <div>
                    <span class="text-xs font-bold text-outline uppercase tracking-wider">ID Konseling: #{{ $konseling->id }}</span>
                    <h3 class="font-headline-sm text-headline-sm font-bold text-on-surface mt-1">Detail Sesi Konseling</h3>
                </div>
                <span class="px-4 py-1.5 rounded-full text-xs font-bold border {{ $bc }} flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                    {{ $bl }}
                </span>
            </div>
            
            <!-- Bento Box Information Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Kategori Masalah -->
                <div class="border rounded-2xl p-4 flex items-center gap-4 {{ $categoryColor }}">
                    <div class="p-2.5 bg-current/10 rounded-xl">
                        <span class="material-symbols-outlined text-2xl">{{ $icon }}</span>
                    </div>
                    <div>
                        <p class="text-[0.625rem] uppercase font-bold text-outline-variant tracking-wider leading-none mb-1.5">Kategori Masalah</p>
                        <p class="text-sm font-semibold capitalize">{{ $konseling->jenis_masalah }}</p>
                    </div>
                </div>
                
                <!-- Tipe Pertemuan -->
                <div class="border border-outline-variant/30 bg-surface-container-lowest text-on-surface rounded-2xl p-4 flex items-center gap-4">
                    <div class="p-2.5 bg-secondary-container/20 text-secondary rounded-xl">
                        <span class="material-symbols-outlined text-2xl">
                            {{ $konseling->jenis === 'online' ? 'video_call' : 'room' }}
                        </span>
                    </div>
                    <div>
                        <p class="text-[0.625rem] uppercase font-bold text-outline tracking-wider leading-none mb-1.5">Tipe Konseling</p>
                        <p class="text-sm font-semibold capitalize">{{ $konseling->jenis }}</p>
                    </div>
                </div>
                
                <!-- Tanggal -->
                <div class="border border-outline-variant/30 bg-surface-container-lowest text-on-surface rounded-2xl p-4 flex items-center gap-4">
                    <div class="p-2.5 bg-primary/10 text-primary rounded-xl">
                        <span class="material-symbols-outlined text-2xl">calendar_today</span>
                    </div>
                    <div>
                        <p class="text-[0.625rem] uppercase font-bold text-outline tracking-wider leading-none mb-1.5">Tanggal Pertemuan</p>
                        <p class="text-sm font-semibold">
                            {{ $konseling->tanggal_konseling ? $konseling->tanggal_konseling->translatedFormat('d F Y') : 'Belum dijadwalkan' }}
                        </p>
                    </div>
                </div>
                
                <!-- Jam -->
                <div class="border border-outline-variant/30 bg-surface-container-lowest text-on-surface rounded-2xl p-4 flex items-center gap-4">
                    <div class="p-2.5 bg-tertiary-container/20 text-tertiary rounded-xl">
                        <span class="material-symbols-outlined text-2xl">schedule</span>
                    </div>
                    <div>
                        <p class="text-[0.625rem] uppercase font-bold text-outline tracking-wider leading-none mb-1.5">Waktu Sesi</p>
                        <p class="text-sm font-semibold">
                            {{ $konseling->jam_konseling ? \Carbon\Carbon::parse($konseling->jam_konseling)->format('H:i') . ' WIB' : 'Belum ditentukan' }}
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Tempat / Ruangan / Link -->
            <div class="bg-surface-container-low/50 rounded-2xl p-4 border border-outline-variant/20 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary text-2xl">location_on</span>
                    <div>
                        <p class="text-[0.625rem] uppercase font-bold text-outline tracking-wider leading-none mb-1">Tempat / Ruangan</p>
                        @if($konseling->jenis === 'online')
                            <p class="text-sm font-semibold text-on-surface">Online (Virtual Meeting)</p>
                        @else
                            <p class="text-sm font-semibold text-on-surface">{{ $konseling->tempat ?? 'Ruang Bimbingan Konseling (BK)' }}</p>
                        @endif
                    </div>
                </div>
                @if($konseling->jenis === 'online' && $konseling->link_meeting)
                    <a href="{{ $konseling->link_meeting }}" target="_blank" class="px-4 py-2 bg-primary text-white rounded-xl text-xs font-bold hover:bg-primary-container transition-all flex items-center gap-1.5 shadow-sm self-start sm:self-auto">
                        <span class="material-symbols-outlined text-[1rem]">video_call</span>
                        Gabung Google Meet
                    </a>
                @endif
            </div>

            <!-- Deskripsi Masalah -->
            <div class="space-y-2">
                <label class="text-xs font-bold text-outline uppercase tracking-wider">Deskripsi Masalah dari Siswa</label>
                <div class="relative bg-surface-container-lowest border border-outline-variant/30 rounded-2xl p-6 italic text-on-surface-variant leading-relaxed shadow-inner group">
                    <span class="absolute top-4 right-6 text-primary/10 text-6xl font-serif select-none pointer-events-none">“</span>
                    <p class="text-body-md font-medium leading-relaxed">"{{ $konseling->deskripsi_masalah }}"</p>
                </div>
            </div>
        </div>

        <!-- Hasil & Saran Card -->
        @if($konseling->hasil)
            <div class="bg-white border border-outline-variant/30 rounded-[1.5rem] p-6 md:p-8 shadow-[0px_4px_20px_rgba(16,106,106,0.02)] space-y-6">
                <div class="pb-4 border-b border-outline-variant/20">
                    <h3 class="font-headline-sm text-headline-sm font-bold text-on-surface">Hasil & Saran Konselor</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant/30 space-y-2.5">
                        <h5 class="font-bold text-on-surface text-sm flex items-center gap-2 pb-2.5 border-b border-outline-variant/20">
                            <span class="material-symbols-outlined text-primary text-xl">description</span>
                            Ringkasan Hasil Sesi
                        </h5>
                        <p class="text-body-sm text-on-surface-variant italic leading-relaxed">
                            "{{ $konseling->hasil->catatan_konselor ?? 'Belum ada catatan.' }}"
                        </p>
                    </div>
                    <div class="bg-primary/5 p-5 rounded-2xl border border-primary/10 space-y-2.5">
                        <h5 class="font-bold text-primary text-sm flex items-center gap-2 pb-2.5 border-b border-primary/20">
                            <span class="material-symbols-outlined text-xl">lightbulb</span>
                            Rekomendasi & Saran
                        </h5>
                        @if($konseling->hasil->saran)
                            @php
                                $saranLines = explode("\n", str_replace("\r", "", trim($konseling->hasil->saran)));
                            @endphp
                            <ul class="text-body-sm text-on-surface-variant space-y-2 list-disc pl-4 leading-relaxed font-medium">
                                @foreach($saranLines as $line)
                                    @if(trim($line))
                                        <li>{{ ltrim(trim($line), '-*• ') }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        @else
                            <p class="text-body-sm text-on-surface-variant italic leading-relaxed">Tidak ada saran khusus.</p>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Rencana Tindak Lanjut Card -->
        @if($konseling->status === 'selesai' || $konseling->tindakLanjut->count() > 0)
            <div class="bg-white border border-outline-variant/30 rounded-[1.5rem] p-6 md:p-8 shadow-[0px_4px_20px_rgba(16,106,106,0.02)] space-y-6">
                <div class="flex justify-between items-center pb-4 border-b border-outline-variant/20">
                    <h3 class="font-headline-sm text-headline-sm font-bold text-on-surface">Rencana Tindak Lanjut</h3>
                    @if($konseling->status === 'selesai')
                        <button onclick="openModal('modal-tindak-lanjut')" class="px-4 py-2 bg-primary text-white rounded-xl text-xs font-bold hover:bg-primary-container transition-all flex items-center gap-1.5 shadow-sm cursor-pointer">
                            <span class="material-symbols-outlined text-[1rem]">add_circle</span>
                            Buat Rencana Baru
                        </button>
                    @endif
                </div>

                @if($konseling->tindakLanjut->count() > 0)
                    <div class="divide-y divide-outline-variant/20">
                        @foreach($konseling->tindakLanjut as $tl)
                            <div class="py-6 first:pt-0 last:pb-0 flex flex-col md:flex-row gap-6 md:gap-8 justify-between">
                                <div class="flex-1 space-y-3.5">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <span class="px-3 py-1 bg-primary/10 text-primary rounded-full text-xs font-bold uppercase tracking-wider">
                                            {{ $tl->jenis_label }}
                                        </span>
                                        <span class="text-xs text-outline font-medium flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm">schedule</span>
                                            {{ $tl->created_at->format('d M Y, H:i') }}
                                        </span>
                                    </div>
                                    
                                    <div class="pl-4 py-2 border-l-[0.1875rem] border-primary bg-primary/5 rounded-r-xl">
                                        <p class="text-body-sm text-on-surface-variant font-medium leading-relaxed italic">"{{ $tl->catatan }}"</p>
                                    </div>
                                    
                                    <!-- Delivery statuses -->
                                    <div class="flex flex-wrap gap-4 text-xs font-semibold">
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full flex items-center justify-center {{ $tl->status_wa === 'terkirim' ? 'bg-emerald-50 text-emerald-500' : ($tl->status_wa === 'gagal' ? 'bg-red-50 text-red-500' : 'bg-surface-container-high text-outline') }}">
                                                <span class="material-symbols-outlined text-[0.875rem]">chat</span>
                                            </div>
                                            <span class="{{ $tl->status_wa === 'terkirim' ? 'text-emerald-700' : ($tl->status_wa === 'gagal' ? 'text-red-700' : 'text-outline') }}">
                                                WhatsApp {{ $tl->status_wa === 'terkirim' ? 'Terkirim' : ($tl->status_wa === 'gagal' ? 'Gagal' : 'Belum Dikirim') }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full flex items-center justify-center {{ $tl->status_email === 'terkirim' ? 'bg-sky-50 text-sky-500' : ($tl->status_email === 'gagal' ? 'bg-red-50 text-red-500' : 'bg-surface-container-high text-outline') }}">
                                                <span class="material-symbols-outlined text-[0.875rem]">mail</span>
                                            </div>
                                            <span class="{{ $tl->status_email === 'terkirim' ? 'text-sky-700' : ($tl->status_email === 'gagal' ? 'text-red-700' : 'text-outline') }}">
                                                Email {{ $tl->status_email === 'terkirim' ? 'Terkirim' : ($tl->status_email === 'gagal' ? 'Gagal' : 'Belum Dikirim') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Document actions -->
                                <div class="flex flex-row md:flex-col justify-center gap-2 min-w-[13.125rem] shrink-0">
                                    <a href="{{ route('admin.tindak-lanjut.pdf', $tl) }}" target="_blank" class="flex-1 md:flex-initial px-4 py-2.5 bg-surface-container hover:bg-on-surface hover:text-white text-on-surface rounded-xl text-xs font-bold transition-all duration-300 flex items-center justify-center gap-2 border border-outline-variant/30">
                                        <span class="material-symbols-outlined text-[1rem]">picture_as_pdf</span>
                                        Buka PDF
                                    </a>
                                    
                                    <form action="{{ route('admin.tindak-lanjut.wa', $tl) }}" method="POST" class="flex-1 md:flex-initial">
                                        @csrf
                                        <button type="submit" class="w-full px-4 py-2.5 bg-[#25D366]/10 hover:bg-[#25D366] hover:text-white text-[#128C7E] rounded-xl text-xs font-bold transition-all duration-300 flex items-center justify-center gap-2 border border-[#25D366]/20 shadow-sm cursor-pointer font-sans">
                                            <span class="material-symbols-outlined text-[1rem] font-bold">chat</span>
                                            Kirim WA
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.tindak-lanjut.email', $tl) }}" method="POST" class="flex-1 md:flex-initial">
                                        @csrf
                                        <button type="submit" class="w-full px-4 py-2.5 bg-[#1a73e8]/10 hover:bg-[#1a73e8] hover:text-white text-[#1a73e8] rounded-xl text-xs font-bold transition-all duration-300 flex items-center justify-center gap-2 border border-[#1a73e8]/20 shadow-sm cursor-pointer font-sans">
                                            <span class="material-symbols-outlined text-[1rem]">mail</span>
                                            Kirim Email
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-10 px-4 border border-dashed border-outline-variant/40 rounded-2xl bg-surface-container-lowest">
                        <div class="w-14 h-14 bg-surface-container-low rounded-full flex items-center justify-center mb-3 text-outline">
                            <span class="material-symbols-outlined text-3xl">description</span>
                        </div>
                        <p class="text-body-sm text-outline font-semibold">Belum ada tindak lanjut tercatat pada sesi ini.</p>
                        <p class="text-[0.6875rem] text-outline-variant mt-1">Silakan gunakan tombol di atas untuk membuat dokumen tindak lanjut.</p>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Right Column: Student Profile & Quick Actions -->
    <div class="lg:col-span-4 space-y-6">
        
        <!-- Student Profile Card -->
        <div class="bg-white border border-outline-variant/30 rounded-[1.5rem] p-6 shadow-[0px_4px_20px_rgba(16,106,106,0.02)] space-y-6">
            <div class="pb-4 border-b border-outline-variant/20">
                <h4 class="font-bold text-on-surface text-base">Profil Siswa</h4>
            </div>
            
            <div class="flex flex-col items-center text-center space-y-3">
                @php
                    $words = explode(' ', $konseling->siswa->name);
                    $initials = '';
                    if (count($words) >= 2) {
                        $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
                    } else {
                        $initials = strtoupper(substr($konseling->siswa->name, 0, 2));
                    }
                @endphp
                <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-lg shadow-inner">
                    {{ $initials }}
                </div>
                <div>
                    <h5 class="font-bold text-on-surface text-base leading-tight">{{ $konseling->siswa->name }}</h5>
                    <p class="text-xs text-outline font-semibold mt-1">Kelas {{ $konseling->siswa->kelas ?? '—' }}</p>
                </div>
            </div>
            
            <div class="space-y-3 pt-2">
                <div class="flex items-center gap-3 text-body-sm text-on-surface-variant bg-surface-container-low/50 p-3 rounded-xl">
                    <span class="material-symbols-outlined text-outline text-[1.25rem]">mail</span>
                    <span class="truncate w-full" title="{{ $konseling->siswa->email }}">{{ $konseling->siswa->email }}</span>
                </div>
                <div class="flex items-center gap-3 text-body-sm text-on-surface-variant bg-surface-container-low/50 p-3 rounded-xl">
                    <span class="material-symbols-outlined text-outline text-[1.25rem]">call</span>
                    <span>{{ $konseling->siswa->no_telp ?? 'Tidak ada nomor telepon' }}</span>
                </div>
            </div>
            
            @if($konseling->siswa->no_telp)
                @php
                    $cleanPhone = preg_replace('/[^0-9]/', '', $konseling->siswa->no_telp);
                    if (str_starts_with($cleanPhone, '0')) {
                        $cleanPhone = '62' . substr($cleanPhone, 1);
                    }
                    $waUrl = "https://wa.me/" . $cleanPhone . "?text=" . urlencode("Halo {$konseling->siswa->name}, saya Guru BK terkait pengajuan bimbingan konseling Anda...");
                @endphp
                <a href="{{ $waUrl }}" target="_blank" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-[#25D366]/10 text-[#128C7E] hover:bg-[#25D366] hover:text-white border border-[#25D366]/20 rounded-2xl text-xs font-bold transition-all duration-300 shadow-sm font-sans">
                    <span class="material-symbols-outlined text-[1rem] font-bold">chat</span>
                    Hubungi via WhatsApp
                </a>
            @endif
        </div>
        
        <!-- Action Timeline / Quick Controls Card -->
        <div class="bg-white border border-outline-variant/30 rounded-[1.5rem] p-6 shadow-[0px_4px_20px_rgba(16,106,106,0.02)] space-y-6">
            <div class="pb-4 border-b border-outline-variant/20">
                <h4 class="font-bold text-on-surface text-base">Alur &amp; Tindakan Sesi</h4>
            </div>

            <!-- If status is waiting (menunggu) -->
            @if($konseling->status === 'menunggu')
                <div class="space-y-4">
                    <div class="p-4 bg-amber-50 text-amber-800 rounded-2xl border border-amber-200/50 flex gap-3">
                        <span class="material-symbols-outlined text-amber-600 shrink-0">info</span>
                        <div class="space-y-1">
                            <p class="text-xs font-bold">Menunggu Persetujuan</p>
                            <p class="text-[0.6875rem] leading-relaxed text-on-surface-variant">Silakan tentukan jadwal (tanggal, jam, dan lokasi) untuk menyetujui pengajuan ini.</p>
                        </div>
                    </div>
                    
                    <form action="{{ route('admin.konseling.setujui', $konseling) }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="space-y-3">
                            <div class="space-y-1">
                                <label class="text-[0.625rem] font-bold text-outline uppercase tracking-wider">Tanggal Pertemuan</label>
                                <input type="date" name="tanggal_konseling" required min="{{ today()->toDateString() }}"
                                       class="w-full bg-surface border border-outline-variant/40 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-primary focus:border-primary transition-all text-xs font-medium outline-none">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[0.625rem] font-bold text-outline uppercase tracking-wider">Jam Konseling</label>
                                <input type="time" name="jam_konseling" required
                                       class="w-full bg-surface border border-outline-variant/40 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-primary focus:border-primary transition-all text-xs font-medium outline-none">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[0.625rem] font-bold text-outline uppercase tracking-wider">Tempat / Ruangan</label>
                                <input type="text" name="tempat" required value="Ruang Bimbingan Konseling (BK)"
                                       class="w-full bg-surface border border-outline-variant/40 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-primary focus:border-primary transition-all text-xs font-medium outline-none">
                            </div>
                        </div>
                        
                        <div class="pt-2 flex flex-col gap-2">
                            <button type="submit" class="w-full py-3.5 bg-primary hover:bg-primary-container text-white font-bold rounded-2xl text-xs transition-all shadow-md flex items-center justify-center gap-1.5 cursor-pointer font-sans">
                                <span class="material-symbols-outlined text-[1rem] font-bold">check_circle</span>
                                Setujui &amp; Jadwalkan Sesi
                            </button>
                            <button type="button" onclick="openModal('modal-tolak')" class="w-full py-3.5 border border-error/30 hover:bg-error/5 text-error font-bold rounded-2xl text-xs transition-all flex items-center justify-center gap-1.5 cursor-pointer font-sans">
                                <span class="material-symbols-outlined text-[1rem] font-bold">cancel</span>
                                Tolak Pengajuan
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- If status is scheduled (disetujui) -->
            @if($konseling->status === 'disetujui')
                <div class="space-y-4">
                    <div class="p-4 bg-sky-50 text-sky-800 rounded-2xl border border-sky-200/50 flex gap-3">
                        <span class="material-symbols-outlined text-sky-600 shrink-0">event_available</span>
                        <div class="space-y-1">
                            <p class="text-xs font-bold">Terjadwal &amp; Disetujui</p>
                            <p class="text-[0.6875rem] leading-relaxed text-on-surface-variant">Sesi telah dijadwalkan. Silakan mulai sesi konseling jika siswa sudah hadir.</p>
                        </div>
                    </div>
                    
                    <form action="{{ route('admin.konseling.advance', $konseling) }}" method="POST" id="form-mulai-detail">
                        @csrf
                        <button type="button" onclick="confirmMulaiDetail('{{ addslashes($konseling->siswa->name) }}')"
                                class="w-full py-3.5 bg-secondary hover:opacity-95 text-white font-bold rounded-2xl text-xs transition-all shadow-md flex items-center justify-center gap-1.5 cursor-pointer font-sans">
                            <span class="material-symbols-outlined text-[1rem] font-bold">play_arrow</span>
                            Mulai Sesi Konseling
                        </button>
                    </form>
                </div>
            @endif

            <!-- If status is in progress (berlangsung) -->
            @if($konseling->status === 'berlangsung')
                <div class="space-y-4">
                    <div class="p-4 bg-purple-50 text-purple-800 rounded-2xl border border-purple-200/50 flex gap-3">
                        <span class="material-symbols-outlined text-purple-600 shrink-0">play_circle</span>
                        <div class="space-y-1">
                            <p class="text-xs font-bold">Sesi Sedang Berlangsung</p>
                            <p class="text-[0.6875rem] leading-relaxed text-on-surface-variant">Sesi konseling saat ini sedang aktif. Setelah selesai, silakan catat hasil &amp; saran.</p>
                        </div>
                    </div>
                    
                    <a href="{{ route('admin.konseling.hasil', $konseling) }}"
                       class="w-full py-3.5 bg-primary hover:bg-primary-container text-white font-bold rounded-2xl text-xs transition-all shadow-md flex items-center justify-center gap-1.5">
                        <span class="material-symbols-outlined text-[1rem] font-bold">rate_review</span>
                        Tulis Hasil &amp; Saran
                    </a>
                </div>
            @endif

            <!-- If status is finished (selesai) -->
            @if($konseling->status === 'selesai')
                <div class="space-y-4">
                    <div class="p-4 bg-emerald-50 text-emerald-800 rounded-2xl border border-emerald-200/50 flex gap-3">
                        <span class="material-symbols-outlined text-emerald-600 shrink-0">check_circle</span>
                        <div class="space-y-1">
                            <p class="text-xs font-bold">Sesi Selesai</p>
                            <p class="text-[0.6875rem] leading-relaxed text-on-surface-variant">Sesi konseling telah diselesaikan dengan sukses. Catatan hasil dan rencana tindak lanjut tersedia di panel kiri.</p>
                        </div>
                    </div>
                    
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('admin.konseling.hasil', $konseling) }}" class="w-full py-3 border border-outline-variant/40 hover:bg-surface-container-low text-on-surface-variant font-bold rounded-2xl text-xs transition-all flex items-center justify-center gap-1.5">
                            <span class="material-symbols-outlined text-[1rem] font-bold">edit</span>
                            Edit Hasil &amp; Saran
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('modals')
{{-- Modal: Tolak Pengajuan --}}
<div id="modal-tolak" class="hidden fixed inset-0 bg-black/40 backdrop-blur-md z-[100] items-center justify-center p-4">
    <div class="bg-white rounded-3xl p-8 w-full max-w-md shadow-2xl relative border border-outline-variant/30">
        <button onclick="closeModal('modal-tolak')" class="absolute top-6 right-6 text-outline hover:text-on-surface flex items-center justify-center">
            <span class="material-symbols-outlined">close</span>
        </button>
        
        <h3 class="font-headline-sm text-headline-sm text-on-surface mb-2">Tolak Pengajuan Konseling</h3>
        <p class="text-body-sm text-outline mb-6">Berikan alasan penolakan atau instruksi alternatif bagi siswa terkait pembatalan ini.</p>
        
        <form action="{{ route('admin.konseling.tolak', $konseling) }}" method="POST" class="space-y-4">
            @csrf
            <div class="space-y-1">
                <label class="text-xs font-bold text-outline uppercase tracking-wider">Alasan Penolakan</label>
                <textarea name="alasan_penolakan" rows="4" required placeholder="Tuliskan alasan penolakan..."
                          class="w-full px-4 py-3 border border-outline-variant/50 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none"></textarea>
            </div>
            
            <div class="pt-4 flex gap-3">
                <button type="button" onclick="closeModal('modal-tolak')" 
                        class="flex-1 py-3 text-center border border-outline-variant/50 rounded-xl text-sm font-bold text-outline hover:bg-surface-container-low transition-all">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 py-3 bg-error hover:bg-red-700 text-white rounded-xl text-sm font-bold transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer font-sans">
                    <span class="material-symbols-outlined text-[1.125rem]">cancel</span>
                    Tolak Sesi
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Buat Rencana Tindak Lanjut --}}
<div id="modal-tindak-lanjut" class="hidden fixed inset-0 bg-black/40 backdrop-blur-md z-[100] items-center justify-center p-4">
    <div class="bg-white rounded-3xl p-8 w-full max-w-md shadow-2xl relative border border-outline-variant/30">
        <button onclick="closeModal('modal-tindak-lanjut')" class="absolute top-6 right-6 text-outline hover:text-on-surface flex items-center justify-center">
            <span class="material-symbols-outlined">close</span>
        </button>
        
        <h3 class="font-headline-sm text-headline-sm text-on-surface mb-2">Buat Rencana Tindak Lanjut</h3>
        <p class="text-body-sm text-outline mb-6">Dokumen resmi (pemanggilan orang tua, surat mediasi, atau rujukan) akan dibuat otomatis.</p>
        
        <form action="{{ route('admin.konseling.tindak-lanjut.store', $konseling) }}" method="POST" class="space-y-4">
            @csrf
            <div class="space-y-1">
                <label class="text-xs font-bold text-outline uppercase tracking-wider">Jenis Dokumen</label>
                <select name="jenis" required class="w-full px-4 py-3 border border-outline-variant/50 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none cursor-pointer">
                    <option value="pemanggilan_ortu">Pemanggilan Orang Tua</option>
                    <option value="mediasi">Surat Mediasi</option>
                    <option value="rujukan">Rujukan Profesional</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>
            
            <div class="space-y-1">
                <label class="text-xs font-bold text-outline uppercase tracking-wider">Catatan / Keterangan</label>
                <textarea name="catatan" rows="5" required minlength="10" placeholder="Tuliskan detail surat, instruksi kehadiran, atau catatan rujukan... (min 10 karakter)"
                          class="w-full px-4 py-3 border border-outline-variant/50 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none"></textarea>
            </div>
            
            <div class="pt-4 flex gap-3">
                <button type="button" onclick="closeModal('modal-tindak-lanjut')" 
                        class="flex-1 py-3 text-center border border-outline-variant/50 rounded-xl text-sm font-bold text-outline hover:bg-surface-container-low transition-all">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 py-3 bg-primary hover:bg-primary-container text-white rounded-xl text-sm font-bold transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer font-sans">
                    <span class="material-symbols-outlined text-[1.125rem]">save</span>
                    Simpan Rencana
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            modal.style.opacity = '0';
            setTimeout(() => {
                modal.style.transition = 'opacity 0.25s ease-out';
                modal.style.opacity = '1';
            }, 10);
        }
    }

    // Direct close function
    function closeModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.style.opacity = '0';
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 250);
        }
    }

    function confirmMulaiDetail(name) {
        Swal.fire({
            title: 'Mulai Sesi Konseling?',
            text: `Apakah Anda yakin ingin memulai sesi khusus untuk ${name}? Pastikan siswa sudah siap/hadir.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#005050', // primary
            cancelButtonColor: '#6f7979', // outline
            confirmButtonText: 'Ya, Mulai Sesi!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-xl shadow-sm px-5 py-2.5 text-sm font-bold',
                cancelButton: 'rounded-xl px-4 py-2.5 text-sm font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-mulai-detail').submit();
            }
        });
    }
</script>
@endsection
