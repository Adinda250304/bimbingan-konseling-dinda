<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Konseling;
use App\Models\JadwalKonseling;
use App\Models\HasilKonseling;
use App\Models\KalenderBk;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $data = [
            'total_siswa'       => User::role('siswa')->count(),
            'sesi_hari_ini'     => Konseling::where('status', 'disetujui')
                                    ->whereDate('tanggal_konseling', today())->count(),
            'menunggu'          => Konseling::where('status', 'menunggu')->count(),
            'selesai_bulan_ini' => Konseling::where('status', 'selesai')
                                    ->whereMonth('updated_at', now()->month)
                                    ->whereYear('updated_at', now()->year)->count(),
            'recent_konselings' => Konseling::with('siswa')->latest()->take(5)->get(),
        ];
        return view('admin.dashboard', $data);
    }

    // ===== KALENDER GURU BK =====
    public function kalender()
    {
        return view('admin.kalender');
    }

    public function apiKalender()
    {
        $events = KalenderBk::where('guru_id', auth()->id())->get()->map(function($k) {
            return [
                'id' => $k->id,
                'title' => $k->title,
                'start' => $k->start->format('Y-m-d\TH:i:s'),
                'end' => $k->end ? $k->end->format('Y-m-d\TH:i:s') : $k->start->copy()->addHours(1)->format('Y-m-d\TH:i:s'),
                'backgroundColor' => $k->color ?? ($k->is_available ? '#22c55e' : '#ef4444'),
                'borderColor' => $k->color ?? ($k->is_available ? '#22c55e' : '#ef4444'),
                'extendedProps' => [
                    'is_available' => $k->is_available,
                    'is_readonly' => false
                ]
            ];
        });
        return response()->json($events);
    }

    public function apiKalenderStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'start' => 'required|date',
            'end' => 'nullable|date|after_or_equal:start',
            'is_available' => 'required|boolean',
            'color' => 'nullable|string'
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
        
        $request->validate([
            'title' => 'sometimes|required|string',
            'start' => 'required|date',
            'end' => 'nullable|date',
            'is_available' => 'sometimes|required|boolean',
            'color' => 'nullable|string'
        ]);

        $kalender->update($request->only('title', 'start', 'end', 'is_available', 'color'));

        return response()->json(['success' => true, 'data' => $kalender]);
    }

    public function apiKalenderDestroy(KalenderBk $kalender)
    {
        if ($kalender->guru_id !== auth()->id()) abort(403);
        $kalender->delete();
        return response()->json(['success' => true]);
    }


    // ===== PROFILE =====
    public function profile()
    {
        return view('admin.profile', ['user' => auth()->user()]);
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
            'password'              => 'required|min:6|confirmed',
        ]);
        if (!\Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }
        auth()->user()->update(['password' => bcrypt($request->password)]);
        return back()->with('success_pw', 'Password berhasil diperbarui.');
    }

    // ===== USERS =====
    public function users(Request $request)
    {
        $role  = $request->get('role', 'semua');
        $search = $request->get('search', '');
        $query = User::query();
        if ($role !== 'semua') $query->role($role);
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('username', 'like', "%$search%");
            });
        }
        $users = $query->latest()->paginate(10);
        return view('admin.users', compact('users', 'role', 'search'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'username'      => 'required|string|max:100|unique:users',
            'email'         => 'required|email|unique:users',
            'password'      => 'required|min:6',
            'role'          => 'required|in:admin,siswa',
            'tingkat_kelas' => 'nullable|in:X,XI,XII',
            'jurusan'       => 'nullable|string|max:100',
            'no_telp'       => 'nullable|string|max:20',
        ]);

        // Gabungkan tingkat + jurusan menjadi nilai kelas
        $kelas = null;
        if ($request->tingkat_kelas) {
            $kelas = trim($request->tingkat_kelas . ' ' . $request->jurusan);
        }

        $user = User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'kelas'    => $kelas,
            'no_telp'  => $request->no_telp,
        ]);
        $user->assignRole($request->role);

        return back()->with('success', 'User berhasil ditambahkan.');
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'username'      => 'required|string|max:100|unique:users,username,' . $user->id,
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'role'          => 'required|in:admin,siswa',
            'tingkat_kelas' => 'nullable|in:X,XI,XII',
            'jurusan'       => 'nullable|string|max:100',
            'no_telp'       => 'nullable|string|max:20',
        ]);

        $kelas = null;
        if ($request->tingkat_kelas) {
            $kelas = trim($request->tingkat_kelas . ' ' . $request->jurusan);
        }

        $user->update([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'kelas'    => $kelas,
            'no_telp'  => $request->no_telp,
        ]);
        $user->syncRoles($request->role);
        if ($request->filled('password')) {
            $user->update(['password' => bcrypt($request->password)]);
        }

        return back()->with('success', 'User berhasil diperbarui.');
    }

    public function deleteUser(User $user)
    {
        if ($user->hasRole('admin') && User::role('admin')->count() <= 1) {
            return back()->with('error', 'Tidak bisa menghapus admin terakhir.');
        }
        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }

    // ===== JADWAL (Konseling Sessions) =====
    public function jadwal(Request $request)
    {
        $search = $request->get('search', '');
        $status = $request->get('status', '');

        $query = Konseling::with(['siswa', 'guru', 'jadwal', 'hasil'])
            ->whereIn('status', ['menunggu', 'disetujui', 'berlangsung'])
            ->latest();

        if ($search) {
            $query->whereHas('siswa', fn($q) => $q->where('name', 'like', "%$search%")
                ->orWhere('kelas', 'like', "%$search%")
            );
        }

        if ($status) {
            $map = [
                'selesai'     => 'selesai',
                'berlangsung' => 'berlangsung',
                'terjadwal'   => 'disetujui',
                'menunggu'    => 'menunggu',
            ];
            $dbStatus = $map[$status] ?? $status;
            $query->where('status', $dbStatus);
        }

        $konselings = $query->get();
        $hari_ini   = $konselings->filter(fn($k) => $k->tanggal_konseling && $k->tanggal_konseling->isToday());
        $upcoming   = $konselings->filter(fn($k) => !($k->tanggal_konseling && $k->tanggal_konseling->isToday()));
        $siswa_list = User::role('siswa')->orderBy('name')->get();

        return view('admin.jadwal', compact('konselings', 'hari_ini', 'upcoming', 'search', 'status', 'siswa_list'));
    }

    public function storeKonseling(Request $request)
    {
        $request->validate([
            'siswa_id'          => 'required|exists:users,id',
            'tanggal_konseling' => 'required|date',
            'jam_konseling'     => 'required',
            'jenis_masalah'     => 'required|string|max:255',
            'tempat'            => 'nullable|string|max:255',
        ]);
        Konseling::create([
            'siswa_id'          => $request->siswa_id,
            'jenis_masalah'     => $request->jenis_masalah,
            'deskripsi_masalah' => $request->jenis_masalah,
            'jenis'             => 'offline',
            'status'            => 'disetujui',
            'tanggal_konseling' => $request->tanggal_konseling,
            'jam_konseling'     => $request->jam_konseling,
            'link_meeting'      => null,
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
            'tempat'            => 'nullable|string|max:255',
        ]);
        $konseling->update([
            'siswa_id'          => $request->siswa_id,
            'jenis_masalah'     => $request->jenis_masalah,
            'tanggal_konseling' => $request->tanggal_konseling,
            'jam_konseling'     => $request->jam_konseling,
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
            'tempat'            => 'nullable|string|max:255',
        ]);
        $konseling->update([
            'status'            => 'disetujui',
            'tanggal_konseling' => $request->tanggal_konseling,
            'jam_konseling'     => $request->jam_konseling,
            'alasan_penolakan'  => null,
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
            'tempat'     => 'nullable|string|max:255',
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
            'tempat'     => 'nullable|string|max:255',
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
        $query = Konseling::with(['siswa', 'hasil'])->latest();

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

        $konselings = $query->paginate(10);
        $kelas_list = User::role('siswa')->whereNotNull('kelas')->distinct()->pluck('kelas');

        return view('admin.riwayat', compact('konselings', 'kelas_list'));
    }

    public function showKonseling(Konseling $konseling)
    {
        $konseling->load(['siswa', 'jadwal', 'hasil', 'tindakLanjut']);
        return view('admin.konseling-detail', compact('konseling'));
    }

    public function updateStatusKonseling(Request $request, Konseling $konseling)
    {
        $request->validate([
            'status'              => 'required|in:disetujui,ditolak',
            'alasan_penolakan'    => 'nullable|string',
            'tanggal_konseling'   => 'nullable|date',
            'jam_konseling'       => 'nullable',
            'link_meeting'        => 'nullable|url',
            'jadwal_id'           => 'nullable|exists:jadwal_konselings,id',
        ]);

        $konseling->update([
            'status'            => $request->status,
            'alasan_penolakan'  => $request->alasan_penolakan,
            'tanggal_konseling' => $request->tanggal_konseling,
            'jam_konseling'     => $request->jam_konseling,
            'link_meeting'      => $request->link_meeting,
            'jadwal_id'         => $request->jadwal_id,
        ]);

        return back()->with('success', 'Status konseling berhasil diperbarui.');
    }

    public function hasilKonseling(Konseling $konseling)
    {
        $konseling->load(['siswa', 'hasil']);
        return view('admin.hasil-form', compact('konseling'));
    }

    public function storeHasil(Request $request, Konseling $konseling)
    {
        $request->validate([
            'catatan_konselor' => 'required|string',
            'saran'            => 'required|string',
            'buat_tl'          => 'nullable',
            'tl_jenis'         => 'nullable|string|in:pemanggilan_ortu,mediasi,rujukan,lainnya',
            'tl_catatan'       => 'nullable|string|min:5',
        ]);

        $tlText = null;

        if ($request->has('buat_tl')) {
             $tl = \App\Models\TindakLanjut::create([
                 'konseling_id' => $konseling->id,
                 'jenis'        => $request->tl_jenis,
                 'catatan'      => $request->tl_catatan,
                 'kode_unik'    => strtoupper(\Illuminate\Support\Str::random(4)) . '-' . $konseling->id . '-' . date('Ymd'),
             ]);

             $tlText = 'Diterbitkan Surat Tindak Lanjut: ' . $tl->jenis_label;
        }

        HasilKonseling::updateOrCreate(
            ['konseling_id' => $konseling->id],
            [
                'catatan_konselor' => $request->catatan_konselor,
                'saran'            => $request->saran,
                'tindak_lanjut'    => $tlText,
            ]
        );

        $konseling->update(['status' => 'selesai']);
        return redirect()->route('admin.riwayat')->with('success', 'Hasil konseling & tindak lanjut berhasil disimpan.');
    }
}
