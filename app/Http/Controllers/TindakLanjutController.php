<?php

namespace App\Http\Controllers;

use App\Models\Konseling;
use App\Models\TindakLanjut;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TindakLanjutController extends Controller
{
    // ───────────────────────────────────────────
    // SIMPAN TINDAK LANJUT
    // ───────────────────────────────────────────
    public function store(Request $request, Konseling $konseling)
    {
        $request->validate([
            'jenis'   => 'required|in:pemanggilan_ortu,mediasi,rujukan,lainnya',
            'catatan' => 'required|string|min:10',
        ]);

        $tl = TindakLanjut::create([
            'konseling_id' => $konseling->id,
            'jenis'        => $request->jenis,
            'catatan'      => $request->catatan,
            'kode_unik'    => strtoupper(Str::random(4)) . '-' . $konseling->id . '-' . date('Ymd'),
        ]);

        return back()->with('success', 'Tindak lanjut berhasil disimpan. Silakan unduh atau kirim surat.');
    }

    // ───────────────────────────────────────────
    // UNDUH PDF
    // ───────────────────────────────────────────
    public function pdf(TindakLanjut $tindakLanjut)
    {
        $konseling = $tindakLanjut->konseling->load(['siswa', 'guru']);

        // Generate QR Code sebagai SVG inline
        $verifikasiUrl = url('/verifikasi-surat/' . $tindakLanjut->kode_unik);
        $qrCode = QrCode::format('svg')
            ->size(90)
            ->errorCorrection('M')
            ->generate($verifikasiUrl);

        $pdf = Pdf::loadView('pdf.tindak-lanjut', [
            'tl'         => $tindakLanjut,
            'konseling'  => $konseling,
            'qrCode'     => $qrCode,
        ])->setPaper('a4');

        $filename = 'TindakLanjut_' . str_replace(' ', '_', $konseling->siswa->name) . '_' . $tindakLanjut->kode_unik . '.pdf';

        return $pdf->download($filename);
    }

    // ───────────────────────────────────────────
    // KIRIM WHATSAPP via FONNTE
    // ───────────────────────────────────────────
    public function kirimWa(TindakLanjut $tindakLanjut)
    {
        $konseling = $tindakLanjut->konseling->load(['siswa', 'guru']);
        $siswa     = $konseling->siswa;

        if (!$siswa->no_telp) {
            return back()->with('error', 'Nomor WhatsApp siswa/orang tua tidak tersedia.');
        }

        $pesan = "📋 *PEMBERITAHUAN TINDAK LANJUT KONSELING*\n";
        $pesan .= "SMK YPML — Bimbingan Konseling\n\n";
        $pesan .= "Yth. Orang Tua/Wali dari *{$siswa->name}*\n\n";
        $pesan .= "Kami menginformasikan bahwa telah dilakukan tindak lanjut konseling:\n";
        $pesan .= "📌 *Jenis:* {$tindakLanjut->jenis_label}\n";
        $pesan .= "📅 *Tanggal:* " . now()->format('d M Y') . "\n";
        $pesan .= "📝 *Catatan:* {$tindakLanjut->catatan}\n\n";
        $pesan .= "Kode Surat: *{$tindakLanjut->kode_unik}*\n";
        $pesan .= "Guru BK: {$konseling->guru?->name}\n\n";
        $pesan .= "_Pesan ini dikirim otomatis oleh sistem Teman BK_";

        $nomor = preg_replace('/[^0-9]/', '', $siswa->no_telp);
        if (str_starts_with($nomor, '0')) {
            $nomor = '62' . substr($nomor, 1);
        }

        $response = $this->sendFonnte($nomor, $pesan);

        if ($response && isset($response['status']) && $response['status'] === true) {
            $tindakLanjut->update([
                'status_wa'  => 'terkirim',
                'dikirim_at' => now(),
            ]);
            return back()->with('success', 'WhatsApp berhasil dikirim ke ' . $nomor);
        }

        $tindakLanjut->update(['status_wa' => 'gagal']);
        return back()->with('error', 'Gagal mengirim WhatsApp. Cek API token Fonnte di .env');
    }

    // ───────────────────────────────────────────
    // KIRIM EMAIL via SMTP (tanpa Mailable class)
    // ───────────────────────────────────────────
    public function kirimEmail(TindakLanjut $tindakLanjut)
    {
        $konseling = $tindakLanjut->konseling->load(['siswa', 'guru']);
        $siswa     = $konseling->siswa;

        if (!$siswa->email) {
            return back()->with('error', 'Email siswa/orang tua tidak tersedia.');
        }

        // Generate PDF untuk attachment
        $qrCode = QrCode::format('svg')->size(90)->errorCorrection('M')
            ->generate(url('/verifikasi-surat/' . $tindakLanjut->kode_unik));

        $pdfContent = Pdf::loadView('pdf.tindak-lanjut', [
            'tl'        => $tindakLanjut,
            'konseling' => $konseling,
            'qrCode'    => $qrCode,
        ])->setPaper('a4')->output();

        $filename = 'TindakLanjut_' . $tindakLanjut->kode_unik . '.pdf';

        try {
            // Kirim via SMTP menggunakan config mail di .env
            \Mail::send([], [], function ($message) use ($siswa, $konseling, $tindakLanjut, $pdfContent, $filename) {
                $message
                    ->to($siswa->email, $siswa->name)
                    ->subject('[SMK YPML] Tindak Lanjut Konseling — ' . $tindakLanjut->jenis_label)
                    ->html(
                        view('emails.tindak-lanjut', [
                            'siswa'       => $siswa,
                            'konseling'   => $konseling,
                            'tindakLanjut' => $tindakLanjut,
                        ])->render()
                    )
                    ->attachData($pdfContent, $filename, ['mime' => 'application/pdf']);
            });

            $tindakLanjut->update([
                'status_email' => 'terkirim',
                'dikirim_at'   => now(),
            ]);
            return back()->with('success', 'Email berhasil dikirim ke ' . $siswa->email);

        } catch (\Exception $e) {
            $tindakLanjut->update(['status_email' => 'gagal']);
            return back()->with('error', 'Gagal mengirim email. Periksa konfigurasi SMTP di .env — ' . $e->getMessage());
        }
    }

    // ───────────────────────────────────────────
    // HELPER: Fonnte API
    // ───────────────────────────────────────────
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
