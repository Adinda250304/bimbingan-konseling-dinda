<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
    body { margin: 0; padding: 0; font-family: Arial, sans-serif; background: #f3f4f6; }
    .container { max-width: 580px; margin: 30px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
    .header { background: #1e3a8a; padding: 28px 32px; text-align: center; }
    .header h1 { color: white; font-size: 20px; margin: 0 0 4px; }
    .header p { color: #93c5fd; font-size: 13px; margin: 0; }
    .body { padding: 28px 32px; }
    .greeting { font-size: 15px; color: #374151; margin-bottom: 18px; }
    .info-box { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 16px 20px; margin: 16px 0; }
    .info-row { display: flex; margin-bottom: 8px; font-size: 13px; }
    .info-row:last-child { margin-bottom: 0; }
    .info-label { font-weight: bold; color: #1e40af; min-width: 130px; }
    .info-value { color: #374151; }
    .catatan-box { background: #f9fafb; border-left: 4px solid #1e3a8a; padding: 14px 16px; margin: 16px 0; border-radius: 0 8px 8px 0; font-size: 13px; color: #4b5563; line-height: 1.7; }
    .badge { display: inline-block; background: #dbeafe; color: #1d4ed8; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
    .footer-note { font-size: 12px; color: #9ca3af; text-align: center; margin-top: 24px; border-top: 1px solid #f3f4f6; padding-top: 16px; }
    .footer { background: #f9fafb; padding: 16px 32px; text-align: center; }
    .footer p { font-size: 11px; color: #9ca3af; margin: 0; }
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>📋 Tindak Lanjut Konseling</h1>
        <p>SMK YPML — Bimbingan Konseling</p>
    </div>
    <div class="body">
        <p class="greeting">Yth. Orang Tua/Wali dari <strong>{{ $siswa->name }}</strong>,</p>
        <p style="font-size:13px;color:#4b5563;margin-bottom:16px;">
            Kami menginformasikan bahwa telah dilakukan tindak lanjut atas sesi konseling siswa kami.
            Harap membaca informasi berikut dengan saksama.
        </p>

        <div class="info-box">
            <div class="info-row">
                <span class="info-label">Nama Siswa</span>
                <span class="info-value">{{ $siswa->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Kelas</span>
                <span class="info-value">{{ $siswa->kelas ?? '—' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Jenis Tindak Lanjut</span>
                <span class="info-value"><span class="badge">{{ $tindakLanjut->jenis_label }}</span></span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal Surat</span>
                <span class="info-value">{{ now()->format('d F Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Guru BK</span>
                <span class="info-value">{{ $konseling->guru?->name ?? '—' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Kode Surat</span>
                <span class="info-value"><code>{{ $tindakLanjut->kode_unik }}</code></span>
            </div>
        </div>

        <p style="font-size:13px;font-weight:bold;color:#374151;margin-bottom:8px;">Catatan dari Guru BK:</p>
        <div class="catatan-box">{{ $tindakLanjut->catatan }}</div>

        <p style="font-size:13px;color:#4b5563;margin-top:16px;">
            Surat resmi terlampir dalam format PDF pada email ini. 
            Silakan hubungi pihak sekolah apabila memerlukan informasi lebih lanjut.
        </p>

        <div class="footer-note">
            Email ini dikirim otomatis oleh sistem <strong>Teman BK</strong>.<br>
            Kode verifikasi surat: <code>{{ $tindakLanjut->kode_unik }}</code>
        </div>
    </div>
    <div class="footer">
        <p>© {{ date('Y') }} SMK YPML — Sistem Teman BK</p>
    </div>
</div>
</body>
</html>
