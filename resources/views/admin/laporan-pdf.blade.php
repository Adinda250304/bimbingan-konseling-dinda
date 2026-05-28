<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Rekapitulasi Bimbingan Konseling</title>
    <style>
        body { 
            font-family: 'Times New Roman', Times, serif; 
            font-size: 11px; 
            color: #000; 
            line-height: 1.4; 
            margin: 0; 
            padding: 0; 
        }
        .container { 
            padding: 10px 20px; 
        }
        
        /* Kop Surat (Letterhead) */
        .kop-surat {
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 15px;
            text-align: center;
        }
        .kop-surat .yayasan {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }
        .kop-surat .sekolah {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 2px 0;
        }
        .kop-surat .alamat {
            font-size: 9px;
            margin: 0;
        }
        
        .title { 
            text-align: center; 
            margin-bottom: 15px; 
        }
        .title h3 { 
            margin: 0; 
            font-size: 13px; 
            text-transform: uppercase; 
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .title .periode {
            font-size: 11px;
            margin-top: 3px;
        }
        
        .meta-summary { 
            width: 100%;
            margin-bottom: 15px; 
            font-size: 10px;
        }
        .meta-summary td {
            padding: 1px 0;
        }
        
        table.main { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 5px; 
        }
        table.main th { 
            border: 1px solid #000; 
            padding: 6px 4px; 
            text-align: center; 
            background-color: #f2f2f2; 
            font-weight: bold; 
            font-size: 10px;
            text-transform: uppercase;
        }
        table.main td { 
            border: 1px solid #000; 
            padding: 6px 6px; 
            vertical-align: top; 
            font-size: 10px;
        }
        
        .footer-sig { 
            margin-top: 35px; 
            width: 100%;
        }
        .footer-sig td { 
            vertical-align: top;
            font-size: 10px;
        }
        .signature-space { 
            height: 65px; 
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Kop Surat -->
        <div class="kop-surat">
            <h4 class="yayasan">Yayasan Pendidikan Mandiri Lestari (YPML)</h4>
            <h2 class="sekolah">SMK YPML TANGERANG</h2>
            <p class="alamat">Jl. Raya YPML No. 12, Kel. Kedaung Wetan, Kec. Neglasari, Kota Tangerang, Banten 15128</p>
            <p class="alamat">Telp: (021) 5578990 | Website: www.smkypml.sch.id | Email: info@smkypml.sch.id</p>
        </div>

        <!-- Judul Laporan -->
        <div class="title">
            <h3>Laporan Rekapitulasi Pelayanan Bimbingan Konseling</h3>
            <div class="periode">
                Periode: {{ \Carbon\Carbon::parse($start_date)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($end_date)->translatedFormat('d F Y') }}
            </div>
        </div>

        <!-- Ringkasan Singkat -->
        <table class="meta-summary">
            <tr>
                <td width="15%">Total Sesi Konseling</td>
                <td width="2%">:</td>
                <td width="33%">{{ $konselings->count() }} Sesi</td>
                <td width="15%">Selesai</td>
                <td width="2%">:</td>
                <td width="33%">{{ $konselings->where('status', 'selesai')->count() }} Sesi</td>
            </tr>
            <tr>
                <td>Berlangsung</td>
                <td>:</td>
                <td>{{ $konselings->where('status', 'disetujui')->count() }} Sesi</td>
                <td>Ditolak / Batal</td>
                <td>:</td>
                <td>{{ $konselings->where('status', 'ditolak')->count() }} Sesi</td>
            </tr>
        </table>

        <!-- Tabel Utama Laporan -->
        <table class="main">
            <thead>
                <tr>
                    <th width="4%">No</th>
                    <th width="12%">Hari, Tanggal</th>
                    <th width="18%">Nama Siswa</th>
                    <th width="10%">Kelas</th>
                    <th width="12%">Kategori</th>
                    <th width="24%">Deskripsi Masalah</th>
                    <th width="20%">Saran / Tindak Lanjut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($konselings as $index => $k)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="text-align: center;">
                        @if($k->tanggal_konseling)
                            {{ \Carbon\Carbon::parse($k->tanggal_konseling)->translatedFormat('D, d M Y') }}
                        @else
                            {{ $k->created_at->translatedFormat('D, d M Y') }}
                        @endif
                    </td>
                    <td>{{ $k->siswa->name }}</td>
                    <td style="text-align: center;">{{ $k->siswa->kelas }}</td>
                    <td style="text-align: center; text-transform: capitalize;">{{ $k->jenis_masalah }}</td>
                    <td>{{ Str::limit($k->deskripsi_masalah, 100) }}</td>
                    <td>
                        @if($k->hasil)
                            {{ Str::limit($k->hasil->saran, 80) }}
                        @elseif($k->status === 'ditolak')
                            <span style="color: red; font-style: italic;">Ditolak: {{ $k->alasan_penolakan }}</span>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; font-style: italic; padding: 15px;">
                        Tidak ada data konseling pada periode ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Bagian Tanda Tangan -->
        <table class="footer-sig">
            <tr>
                <td width="50%">
                    Tangerang, {{ now()->translatedFormat('d F Y') }}<br>
                    Guru Bimbingan Konseling
                    <div class="signature-space"></div>
                    <strong>{{ $user->name }}</strong><br>
                    NIP. -
                </td>
                <td width="50%">
                    Mengetahui,<br>
                    Kepala Sekolah SMK YPML
                    <div class="signature-space"></div>
                    <strong>....................................................</strong><br>
                    NIP. -
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
