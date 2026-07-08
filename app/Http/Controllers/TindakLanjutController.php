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

        // Automatically send WA and Email notifications
        [$waSent, $emailSent] = $this->kirimNotifikasiOtomatis($tl);

        $msg = 'Tindak lanjut berhasil disimpan.';
        if ($waSent && $emailSent) {
            $msg .= ' Notifikasi WhatsApp & Email berhasil dikirim secara otomatis.';
            return back()->with('success', $msg);
        } else {
            $msg .= ' Status Pengiriman - WA: ' . ($waSent ? 'Terkirim' : 'Gagal') . ', Email: ' . ($emailSent ? 'Terkirim' : 'Gagal') . '.';
            return back()->with('warning', $msg);
        }
    }

    // ───────────────────────────────────────────
    // PUBLIC: Kirim notifikasi WA + Email
    // Bisa dipanggil dari controller lain
    // ───────────────────────────────────────────
    public function kirimNotifikasiOtomatis(TindakLanjut $tl): array
    {
        $waSent    = $this->autoKirimWa($tl);
        $emailSent = $this->autoKirimEmail($tl);
        return [$waSent, $emailSent];
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
    // AUTOMATED WHATSAPP SENDING
    // ───────────────────────────────────────────
    private function autoKirimWa(TindakLanjut $tindakLanjut): bool
    {
        $konseling = $tindakLanjut->konseling->load(['siswa', 'guru']);
        $siswa     = $konseling->siswa;

        $recipientNumber = $siswa->no_telp_ortu ?: $siswa->no_telp;
        $recipientName = $siswa->nama_ortu ?: 'Orang Tua/Wali';

        if (!$recipientNumber) {
            $tindakLanjut->update(['status_wa' => 'gagal']);
            return false;
        }

        $namaPanggil = $recipientName !== 'Orang Tua/Wali' ? $recipientName : 'Bapak/Ibu';
        $namaGuru    = $konseling->guru?->name ?? 'Guru BK';
        $tanggal     = now()->translatedFormat('d F Y');

        $pesan  = "Assalamu'alaikum, {$namaPanggil}.\n\n";
        $pesan .= "Saya {$namaGuru}, Guru Bimbingan Konseling di SMK YPML, ingin menyampaikan kabar terkait putra/putri Bapak/Ibu, *{$siswa->name}*.\n\n";
        $pesan .= "Pada tanggal {$tanggal}, kami telah melakukan tindak lanjut konseling berupa *{$tindakLanjut->jenis_label}*.\n\n";
        $pesan .= "*Catatan dari Guru BK:*\n_{$tindakLanjut->catatan}_\n\n";
        $pesan .= "Surat resmi dan detail lengkap mengenai pemanggilan ini telah kami kirimkan ke Email Bapak/Ibu. Mohon diperiksa sebagai tanda telah menerima informasi ini.\n\n";
        $pesan .= "Apabila ada yang ingin didiskusikan lebih lanjut, Bapak/Ibu bisa menghubungi kami langsung di sekolah. Kami terbuka untuk berbicara kapan saja.\n\n";
        $pesan .= "Terima kasih atas perhatian dan kerjasamanya. Semoga {$siswa->name} bisa terus berkembang dengan baik.\n\n";
        $pesan .= "Salam hangat,\n{$namaGuru}\nGuru BK SMK YPML\n\n";
        $pesan .= "_Kode verifikasi surat: {$tindakLanjut->kode_unik}_";

        $nomor = preg_replace('/[^0-9]/', '', $recipientNumber);
        if (str_starts_with($nomor, '0')) {
            $nomor = '62' . substr($nomor, 1);
        } elseif (str_starts_with($nomor, '8')) {
            $nomor = '62' . $nomor;
        }

        // ponytail: skip PDF attachment to bypass Fonnte free-tier file restrictions
        $response = $this->sendFonnte($nomor, $pesan);

        if ($response && isset($response['status']) && $response['status'] === true) {
            $tindakLanjut->update([
                'status_wa'  => 'terkirim',
                'dikirim_at' => now(),
            ]);
            return true;
        }

        $tindakLanjut->update(['status_wa' => 'gagal']);
        return false;
    }

    // ───────────────────────────────────────────
    // AUTOMATED EMAIL SENDING
    // ───────────────────────────────────────────
    private function autoKirimEmail(TindakLanjut $tindakLanjut): bool
    {
        $konseling = $tindakLanjut->konseling->load(['siswa', 'guru']);
        $siswa     = $konseling->siswa;

        // Kirim ke email orang tua jika ada, fallback ke email siswa
        $emailTujuan = $siswa->email_ortu ?? $siswa->email ?? null;

        if (!$emailTujuan) {
            $tindakLanjut->update(['status_email' => 'gagal']);
            return false;
        }

        $namaPenerima = $siswa->nama_ortu ?? $siswa->name;

        // Generate PDF untuk attachment
        $verifikasiUrl = url('/verifikasi-surat/' . $tindakLanjut->kode_unik);
        $qrCode = QrCode::format('svg')->size(90)->errorCorrection('M')->generate($verifikasiUrl);

        $pdfContent = Pdf::loadView('pdf.tindak-lanjut', [
            'tl'        => $tindakLanjut,
            'konseling' => $konseling,
            'qrCode'    => $qrCode,
        ])->setPaper('a4')->output();

        $filename = 'TindakLanjut_' . $tindakLanjut->kode_unik . '.pdf';

        try {
            \Mail::send([], [], function ($message) use ($siswa, $konseling, $tindakLanjut, $pdfContent, $filename, $emailTujuan, $namaPenerima) {
                $message
                    ->to($emailTujuan, $namaPenerima)
                    ->subject('Pemberitahuan: ' . $tindakLanjut->jenis_label . ' — ' . $siswa->name)
                    ->html(
                        view('emails.tindak-lanjut', [
                            'siswa'        => $siswa,
                            'konseling'    => $konseling,
                            'tindakLanjut' => $tindakLanjut,
                        ])->render()
                    )
                    ->attachData($pdfContent, $filename, ['mime' => 'application/pdf']);
            });

            $tindakLanjut->update([
                'status_email' => 'terkirim',
                'dikirim_at'   => now(),
            ]);
            return true;

        } catch (\Exception $e) {
            \Log::error('Email tindak lanjut gagal: ' . $e->getMessage());
            $tindakLanjut->update(['status_email' => 'gagal']);
            return false;
        }
    }

    // Keep public actions for manual retry/redirect if requested, routing internally to auto methods
    public function kirimWa(TindakLanjut $tindakLanjut)
    {
        $status = $this->autoKirimWa($tindakLanjut);
        if ($status) {
            return back()->with('success', 'WhatsApp berhasil dikirim.');
        }
        return back()->with('error', 'Gagal mengirim WhatsApp. Cek API token Fonnte di .env');
    }

    public function kirimEmail(TindakLanjut $tindakLanjut)
    {
        $status = $this->autoKirimEmail($tindakLanjut);
        if ($status) {
            return back()->with('success', 'Email berhasil dikirim.');
        }
        return back()->with('error', 'Gagal mengirim email.');
    }

    // ───────────────────────────────────────────
    // HELPER: Fonnte API
    // ───────────────────────────────────────────
    private function sendFonnte(string $nomor, string $pesan, ?string $filePath = null, ?string $fileName = null): ?array
    {
        $token = env('FONNTE_API_KEY');
        if (!$token) return null;

        $fields = [
            'target'  => $nomor,
            'message' => $pesan,
        ];

        if ($filePath && file_exists($filePath)) {
            $fields['file'] = new \CURLFile($filePath, 'application/pdf', $fileName ?: 'dokumen.pdf');
        }

        $ch = curl_init('https://api.fonnte.com/send');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $fields,
            CURLOPT_HTTPHEADER => ['Authorization: ' . $token],
        ]);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result ? json_decode($result, true) : null;
    }
}
