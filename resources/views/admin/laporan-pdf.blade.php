<!DOCTYPE html>
<html>
<head>
    <title>Rekap Laporan Konseling</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12px; color: #000; line-height: 1.5; margin: 0; padding: 0; }
        .container { padding: 20px; }
        
        /* Formal Header Style */
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header h2 { margin: 5px 0 0; font-size: 14px; font-weight: normal; }
        
        .title { text-align: center; margin-top: 20px; margin-bottom: 20px; }
        .title h3 { margin: 0; text-decoration: underline; font-size: 16px; text-transform: uppercase; }
        
        .meta { margin-bottom: 20px; }
        .meta table { width: 100%; }
        .meta td { padding: 2px 0; }
        
        table.main { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.main th { border: 1px solid #000; padding: 8px; text-align: center; background-color: #f2f2f2; font-weight: bold; }
        table.main td { border: 1px solid #000; padding: 8px; vertical-align: top; }
        
        .summary { margin-top: 20px; font-weight: bold; }
        
        .footer { margin-top: 50px; }
        .footer table { width: 100%; }
        .footer td { text-align: center; }
        .signature-space { height: 70px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>LAPORAN REKAPITULASI BIMBINGAN KONSELING</h1>
            <h2>Bimkos - Sistem Informasi Bimbingan Konseling SMK YPML</h2>
        </div>

        <div class="title">
            <h3>REKAPITULASI DATA KONSELING</h3>
        </div>

        <div class="meta">
            <table>
                <tr>
                    <td width="15%">Periode</td>
                    <td width="2%">:</td>
                    <td>{{ \Carbon\Carbon::parse($start_date)->format('d F Y') }} s/d {{ \Carbon\Carbon::parse($end_date)->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td>Jumlah Sesi</td>
                    <td>:</td>
                    <td>{{ $konselings->count() }} Sesi Konseling</td>
                </tr>
                <tr>
                    <td>Status Selesai</td>
                    <td>:</td>
                    <td>{{ $konselings->where('status', 'selesai')->count() }} Sesi</td>
                </tr>
            </table>
        </div>

        <table class="main">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Tanggal</th>
                    <th width="20%">Nama Siswa</th>
                    <th width="10%">Kelas</th>
                    <th width="25%">Permasalahan</th>
                    <th width="10%">Status</th>
                    <th width="15%">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($konselings as $index => $k)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="text-align: center;">{{ $k->created_at->format('d/m/Y') }}</td>
                    <td>{{ $k->siswa->name }}</td>
                    <td style="text-align: center;">{{ $k->siswa->kelas }}</td>
                    <td>{{ $k->jenis_masalah }}</td>
                    <td style="text-align: center; text-transform: uppercase;">{{ $k->status }}</td>
                    <td>{{ $k->hasil ? Str::limit($k->hasil->saran, 30) : '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <table>
                <tr>
                    <td width="60%"></td>
                    <td width="40%">
                        Tangerang, {{ now()->format('d F Y') }}<br>
                        Guru Bimbingan Konseling
                        <div class="signature-space"></div>
                        <strong>{{ $user->name }}</strong><br>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
