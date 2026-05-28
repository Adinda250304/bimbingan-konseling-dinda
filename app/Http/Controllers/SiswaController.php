<?php

namespace App\Http\Controllers;

use App\Models\Konseling;
use App\Models\JadwalKonseling;
use App\Models\KalenderBk;
use App\Models\User;
use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $sesi_aktif = $user->konselings()->where('status', 'disetujui')->count();
        $menunggu   = $user->konselings()->where('status', 'menunggu')->count();
        $selesai    = $user->konselings()->where('status', 'selesai')->count();
        $total      = $user->konselings()->count();
        $riwayat    = $user->konselings()->with(['jadwal', 'hasil', 'guru'])->latest()->take(5)->get();
        
        // Sesi konseling terdekat yang disetujui
        $sesi_terdekat = $user->konselings()
            ->with('guru')
            ->where('status', 'disetujui')
            ->whereDate('tanggal_konseling', '>=', today())
            ->orderBy('tanggal_konseling', 'asc')
            ->orderBy('jam_konseling', 'asc')
            ->first();

        // Info ketersediaan guru BK pertama
        $guru_bk = User::role('admin')->first();
        $is_available = false;
        if ($guru_bk) {
            $is_available = KalenderBk::where('guru_id', $guru_bk->id)
                ->where('is_available', true)
                ->where('start', '>=', now())
                ->exists();
        }

        $artikels = Artikel::where('is_published', true)->latest()->take(2)->get();

        return view('siswa.dashboard', compact(
            'sesi_aktif', 'menunggu', 'selesai', 'total', 'riwayat', 'sesi_terdekat', 'guru_bk', 'is_available', 'artikels'
        ));
    }

    public function pengajuan()
    {
        // Get all Guru BK users with their real session counts and average ratings
        $gurubk = User::role('admin')
            ->withCount(['konselings as completed_sessions_count' => function ($query) {
                $query->where('status', 'selesai');
            }])
            ->withAvg(['konselings as average_rating' => function ($query) {
                $query->where('status', 'selesai');
            }], 'rating')
            ->get(['id', 'name']);

        return view('siswa.pengajuan', compact('gurubk'));
    }

    public function apiSlotGuru(Request $request)
    {
        $guru_id = $request->guru_id;
        if (!$guru_id) return response()->json([]);

        // Get booked sessions for this guru
        $bookedSessions = Konseling::where('guru_id', $guru_id)
            ->whereIn('status', ['disetujui', 'berlangsung'])
            ->whereNotNull('tanggal_konseling')
            ->whereNotNull('jam_konseling')
            ->get();

        $bookedKeys = $bookedSessions->map(function ($sess) {
            $date = $sess->tanggal_konseling->format('Y-m-d');
            $time = \Carbon\Carbon::parse($sess->jam_konseling)->format('H:i');
            return "{$date}_{$time}";
        })->toArray();

        $slots = KalenderBk::where('guru_id', $guru_id)
            ->where('is_available', true)
            ->where('start', '>=', now())
            ->orderBy('start')
            ->get()
            ->map(function ($k) use ($bookedKeys) {
                $slotDate = $k->start->format('Y-m-d');
                $slotTime = $k->start->format('H:i');
                $key = "{$slotDate}_{$slotTime}";

                if (in_array($key, $bookedKeys)) {
                    return null;
                }

                return [
                    'id'    => $k->id,
                    'label' => $k->start->format('l, d M Y') . ' | ' . $k->start->format('H:i') . ' – ' . ($k->end ? $k->end->format('H:i') : $k->start->copy()->addHours(1)->format('H:i')),
                    'tanggal' => $k->start->format('Y-m-d'),
                    'jam'     => $k->start->format('H:i'),
                ];
            })->filter()->values();

        return response()->json($slots);
    }

    public function storePengajuan(Request $request)
    {
        $request->validate([
            'jenis_masalah'     => 'required|string|in:Akademik,Pribadi,Sosial,Karir,Keluarga',
            'deskripsi_masalah' => 'required|string|min:20',
            'guru_id'           => 'required|exists:users,id',
            'tanggal_konseling' => 'nullable|date',
            'jam_konseling'     => 'nullable',
        ], [
            'jenis_masalah.in'      => 'Kategori masalah harus salah satu dari: Akademik, Pribadi, Sosial, Karir, Keluarga.',
            'deskripsi_masalah.min' => 'Deskripsi masalah minimal 20 karakter.',
            'guru_id.required'      => 'Silakan pilih Guru BK terlebih dahulu.',
        ]);

        $konseling = Konseling::create([
            'siswa_id'          => Auth::id(),
            'guru_id'           => $request->guru_id ?: null,
            'jenis_masalah'     => $request->jenis_masalah,
            'deskripsi_masalah' => $request->deskripsi_masalah,
            'jenis'             => 'offline',
            'tanggal_konseling' => $request->tanggal_konseling,
            'jam_konseling'     => $request->jam_konseling,
            'status'            => 'menunggu',
        ]);

        if ($request->guru_id) {
            \App\Models\Notification::create([
                'user_id' => $request->guru_id,
                'title'   => 'Pengajuan Konseling Baru',
                'message' => 'Siswa ' . Auth::user()->name . ' telah mengirim pengajuan konseling baru (' . $request->jenis_masalah . ').',
            ]);
        }

        return redirect()->route('siswa.riwayat')->with('success', 'Pengajuan konseling berhasil dikirim!');
    }

    public function jadwal()
    {
        $konselings  = Auth::user()->konselings()
            ->with('guru')
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->latest()
            ->get();
            
        $guru_bk = User::role('admin')->first();
        
        return view('siswa.jadwal', compact('konselings', 'guru_bk'));
    }

    public function riwayat()
    {
        $konselings = Auth::user()->konselings()->with(['jadwal', 'hasil'])->latest()->paginate(8);
        return view('siswa.riwayat', compact('konselings'));
    }

    public function kalender()
    {
        $gurubk = User::role('admin')->get(['id', 'name']);
        return view('siswa.kalender', compact('gurubk'));
    }

    public function apiKalender()
    {
        // Fetch all booked sessions
        $bookedSessions = Konseling::whereIn('status', ['disetujui', 'berlangsung'])
            ->whereNotNull('tanggal_konseling')
            ->whereNotNull('jam_konseling')
            ->get();

        $bookedKeys = $bookedSessions->map(function ($sess) {
            $date = $sess->tanggal_konseling->format('Y-m-d');
            $time = \Carbon\Carbon::parse($sess->jam_konseling)->format('H:i');
            return "{$sess->guru_id}_{$date}_{$time}";
        })->toArray();

        $events = KalenderBk::with('guru')->get()->map(function($k) use ($bookedKeys) {
            $guruName = $k->guru ? $k->guru->name : 'Guru BK';
            
            $slotDate = $k->start->format('Y-m-d');
            $slotTime = $k->start->format('H:i');
            $key = "{$k->guru_id}_{$slotDate}_{$slotTime}";

            $isAvailable = $k->is_available;
            if (in_array($key, $bookedKeys)) {
                $isAvailable = false;
            }

            // If it is booked, change title to "Terisi" instead of "Tersedia"
            // (Note: in the student calendar JS, !is_available and not containing 'libur' makes it hasBooked)
            $title = $isAvailable ? "[$guruName] Tersedia" : ($k->is_available ? "[$guruName] Terisi" : "[$guruName] " . $k->title);

            // Set color: Booked/Terisi gets gray color #bec9c8
            $color = $isAvailable ? ($k->color ?? '#22c55e') : ($k->is_available ? '#bec9c8' : ($k->color ?? '#ef4444'));

            return [
                'id' => $k->id,
                'title' => $title,
                'start' => $k->start->format('Y-m-d\TH:i:s'),
                'end' => $k->end ? $k->end->format('Y-m-d\TH:i:s') : $k->start->copy()->addHours(1)->format('Y-m-d\TH:i:s'),
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => [
                    'guru' => $guruName,
                    'guru_id' => $k->guru_id,
                    'is_available' => $isAvailable,
                    'is_readonly' => true
                ]
            ];
        });
        return response()->json($events);
    }

    public function storeFeedback(Request $request, Konseling $konseling)
    {
        if ($konseling->siswa_id !== Auth::id()) {
            abort(403, 'Tindakan tidak sah.');
        }

        if ($konseling->status !== 'selesai') {
            return back()->with('error', 'Anda hanya dapat memberikan ulasan untuk konseling yang sudah selesai.');
        }

        $request->validate([
            'rating'         => 'required|integer|min:1|max:5',
            'feedback_siswa' => 'nullable|string|max:1000',
        ], [
            'rating.required' => 'Penilaian bintang wajib diisi.',
            'rating.min'      => 'Penilaian bintang minimal 1.',
            'rating.max'      => 'Penilaian bintang maksimal 5.',
        ]);

        $konseling->update([
            'rating'         => $request->rating,
            'feedback_siswa' => $request->feedback_siswa,
        ]);

        return back()->with('success', 'Terima kasih atas ulasan dan umpan balik Anda!');
    }

    public function artikel()
    {
        $artikels = Artikel::where('is_published', true)->latest()->paginate(9);
        return view('siswa.artikel', compact('artikels'));
    }

    public function artikelDetail($id)
    {
        $artikel = Artikel::where('is_published', true)->findOrFail($id);
        
        $rekomendasi = Artikel::where('is_published', true)
            ->where('id', '!=', $id)
            ->latest()
            ->take(3)
            ->get();

        return view('siswa.artikel-detail', compact('artikel', 'rekomendasi'));
    }
}
