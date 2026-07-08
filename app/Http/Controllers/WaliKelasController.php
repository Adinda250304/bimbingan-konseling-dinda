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
        $siswa_ids   = User::role('siswa')->where('kelas', $kelas)->pluck('id');

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
        $siswa_ids = User::role('siswa')->where('kelas', $kelas)->pluck('id');

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

        $siswa_kelas = User::role('siswa')->where('kelas', $kelas)->pluck('id');
        $query = Konseling::with(['siswa', 'hasil'])->whereIn('siswa_id', $siswa_kelas);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $konselings = $query->latest()->paginate(10);
        return view('wali.riwayat', compact('konselings', 'kelas'));
    }

    public function siswa()
    {
        $wali  = Auth::user();
        $kelas = $wali->kelas;
        
        $siswas = User::role('siswa')->where('kelas', $kelas)->orderBy('name')->get();
        return view('wali.siswa', compact('siswas', 'kelas'));
    }

    public function rujuk($siswa_id)
    {
        $wali = Auth::user();
        $siswa = User::role('siswa')->where('kelas', $wali->kelas)->findOrFail($siswa_id);
        
        return view('wali.rujuk', compact('siswa'));
    }

    public function storeRujukan(Request $request, $siswa_id)
    {
        $wali = Auth::user();
        $siswa = User::role('siswa')->where('kelas', $wali->kelas)->findOrFail($siswa_id);

        $request->validate([
            'jenis_masalah' => 'required|string',
            'alasan_rujukan' => 'required|string',
        ]);

        Konseling::create([
            'siswa_id' => $siswa->id,
            'rujukan_oleh_id' => $wali->id,
            'jenis_masalah' => $request->jenis_masalah,
            'deskripsi_masalah' => 'Rujukan dari Wali Kelas: ' . $wali->name,
            'alasan_rujukan' => $request->alasan_rujukan,
            'jenis' => 'offline',
            'status' => 'menunggu',
        ]);

        $recipientNumber = $siswa->no_telp_ortu ?: $siswa->no_telp;
        $recipientName = $siswa->nama_ortu ?: 'Orang Tua/Wali';

        $pesanWa = "📋 *RUJUKAN BIMBINGAN KONSELING (BK)*\n"
                 . "SMK YPML — Bimbingan Konseling\n\n"
                 . "Yth. {$recipientName} dari *{$siswa->name}*\n\n"
                 . "Kami menginformasikan bahwa Wali Kelas telah merujuk putra/putri Anda ke Bimbingan Konseling (BK) untuk pendampingan lebih lanjut:\n"
                 . "📌 *Bidang Masalah:* {$request->jenis_masalah}\n"
                 . "📝 *Keterangan/Alasan:* {$request->alasan_rujukan}\n\n"
                 . "Wali Kelas: {$wali->name}\n\n"
                 . "_Pesan ini dikirim oleh sistem Teman BK_";

        $urlWa = null;
        if ($recipientNumber) {
            $nomor = preg_replace('/[^0-9]/', '', $recipientNumber);
            if (str_starts_with($nomor, '0')) {
                $nomor = '62' . substr($nomor, 1);
            }
            
            // Try sending automatically via Fonnte
            $this->sendFonnte($nomor, $pesanWa);
            
            // Prefilled manual WhatsApp URL
            $urlWa = "https://wa.me/" . $nomor . "?text=" . urlencode($pesanWa);
        }

        return redirect()->route('wali.siswa')
            ->with('success', 'Berhasil membuat rujukan konseling untuk ' . $siswa->name)
            ->with('wa_url', $urlWa)
            ->with('siswa_name', $siswa->name);
    }

    private function sendFonnte(string $nomor, string $pesan): ?array
    {
        $token = env('FONNTE_TOKEN');
        if (!$token) return null;

        $ch = curl_init('https://api.fonnte.com/send');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => [
                'target'  => $nomor,
                'message' => $pesan,
            ],
            CURLOPT_HTTPHEADER => ['Authorization: ' . $token],
        ]);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result ? json_decode($result, true) : null;
    }
}
