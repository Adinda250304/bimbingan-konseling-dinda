<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
    body { margin: 0; padding: 0; font-family: Georgia, 'Times New Roman', serif; background: #f5f5f0; color: #2d2d2d; }
    .wrapper { max-width: 600px; margin: 32px auto; background: #fff; border-radius: 4px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
    .top-bar { background: #1a3a5c; height: 5px; }
    .letterhead { padding: 28px 40px 20px; border-bottom: 1px solid #e8e4df; }
    .letterhead .school { font-size: 13px; color: #6b6b6b; text-transform: uppercase; letter-spacing: 0.08em; }
    .letterhead .dept { font-size: 15px; font-weight: bold; color: #1a3a5c; margin-top: 2px; }
    .body { padding: 32px 40px; line-height: 1.85; font-size: 14px; }
    .body p { margin: 0 0 16px; }
    .catatan { background: #fafaf7; border-left: 3px solid #1a3a5c; padding: 14px 18px; margin: 20px 0; font-style: italic; font-size: 14px; color: #4b4b4b; border-radius: 0 4px 4px 0; line-height: 1.8; }
    .kode { display: inline-block; font-family: 'Courier New', monospace; font-size: 13px; background: #f0f0eb; padding: 3px 10px; border-radius: 3px; color: #333; letter-spacing: 0.05em; }
    .signature { margin-top: 28px; }
    .signature .name { font-weight: bold; font-size: 14px; margin-top: 4px; }
    .signature .title { font-size: 13px; color: #6b6b6b; }
    .footer { background: #f9f9f6; padding: 16px 40px; border-top: 1px solid #e8e4df; }
    .footer p { font-size: 11px; color: #aaa; margin: 0; line-height: 1.6; }
</style>
</head>
<body>
<div class="wrapper">
    <div class="top-bar"></div>
    <div class="letterhead">
        <div class="school">SMK YPML</div>
        <div class="dept">Bimbingan &amp; Konseling</div>
    </div>
    <div class="body">
        @php
            $namaPanggil = $siswa->nama_ortu ?? 'Bapak/Ibu';
            $namaGuru    = $konseling->guru?->name ?? 'Guru BK';
            $tanggal     = now()->translatedFormat('d F Y');
        @endphp

        <p>Assalamu'alaikum, {{ $namaPanggil }}.</p>

        <p>
            Semoga Bapak/Ibu dalam keadaan sehat dan baik. Saya {{ $namaGuru }}, Guru Bimbingan Konseling 
            di SMK YPML, ingin menyampaikan informasi terkait putra/putri Bapak/Ibu, 
            <strong>{{ $siswa->name }}</strong> (Kelas {{ $siswa->kelas ?? '—' }}).
        </p>

        <p>
            Pada tanggal {{ $tanggal }}, kami telah melakukan tindak lanjut konseling berupa 
            <strong>{{ $tindakLanjut->jenis_label }}</strong> sebagai bagian dari upaya kami mendampingi 
            perkembangan {{ $siswa->name }} di sekolah.
        </p>

        <p>Berikut catatan yang ingin saya sampaikan:</p>

        <div class="catatan">{{ $tindakLanjut->catatan }}</div>

        <p>
            Surat resmi dengan detail lengkap telah kami lampirkan dalam format PDF di email ini. 
            Mohon kiranya Bapak/Ibu berkenan membacanya dan menandatangani sebagai tanda telah menerima informasi ini.
        </p>

        <p>
            Kami sangat mengharapkan keterlibatan Bapak/Ibu dalam proses ini karena dukungan keluarga 
            sangat berarti bagi perkembangan {{ $siswa->name }}. Apabila ada hal yang ingin 
            Bapak/Ibu diskusikan, jangan ragu untuk menghubungi kami langsung di sekolah — kami 
            terbuka untuk berbicara kapan saja.
        </p>

        <p>Terima kasih atas perhatian dan kepercayaan Bapak/Ibu kepada kami.</p>

        <div class="signature">
            <p style="margin:0;">Salam hangat,</p>
            <p class="name">{{ $namaGuru }}</p>
            <p class="title">Guru Bimbingan Konseling<br>SMK YPML</p>
        </div>

        <p style="margin-top:28px;font-size:12px;color:#aaa;">
            Kode verifikasi surat: <span class="kode">{{ $tindakLanjut->kode_unik }}</span>
        </p>
    </div>
    <div class="footer">
        <p>
            Pesan ini dikirim secara pribadi oleh sistem Teman BK atas nama {{ $namaGuru }}.<br>
            Jika ini bukan tujuan yang tepat, abaikan email ini atau hubungi pihak sekolah.
        </p>
    </div>
</div>
</body>
</html>
