<?php

namespace App\Http\Controllers;

use App\Models\Konseling;
use App\Models\JadwalKonseling;
use App\Models\KalenderBk;
use App\Models\User;
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
        $riwayat    = $user->konselings()->with('jadwal')->latest()->take(5)->get();
        return view('siswa.dashboard', compact('sesi_aktif', 'menunggu', 'selesai', 'total', 'riwayat'));
    }

    public function pengajuan()
    {
        // Get all Guru BK users
        $gurubk = User::role('admin')->get(['id', 'name']);
        return view('siswa.pengajuan', compact('gurubk'));
    }

    public function apiSlotGuru(Request $request)
    {
        $guru_id = $request->guru_id;
        if (!$guru_id) return response()->json([]);

        $slots = KalenderBk::where('guru_id', $guru_id)
            ->where('is_available', true)
            ->where('start', '>=', now())
            ->orderBy('start')
            ->get()
            ->map(function ($k) {
                return [
                    'id'    => $k->id,
                    'label' => $k->start->format('l, d M Y') . ' | ' . $k->start->format('H:i') . ' – ' . ($k->end ? $k->end->format('H:i') : $k->start->copy()->addHours(1)->format('H:i')),
                    'tanggal' => $k->start->format('Y-m-d'),
                    'jam'     => $k->start->format('H:i'),
                ];
            });

        return response()->json($slots);
    }

    public function storePengajuan(Request $request)
    {
        $request->validate([
            'jenis_masalah'     => 'required|string|max:100',
            'deskripsi_masalah' => 'required|string|min:20',
            'guru_id'           => 'required|exists:users,id',
            'tanggal_konseling' => 'nullable|date',
            'jam_konseling'     => 'nullable',
        ], [
            'deskripsi_masalah.min' => 'Deskripsi masalah minimal 20 karakter.',
            'guru_id.required'      => 'Silakan pilih Guru BK terlebih dahulu.',
        ]);

        Konseling::create([
            'siswa_id'          => Auth::id(),
            'guru_id'           => $request->guru_id ?: null,
            'jenis_masalah'     => $request->jenis_masalah,
            'deskripsi_masalah' => $request->deskripsi_masalah,
            'jenis'             => 'offline',
            'tanggal_konseling' => $request->tanggal_konseling,
            'jam_konseling'     => $request->jam_konseling,
            'status'            => 'menunggu',
        ]);

        return redirect()->route('siswa.riwayat')->with('success', 'Pengajuan konseling berhasil dikirim!');
    }

    public function jadwal()
    {
        $konselings  = Auth::user()->konselings()
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->latest()
            ->get();
        return view('siswa.jadwal', compact('konselings'));
    }

    public function riwayat()
    {
        $konselings = Auth::user()->konselings()->with(['jadwal', 'hasil'])->latest()->paginate(8);
        return view('siswa.riwayat', compact('konselings'));
    }

    public function kalender()
    {
        return view('siswa.kalender');
    }

    public function apiKalender()
    {
        $events = KalenderBk::with('guru')->get()->map(function($k) {
            $guruName = $k->guru ? $k->guru->name : 'Guru BK';
            $title = $k->is_available ? "[$guruName] Tersedia" : "[$guruName] " . $k->title;
            return [
                'id' => $k->id,
                'title' => $title,
                'start' => $k->start->format('Y-m-d\TH:i:s'),
                'end' => $k->end ? $k->end->format('Y-m-d\TH:i:s') : $k->start->copy()->addHours(1)->format('Y-m-d\TH:i:s'),
                'backgroundColor' => $k->color ?? ($k->is_available ? '#22c55e' : '#ef4444'),
                'borderColor' => $k->color ?? ($k->is_available ? '#22c55e' : '#ef4444'),
                'extendedProps' => [
                    'guru' => $guruName,
                    'is_available' => $k->is_available,
                    'is_readonly' => true
                ]
            ];
        });
        return response()->json($events);
    }
}
