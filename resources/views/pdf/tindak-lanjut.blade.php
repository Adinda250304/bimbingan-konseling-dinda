<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Surat Tindak Lanjut - {{ $konseling->siswa->name }}</title>
<style>
    @page { margin: 1cm 2cm; }
    body {
        font-family: 'Times New Roman', Times, serif;
        font-size: 11.5pt;
        color: #000;
        line-height: 1.35;
    }
    .kop-surat {
        text-align: center;
        border-bottom: 4px double #000;
        margin-bottom: 15px;
        padding-bottom: 8px;
        position: relative;
    }
    .kop-surat img {
        position: absolute;
        left: 0;
        top: 0;
        width: 80px;
        height: auto;
    }
    .kop-surat h2 { margin: 0; font-size: 13pt; font-weight: normal; }
    .kop-surat h1 { margin: 0; font-size: 16pt; font-weight: bold; letter-spacing: 1px; }
    .kop-surat p { margin: 2px 0 0; font-size: 10.5pt; }
    
    .meta-surat-container {
        width: 100%;
        margin-bottom: 15px;
    }
    .meta-surat-container table { font-size: 11.5pt; }
    .tujuan-surat { margin-bottom: 15px; font-size: 11.5pt; }
    
    .isi-surat {
        text-align: justify;
        margin-bottom: 20px;
    }
    .tabel-identitas {
        margin: 8px 0 8px 20px;
        font-size: 11.5pt;
    }
    .tabel-identitas td {
        padding: 2px 0;
    }
    
    .signature-area {
        width: 100%;
        margin-top: 25px;
        page-break-inside: avoid;
    }
    .signature-area td {
        width: 50%;
        text-align: center;
        vertical-align: bottom;
    }
    .qrcode-wrapper {
        margin: 10px 0;
    }
    p { margin: 10px 0; }
</style>
</head>
<body>

    <div class="kop-surat">
        <img src="{{ public_path('img/logo-ypml.png') }}" alt="Logo">
        <h2>YAYASAN PENDIDIKAN MANDIRI LENTERA</h2>
        <h1>SMK YPML</h1>
        <p style="margin: 2px 0 0;">Jl. Contoh Jalan Pendidikan No. 123, Kota Contoh, 12345</p>
        <p style="margin: 2px 0 0;">Telp: (021) 1234567 | Email: info@smkypml.sch.id</p>
    </div>

    <div class="meta-surat-container">
        <div style="float: right; text-align: right; font-size: 11.5pt;">
            {{ now()->translatedFormat('d F Y') }}
        </div>
        <table style="width: 70%;">
            <tr>
                <td width="70">Nomor</td>
                <td width="10">:</td>
                <td>Bimkos/{{ date('Y') }}/{{ date('m') }}/{{ $tl->kode_unik }}</td>
            </tr>
            <tr>
                <td>Lampiran</td>
                <td>:</td>
                <td>-</td>
            </tr>
            <tr>
                <td>Perihal</td>
                <td>:</td>
                <td><strong>Pemberitahuan {{ $tl->jenis_label }}</strong></td>
            </tr>
        </table>
        <div style="clear: both;"></div>
    </div>

    <div class="tujuan-surat">
        <p>Kepada Yth.<br>
        Bapak/Ibu Orang Tua/Wali dari <strong>{{ $konseling->siswa->name }}</strong><br>
        di tempat</p>
    </div>

    <div class="isi-surat">
        <p>Dengan hormat,</p>
        <p>Puji syukur kita panjatkan ke hadirat Tuhan Yang Maha Esa atas segala limpahan rahmat-Nya. Sehubungan dengan program pendampingan dan bimbingan karakter siswa di lingkungan sekolah, melalui surat ini kami bermaksud menyampaikan informasi mengenai peserta didik:</p>
        
        <table class="tabel-identitas">
            <tr>
                <td width="130">Nama Lengkap</td>
                <td width="10">:</td>
                <td><strong>{{ $konseling->siswa->name }}</strong></td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>:</td>
                <td>{{ $konseling->siswa->kelas ?? '—' }}</td>
            </tr>
            <tr>
                <td>Jenis Konseling</td>
                <td>:</td>
                <td>{{ $konseling->jenis_masalah }}</td>
            </tr>
        </table>

        <p>Berdasarkan catatan hasil pemantauan serta tindak lanjut proses bimbingan konseling yang telah dilaksanakan, pihak sekolah menerbitkan surat berstatus <strong>{{ $tl->jenis_label }}</strong>.</p>

        <p>Kami menyampaikan hal ini sebagai bentuk langkah kooperatif antara pihak sekolah dan orang tua. Segala bentuk dukungan sangat berarti demi perkembangan peserta didik.</p>

        <p>Demikian surat pemberitahuan ini kami sampaikan agar dapat dimaklumi. Atas perhatian dan kerja samanya, kami ucapkan terima kasih.</p>
    </div>

    <table class="signature-area">
        <tr>
            <td style="width: 50%; text-align: center; vertical-align: bottom;">
                <p>Mengetahui,<br>Kepala Sekolah</p>
                <br><br><br><br>
                <p><strong>( ________________________ )</strong></p>
            </td>
            <td style="width: 50%; text-align: center; vertical-align: bottom;">
                <p>Guru Bimbingan Konseling</p>
                <div class="qrcode-wrapper">
                    <img src="data:image/svg+xml;base64,{{ base64_encode($qrCode) }}" width="75" height="75" alt="QR Code">
                </div>
                <p><strong>{{ $konseling->guru?->name ?? 'Guru BK' }}</strong></p>
            </td>
        </tr>
    </table>

</body>
</html>

