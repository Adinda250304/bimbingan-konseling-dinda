<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Konseling;
use App\Models\JadwalKonseling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WaliKelasController extends Controller
{
    public function dashboard()
    {
        $wali        = Auth::user();
        $kelas       = $wali->kelas;
        $siswa_ids   = User::where('role', 'siswa')->where('kelas', $kelas)->pluck('id');

        $total_siswa = $siswa_ids->count();
        $sesi_aktif  = Konseling::whereIn('siswa_id', $siswa_ids)->where('status', 'disetujui')->count();
        $menunggu    = Konseling::whereIn('siswa_id', $siswa_ids)->where('status', 'menunggu')->count();
        $selesai     = Konseling::whereIn('siswa_id', $siswa_ids)->where('status', 'selesai')->count();
        $riwayat     = Konseling::with('siswa')->whereIn('siswa_id', $siswa_ids)->latest()->take(5)->get();

        return view('wali.dashboard', compact('total_siswa', 'sesi_aktif', 'menunggu', 'selesai', 'riwayat', 'kelas'));
    }

    public function jadwal(Request $request)
    {
        $kelas     = Auth::user()->kelas;
        $siswa_ids = User::where('role', 'siswa')->where('kelas', $kelas)->pluck('id');

        $query = Konseling::with('siswa')
            ->whereIn('siswa_id', $siswa_ids)
            ->whereIn('status', ['menunggu', 'disetujui']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $konselings = $query->latest()->get();
        return view('wali.jadwal', compact('konselings', 'kelas'));
    }

    public function riwayat(Request $request)
    {
        $wali  = Auth::user();
        $kelas = $wali->kelas;

        $siswa_kelas = User::where('role', 'siswa')->where('kelas', $kelas)->pluck('id');
        $query = Konseling::with(['siswa', 'hasil'])->whereIn('siswa_id', $siswa_kelas);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $konselings = $query->latest()->paginate(10);
        return view('wali.riwayat', compact('konselings', 'kelas'));
    }
}
