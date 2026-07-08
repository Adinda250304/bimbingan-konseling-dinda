<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Konseling;
use App\Models\JadwalKonseling;
use App\Models\HasilKonseling;
use App\Models\KalenderBk;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class GuruBkController extends Controller
{
    public function dashboard()
    {
        $guruId = auth()->id();

        // Cek apakah ada slot kalender yang is_available = true untuk hari ini
        $is_available = KalenderBk::where('guru_id', $guruId)
            ->where('is_available', true)
            ->whereDate('start', '<=', today())
            ->whereDate('end', '>=', today())
            ->exists();

        $data = [
            'sesi_hari_ini'     => Konseling::where('status', 'disetujui')
                                    ->whereDate('tanggal_konseling', today())->count(),
            'menunggu'          => Konseling::where('status', 'menunggu')->count(),
            'selesai_bulan_ini' => Konseling::where('status', 'selesai')
                                    ->whereMonth('updated_at', now()->month)
                                    ->whereYear('updated_at', now()->year)->count(),
            'recent_konselings' => Konseling::with('siswa')->where('status', 'menunggu')->latest()->take(5)->get(),
            'upcoming_konselings' => Konseling::with('siswa')
                                    ->where('status', 'disetujui')
                                    ->whereDate('tanggal_konseling', '>=', today())
                                    ->orderBy('tanggal_konseling', 'asc')
                                    ->orderBy('jam_konseling', 'asc')
                                    ->take(5)->get(),
            'is_available'      => $is_available,
        ];
        return view('guru_bk.dashboard', $data);
    }

    // ===== KALENDER GURU BK =====
    public function kalender()
    {
        $guruId = auth()->id();
        
        // Count slot terisi (scheduled, in-progress, or finished) for this month
        $total_slot_terisi = Konseling::where('guru_id', $guruId)
            ->whereIn('status', ['disetujui', 'berlangsung', 'selesai'])
            ->whereMonth('tanggal_konseling', now()->month)
            ->whereYear('tanggal_konseling', now()->year)
            ->count();

        // Count unique students seen this month
        $siswa_baru_count = Konseling::where('guru_id', $guruId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->distinct('siswa_id')
            ->count('siswa_id');

        // Closest upcoming session (disetujui or berlangsung, today or in the future)
        $sesi_terdekat = Konseling::with('siswa')
            ->where('guru_id', $guruId)
            ->whereIn('status', ['disetujui', 'berlangsung'])
            ->where(function($q) {
                $q->whereDate('tanggal_konseling', '>', today())
                  ->orWhere(function($sub) {
                      $sub->whereDate('tanggal_konseling', today())
                          ->whereTime('jam_konseling', '>=', now()->toTimeString());
                  });
            })
            ->orderBy('tanggal_konseling', 'asc')
            ->orderBy('jam_konseling', 'asc')
            ->first();

        return view('guru_bk.kalender', compact('total_slot_terisi', 'siswa_baru_count', 'sesi_terdekat'));
    }

    public function apiKalender()
    {
        $guruId = auth()->id();
        
        // 1. Availability Slots (KalenderBk)
        $availability = KalenderBk::where('guru_id', $guruId)->get()->map(function($k) {
            return [
                'id' => 'av-' . $k->id,
                'title' => $k->title,
                'start' => $k->start->format('Y-m-d\TH:i:s'),
                'end' => $k->end ? $k->end->format('Y-m-d\TH:i:s') : $k->start->copy()->addHours(1)->format('Y-m-d\TH:i:s'),
                'backgroundColor' => $k->is_available ? '#e6f4f1' : '#f1f1ef',
                'borderColor' => $k->is_available ? '#a4f0ef' : '#bec9c8',
                'textColor' => $k->is_available ? '#005050' : '#6f7979',
                'extendedProps' => [
                    'db_id' => $k->id,
                    'is_available' => $k->is_available,
                    'is_readonly' => false,
                    'type' => 'availability'
                ]
            ];
        });

        // 2. Scheduled Counseling Sessions (Konseling)
        $sessions = Konseling::with('siswa')
            ->where('guru_id', $guruId)
            ->whereIn('status', ['disetujui', 'berlangsung'])
            ->get()
            ->map(function($k) {
                if (!$k->tanggal_konseling || !$k->jam_konseling) return null;
                $start = \Carbon\Carbon::parse($k->tanggal_konseling->format('Y-m-d') . ' ' . $k->jam_konseling);
                return [
                    'id' => 'sess-' . $k->id,
                    'title' => 'Terisi: ' . ($k->siswa->name ?? 'Siswa') . ' (' . $k->jenis_masalah . ')',
                    'start' => $start->format('Y-m-d\TH:i:s'),
                    'end' => $start->copy()->addHours(1.5)->format('Y-m-d\TH:i:s'), // Default 90 min duration
                    'backgroundColor' => '#fff2ef',
                    'borderColor' => '#ffdad2',
                    'textColor' => '#865044',
                    'extendedProps' => [
                        'db_id' => $k->id,
                        'is_available' => false,
                        'is_readonly' => true,
                        'type' => 'session'
                    ]
                ];
            })->filter();

        // Merge both
        $events = $availability->concat($sessions);

        return response()->json($events->values());
    }

    public function apiKalenderStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'start' => 'required|date|after_or_equal:today',
            'end' => 'nullable|date|after_or_equal:start',
            'is_available' => 'required|boolean',
            'color' => 'nullable|string'
        ], [
            'start.after_or_equal' => 'Tidak dapat menambah slot ketersediaan di tanggal yang sudah lewat.',
        ]);

        $kalender = KalenderBk::create([
            'guru_id' => auth()->id(),
            'title' => $request->title,
            'start' => $request->start,
            'end' => $request->end,
            'is_available' => $request->is_available,
            'color' => $request->color ?? ($request->is_available ? '#22c55e' : '#ef4444'),
        ]);

        return response()->json(['success' => true, 'data' => $kalender]);
    }

    public function apiKalenderUpdate(Request $request, KalenderBk $kalender)
    {
        if ($kalender->guru_id !== auth()->id()) abort(403);

        // Blok update jika tanggal sudah lewat
        if ($kalender->start->isPast()) {
            return response()->json(['success' => false, 'message' => 'Tidak dapat mengubah slot ketersediaan di tanggal yang sudah lewat.'], 403);
        }
        
        $request->validate([
            'title' => 'sometimes|required|string',
            'start' => 'required|date|after_or_equal:today',
            'end' => 'nullable|date',
            'is_available' => 'sometimes|required|boolean',
            'color' => 'nullable|string'
        ], [
            'start.after_or_equal' => 'Tidak dapat mengubah slot ke tanggal yang sudah lewat.',
        ]);

        $kalender->update($request->only('title', 'start', 'end', 'is_available', 'color'));

        return response()->json(['success' => true, 'data' => $kalender]);
    }

    public function apiKalenderDestroy(KalenderBk $kalender)
    {
        if ($kalender->guru_id !== auth()->id()) abort(403);

        // Blok delete jika tanggal sudah lewat
        if ($kalender->start->isPast()) {
            return response()->json(['success' => false, 'message' => 'Tidak dapat menghapus slot ketersediaan di tanggal yang sudah lewat.'], 403);
        }

        $kalender->delete();
        return response()->json(['success' => true]);
    }


    // ===== PROFILE =====
    public function profile()
    {
        return view('guru_bk.profile', ['user' => auth()->user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,' . $user->id,
            'no_telp' => 'nullable|string|max:20',
            'alamat'  => 'nullable|string|max:255',
        ]);
        $user->update($request->only('name', 'email', 'no_telp', 'alamat'));
        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password'      => 'required',
            'password'              => [
                'required',
                'min:8',
                'max:20',
                'confirmed',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&^]/',
            ],
        ], [
            'password.min'   => 'Kata sandi minimal 8 karakter.',
            'password.max'   => 'Kata sandi maksimal 20 karakter.',
            'password.regex' => 'Kata sandi harus mengandung huruf besar, huruf kecil, angka, dan karakter spesial (@$!%*#?&^).',
        ]);
        if (!\Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }
        auth()->user()->update(['password' => bcrypt($request->password)]);
        return back()->with('success_pw', 'Password berhasil diperbarui.');
    }

    // ===== JADWAL (Konseling Sessions) =====
    public function jadwal(Request $request)
    {
        $search = $request->get('search', '');
        // Default status is 'hari_ini'
        $status = $request->get('status', 'hari_ini');
        if (empty($status)) {
            $status = 'hari_ini';
        }

        $query = Konseling::with(['siswa', 'guru', 'jadwal', 'hasil'])
            ->whereIn('status', ['menunggu', 'disetujui', 'berlangsung'])
            ->latest();

        if ($search) {
            $query->whereHas('siswa', fn($q) => $q->where('name', 'like', "%$search%")
                ->orWhere('kelas', 'like', "%$search%")
            );
        }

        $all_konselings = $query->get();

        // Categorize sessions
        // Today's sessions: scheduled/in-progress for today
        $hari_ini = $all_konselings->filter(fn($k) => $k->tanggal_konseling && $k->tanggal_konseling->isToday() && in_array($k->status, ['disetujui', 'berlangsung']));
        
        // Upcoming: sessions that are not today (or pending)
        $upcoming = $all_konselings->filter(fn($k) => !$k->tanggal_konseling || !$k->tanggal_konseling->isToday());

        // Count for badges (based on active sessions, filtered by search if present)
        $menunggu_count = $all_konselings->where('status', 'menunggu')->count();
        $hari_ini_count = $hari_ini->count();

        // Main collection to list in the table/view based on active tab
        if ($status === 'hari_ini') {
            // In 'hari_ini' tab, we show upcoming sessions in the table below, while today's sessions are highlighted in cards
            $konselings = $upcoming;
        } elseif ($status === 'terjadwal') {
            // In 'terjadwal' tab, we show all scheduled/active sessions (disetujui + berlangsung)
            $konselings = $all_konselings->filter(fn($k) => in_array($k->status, ['disetujui', 'berlangsung']));
        } elseif ($status === 'menunggu') {
            // In 'menunggu' tab, we show all pending requests
            $konselings = $all_konselings->filter(fn($k) => $k->status === 'menunggu');
        } else {
            // 'semua'
            $status = 'semua';
            $konselings = $all_konselings;
        }

        $siswa_list = User::role('siswa')->orderBy('name')->get();

        return view('guru_bk.jadwal', compact(
            'konselings',
            'hari_ini',
            'upcoming',
            'search',
            'status',
            'siswa_list',
            'menunggu_count',
            'hari_ini_count'
        ));
    }

    public function storeKonseling(Request $request)
    {
        $request->validate([
            'siswa_id'          => 'required|exists:users,id',
            'tanggal_konseling' => 'required|date',
            'jam_konseling'     => 'required',
            'jenis_masalah'     => 'required|string|max:255',
            'tempat'            => 'required|string|max:255',
        ]);
         Konseling::create([
            'siswa_id'          => $request->siswa_id,
            'guru_id'           => auth()->id(),
            'jenis_masalah'     => $request->jenis_masalah,
            'deskripsi_masalah' => $request->jenis_masalah,
            'status'            => 'disetujui',
            'tanggal_konseling' => $request->tanggal_konseling,
            'jam_konseling'     => $request->jam_konseling,
            'tempat'            => $request->tempat,
        ]);

        \App\Models\Notification::create([
            'user_id' => $request->siswa_id,
            'title'   => 'Jadwal Konseling Baru',
            'message' => 'Guru BK telah menjadwalkan sesi konseling untuk Anda pada ' . \Carbon\Carbon::parse($request->tanggal_konseling)->translatedFormat('d F Y') . ' pukul ' . $request->jam_konseling . '.',
        ]);

        return back()->with('success', 'Jadwal konseling berhasil ditambahkan.');
    }

    public function updateKonseling(Request $request, Konseling $konseling)
    {
        $request->validate([
            'siswa_id'          => 'required|exists:users,id',
            'tanggal_konseling' => 'required|date',
            'jam_konseling'     => 'required',
            'jenis_masalah'     => 'required|string|max:255',
            'tempat'            => 'required|string|max:255',
        ]);
        $konseling->update([
            'siswa_id'          => $request->siswa_id,
            'jenis_masalah'     => $request->jenis_masalah,
            'tanggal_konseling' => $request->tanggal_konseling,
            'jam_konseling'     => $request->jam_konseling,
            'tempat'            => $request->tempat,
        ]);
        return back()->with('success', 'Jadwal konseling berhasil diperbarui.');
    }

    /**
     * Contextual state machine: advance to the next logical status.
     * menunggu  -> (disetujui via setujuiPengajuan)
     * disetujui -> berlangsung  (Mulai Sesi)
     * berlangsung -> selesai    (via storeHasil)
     */
    public function advanceStatus(Request $request, Konseling $konseling)
    {
        $transitions = [
            'disetujui' => 'berlangsung',
        ];
        $next = $transitions[$konseling->status] ?? null;
        if (!$next) {
            return back()->with('error', 'Status tidak dapat diubah dari tahap ini.');
        }
        $konseling->update(['status' => $next]);

        \App\Models\Notification::create([
            'user_id' => $konseling->siswa_id,
            'title'   => 'Sesi Konseling Dimulai',
            'message' => 'Guru BK Anda telah memulai sesi konseling hari ini.',
        ]);

        $label = match($next) {
            'berlangsung' => 'Sesi konseling dimulai.',
            default => 'Status diperbarui.',
        };
        return back()->with('success', $label);
    }

    /**
     * Setujui pengajuan dari siswa — tentukan tanggal & jam.
     */
    public function setujuiPengajuan(Request $request, Konseling $konseling)
    {
        $request->validate([
            'tanggal_konseling' => 'required|date',
            'jam_konseling'     => 'required',
            'tempat'            => 'required|string|max:255',
        ]);
        $konseling->update([
            'status'            => 'disetujui',
            'tanggal_konseling' => $request->tanggal_konseling,
            'jam_konseling'     => $request->jam_konseling,
            'tempat'            => $request->tempat,
            'alasan_penolakan'  => null,
        ]);

        \App\Models\Notification::create([
            'user_id' => $konseling->siswa_id,
            'title'   => 'Pengajuan Konseling Disetujui',
            'message' => 'Pengajuan konseling Anda telah disetujui untuk ' . \Carbon\Carbon::parse($request->tanggal_konseling)->translatedFormat('d F Y') . ' pukul ' . $request->jam_konseling . '.',
        ]);

        return back()->with('success', 'Pengajuan disetujui dan jadwal telah ditentukan.');
    }

    /**
     * Tolak pengajuan konseling dengan alasan.
     */
    public function tolakPengajuan(Request $request, Konseling $konseling)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:500',
        ]);
        $konseling->update([
            'status'           => 'ditolak',
            'alasan_penolakan' => $request->alasan_penolakan,
        ]);

        \App\Models\Notification::create([
            'user_id' => $konseling->siswa_id,
            'title'   => 'Pengajuan Konseling Ditolak',
            'message' => 'Pengajuan konseling Anda ditolak. Alasan: ' . $request->alasan_penolakan,
        ]);

        return back()->with('success', 'Pengajuan konseling ditolak.');
    }

    public function deleteKonseling(Konseling $konseling)
    {
        $konseling->delete();
        return back()->with('success', 'Jadwal konseling berhasil dihapus.');
    }


    public function storeJadwal(Request $request)
    {
        $request->validate([
            'hari'       => 'required|string',
            'jam_mulai'  => 'required',
            'jam_selesai'=> 'required',
            'tempat'     => 'required|string|max:255',
        ]);
        JadwalKonseling::create($request->all());
        return back()->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function updateJadwal(Request $request, JadwalKonseling $jadwal)
    {
        $request->validate([
            'hari'       => 'required|string',
            'jam_mulai'  => 'required',
            'jam_selesai'=> 'required',
            'tempat'     => 'required|string|max:255',
            'is_aktif'   => 'nullable',
        ]);
        $jadwal->update([
            'hari'        => $request->hari,
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'tempat'      => $request->tempat,
            'is_aktif'    => $request->has('is_aktif'),
        ]);
        return back()->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function deleteJadwal(JadwalKonseling $jadwal)
    {
        $jadwal->delete();
        return back()->with('success', 'Jadwal berhasil dihapus.');
    }

    // ===== KONSELING / RIWAYAT =====
    public function riwayat(Request $request)
    {
        $query = Konseling::with(['siswa', 'hasil', 'tindakLanjut'])->latest();

        if ($request->filled('search')) {
            $query->whereHas('siswa', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('kelas')) {
            $query->whereHas('siswa', fn($q) => $q->where('kelas', $request->kelas));
        }
        if ($request->filled('tanggal')) {
            $date = \Carbon\Carbon::parse($request->tanggal);
            $query->whereDate('created_at', $date->toDateString());
        }
        if ($request->filled('jenis_masalah')) {
            $query->whereRaw('LOWER(jenis_masalah) = ?', [strtolower($request->jenis_masalah)]);
        }

        $konselings = $query->paginate(10);
        $kelas_list = User::role('siswa')->whereNotNull('kelas')->distinct()->pluck('kelas');

        // Statistics
        $total_selesai = Konseling::where('status', 'selesai')->count();

        // Calculate trend (completed sessions this month vs last month)
        $this_month = Konseling::where('status', 'selesai')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
        $last_month = Konseling::where('status', 'selesai')->whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year)->count();
        if ($last_month > 0) {
            $trend = round((($this_month - $last_month) / $last_month) * 100);
        } else {
            $trend = $this_month > 0 ? 100 : 0;
        }

        // Kategori masalah terbanyak
        $kategori_terbanyak = Konseling::select('jenis_masalah')
            ->groupBy('jenis_masalah')
            ->orderByRaw('COUNT(*) DESC')
            ->first();
        $kategori_terbanyak_nama = $kategori_terbanyak ? ucfirst($kategori_terbanyak->jenis_masalah) : '-';

        $total_kasus = Konseling::count();
        $kategori_terbanyak_count = $kategori_terbanyak ? Konseling::where('jenis_masalah', $kategori_terbanyak->jenis_masalah)->count() : 0;
        $kategori_terbanyak_percentage = $total_kasus > 0 ? round(($kategori_terbanyak_count / $total_kasus) * 100) : 0;

        return view('guru_bk.riwayat', compact(
            'konselings', 
            'kelas_list',
            'total_selesai',
            'trend',
            'kategori_terbanyak_nama',
            'kategori_terbanyak_percentage'
        ));
    }

    public function showKonseling(Konseling $konseling)
    {
        $konseling->load(['siswa', 'jadwal', 'hasil', 'tindakLanjut']);
        return view('guru_bk.konseling-detail', compact('konseling'));
    }

    public function updateStatusKonseling(Request $request, Konseling $konseling)
    {
        $request->validate([
            'status'              => 'required|in:disetujui,ditolak',
            'alasan_penolakan'    => 'nullable|string',
            'tanggal_konseling'   => 'nullable|date',
            'jam_konseling'       => 'nullable',
            'jadwal_id'           => 'nullable|exists:jadwal_konselings,id',
        ]);

        $konseling->update([
            'status'            => $request->status,
            'alasan_penolakan'  => $request->alasan_penolakan,
            'tanggal_konseling' => $request->tanggal_konseling,
            'jam_konseling'     => $request->jam_konseling,
            'jadwal_id'         => $request->jadwal_id,
        ]);

        return back()->with('success', 'Status konseling berhasil diperbarui.');
    }

    public function hasilKonseling(Konseling $konseling)
    {
        $konseling->load(['siswa', 'hasil']);
        return view('guru_bk.hasil-form', compact('konseling'));
    }

    public function storeHasil(Request $request, Konseling $konseling)
    {
        $rules = [
            'catatan_konselor' => 'required|string',
            'saran'            => 'required|string',
        ];

        if ($request->filled('buat_tl') && $request->buat_tl == '1') {
            $rules['tl_jenis'] = 'required|string|in:pemanggilan_ortu,mediasi,rujukan,lainnya';
            $rules['tl_catatan'] = 'required|string|min:5';
        }

        $request->validate($rules);

        $tlText = null;

        if ($request->filled('buat_tl') && $request->buat_tl == '1') {
             $tl = \App\Models\TindakLanjut::create([
                 'konseling_id' => $konseling->id,
                 'jenis'        => $request->tl_jenis,
                 'catatan'      => $request->tl_catatan,
                 'kode_unik'    => strtoupper(\Illuminate\Support\Str::random(4)) . '-' . $konseling->id . '-' . date('Ymd'),
             ]);

             // Kirim notifikasi WA & Email otomatis ke orang tua
             $tlController = new \App\Http\Controllers\TindakLanjutController();
             $tlController->kirimNotifikasiOtomatis($tl);

             $tlText = 'Diterbitkan Surat Tindak Lanjut: ' . $tl->jenis_label;
        }

        $hasilData = [
            'catatan_konselor' => $request->catatan_konselor,
            'saran'            => $request->saran,
        ];

        if ($tlText !== null) {
            $hasilData['tindak_lanjut'] = $tlText;
        }

        HasilKonseling::updateOrCreate(
            ['konseling_id' => $konseling->id],
            $hasilData
        );

        $konseling->update(['status' => 'selesai']);

        \App\Models\Notification::create([
            'user_id' => $konseling->siswa_id,
            'title'   => 'Sesi Konseling Selesai',
            'message' => 'Sesi konseling Anda telah selesai. Silakan periksa hasil dan saran dari Guru BK.',
        ]);

        return redirect()->route('guru_bk.riwayat')->with('success', 'Hasil konseling & tindak lanjut berhasil disimpan.');
    }

    public function laporan(Request $request)
    {
        $start_date = $request->get('start_date', now()->subMonths(3)->toDateString());
        $end_date = $request->get('end_date', now()->toDateString());

        $query = Konseling::with(['siswa', 'hasil'])
            ->whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $konselings = $query->latest()->get();

        // 1. Total Sesi MoM growth trend
        $start = \Carbon\Carbon::parse($start_date);
        $end = \Carbon\Carbon::parse($end_date);
        $diffInDays = $start->diffInDays($end) ?: 1;

        $prev_start_date = $start->copy()->subDays($diffInDays)->toDateString();
        $prev_end_date = $start->copy()->subDay()->toDateString();

        $current_count = $konselings->count();

        $prev_query = Konseling::whereBetween('created_at', [$prev_start_date . ' 00:00:00', $prev_end_date . ' 23:59:59']);
        if ($request->filled('status')) {
            $prev_query->where('status', $request->status);
        }
        $prev_count = $prev_query->count();

        if ($prev_count > 0) {
            $trend_percentage = round((($current_count - $prev_count) / $prev_count) * 100);
        } else {
            $trend_percentage = $current_count > 0 ? 100 : 0;
        }

        // 2. Masalah Tersering
        $kategori_counts = [
            'akademik' => 0,
            'pribadi'  => 0,
            'sosial'   => 0,
            'karir'    => 0,
            'keluarga' => 0,
        ];

        foreach ($konselings as $k) {
            $val = strtolower($k->jenis_masalah ?? '');
            if (str_contains($val, 'akademik') || str_contains($val, 'belajar') || str_contains($val, 'sekolah') || str_contains($val, 'nilai') || str_contains($val, 'tugas')) {
                $kategori_counts['akademik']++;
            } elseif (str_contains($val, 'keluarga') || str_contains($val, 'ortu') || str_contains($val, 'orang tua')) {
                $kategori_counts['keluarga']++;
            } elseif (str_contains($val, 'pribadi') || str_contains($val, 'diri') || str_contains($val, 'mental')) {
                $kategori_counts['pribadi']++;
            } elseif (str_contains($val, 'sosial') || str_contains($val, 'teman') || str_contains($val, 'bully') || str_contains($val, 'perundungan') || str_contains($val, 'mediasi')) {
                $kategori_counts['sosial']++;
            } elseif (str_contains($val, 'karir') || str_contains($val, 'kuliah') || str_contains($val, 'kerja') || str_contains($val, 'minat') || str_contains($val, 'bakat') || str_contains($val, 'lanjut')) {
                $kategori_counts['karir']++;
            } else {
                $kategori_counts['akademik']++; // Default fallback
            }
        }

        $sorted_counts = $kategori_counts;
        arsort($sorted_counts);
        $masalah_tersering = key($sorted_counts) ?: 'akademik';
        $masalah_tersering_count = current($sorted_counts);
        $total_masalah = array_sum($sorted_counts);
        $masalah_tersering_percentage = $total_masalah > 0 ? round(($masalah_tersering_count / $total_masalah) * 100) : 0;

        // 3. Kepuasan Siswa (Rating)
        $rated_konselings = $konselings->where('status', 'selesai')->whereNotNull('rating');
        $responden_count = $rated_konselings->count();
        $rating_score = $responden_count > 0 ? round($rated_konselings->avg('rating'), 1) : 5.0;

        // 4. Weekly Session Trend (4 equal intervals of selected date range)
        $weekly_counts = [0, 0, 0, 0];
        if ($current_count > 0) {
            $interval = $diffInDays / 4;
            foreach ($konselings as $k) {
                $daysFromStart = $start->diffInDays($k->created_at);
                $weekIndex = min(3, floor($daysFromStart / $interval));
                $weekly_counts[$weekIndex]++;
            }
        }
        $max_weekly = max($weekly_counts) ?: 1;
        $weekly_heights = [];
        foreach ($weekly_counts as $count) {
            $weekly_heights[] = round(($count / $max_weekly) * 100);
        }        // 5. Category breakdown counts and percentages
        $kat_akademik = $kategori_counts['akademik'];
        $kat_pribadi  = $kategori_counts['pribadi'];
        $kat_sosial   = $kategori_counts['sosial'];
        $kat_karir    = $kategori_counts['karir'];
        $kat_keluarga = $kategori_counts['keluarga'];

        $kat_total = array_sum($kategori_counts);
        $pct_akademik = $kat_total > 0 ? round(($kat_akademik / $kat_total) * 100) : 0;
        $pct_pribadi  = $kat_total > 0 ? round(($kat_pribadi / $kat_total) * 100) : 0;
        $pct_sosial   = $kat_total > 0 ? round(($kat_sosial / $kat_total) * 100) : 0;
        $pct_keluarga = $kat_total > 0 ? round(($kat_keluarga / $kat_total) * 100) : 0;
        $pct_karir    = $kat_total > 0 ? (100 - ($pct_akademik + $pct_pribadi + $pct_sosial + $pct_keluarga)) : 0;
        if ($pct_karir < 0) $pct_karir = 0;

        // 6. Historical Reports list (dynamic based on actual database records)
        $unique_months = Konseling::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        $indonesian_months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $historical_reports = [];

        foreach ($unique_months as $record) {
            $year = $record->year;
            $month = $record->month;

            $month_carbon = \Carbon\Carbon::createFromDate($year, $month, 1);
            $m_name = ($indonesian_months[$month] ?? $month_carbon->format('F')) . ' ' . $year;

            $m_start = $month_carbon->copy()->startOfMonth()->toDateString();
            $m_end = $month_carbon->copy()->endOfMonth()->toDateString();

            $month_count = Konseling::whereBetween('created_at', [$m_start . ' 00:00:00', $m_end . ' 23:59:59'])->count();

            if ($month_count > 0) {
                $historical_reports[] = [
                    'name' => 'Rekap Bulanan - ' . $m_name,
                    'start_date' => $m_start,
                    'end_date' => $m_end,
                    'count' => $month_count,
                    'file_size' => round(1.2 + ($month_count * 0.1), 1) . ' MB',
                    'type' => 'PDF',
                    'created_at' => $month_carbon->copy()->endOfMonth()->format('d M Y')
                ];
            }
        }

        // Add annual report row ONLY if there are sessions in this year
        $annual_count = Konseling::whereYear('created_at', now()->year)->count();
        if ($annual_count > 0) {
            $historical_reports[] = [
                'name' => 'Laporan Tahunan ' . now()->year,
                'start_date' => now()->startOfYear()->toDateString(),
                'end_date' => now()->toDateString(),
                'count' => $annual_count,
                'file_size' => round(2.5 + ($annual_count * 0.05), 1) . ' MB',
                'type' => 'PDF',
                'created_at' => now()->format('d M Y'),
                'tag' => 'Arsip'
            ];
        }

        // Gather all calculated stats
        $stats = [
            'total' => $current_count,
            'selesai' => $konselings->where('status', 'selesai')->count(),
            'berlangsung' => $konselings->where('status', 'disetujui')->count(),
            'ditolak' => $konselings->where('status', 'ditolak')->count(),
            'trend_percentage' => $trend_percentage,
            'masalah_tersering' => ucfirst($masalah_tersering),
            'masalah_tersering_percentage' => $masalah_tersering_percentage,
            'rating_score' => $rating_score,
            'responden_count' => $responden_count,
            'weekly_counts' => $weekly_counts,
            'weekly_heights' => $weekly_heights,
            'pct_akademik' => $pct_akademik,
            'pct_pribadi' => $pct_pribadi,
            'pct_sosial' => $pct_sosial,
            'pct_karir' => $pct_karir,
            'pct_keluarga' => $pct_keluarga,
            'kat_akademik' => $kat_akademik,
            'kat_pribadi' => $kat_pribadi,
            'kat_sosial' => $kat_sosial,
            'kat_karir' => $kat_karir,
            'kat_keluarga' => $kat_keluarga,
        ];

        return view('guru_bk.laporan', compact(
            'konselings',
            'stats',
            'start_date',
            'end_date',
            'historical_reports'
        ));
    }

    public function laporanPdf(Request $request)
    {
        $start_date = $request->get('start_date', now()->subMonths(3)->toDateString());
        $end_date = $request->get('end_date', now()->toDateString());

        $query = Konseling::with(['siswa', 'hasil'])
            ->whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $konselings = $query->latest()->get();
        
        $pdf = Pdf::loadView('guru_bk.laporan-pdf', [
            'konselings' => $konselings,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'user' => auth()->user()
        ]);

        return $pdf->download('Rekap-Konseling-' . $start_date . '-to-' . $end_date . '.pdf');
    }
}
