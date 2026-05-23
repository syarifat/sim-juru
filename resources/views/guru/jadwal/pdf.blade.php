<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Jadwal Mengajar - {{ $guru->nama_lengkap }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #333333;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #059669;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .header h1 {
            font-size: 18px;
            color: #059669;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }
        .header p {
            font-size: 11px;
            color: #666666;
            margin: 0;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 4px 8px;
            vertical-align: top;
        }
        .info-table .label {
            font-weight: bold;
            color: #4b5563;
            width: 120px;
        }
        .info-table .value {
            color: #1f2937;
        }
        .schedule-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            border: 1px solid #94a3b8;
        }
        .schedule-table th {
            border: 1px solid #94a3b8;
            padding: 6px 8px;
            font-size: 9px;
        }
        .th-main {
            background-color: #facc15;
            color: #0f172a;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center !important;
        }
        .th-sub-mulai {
            background-color: #059669;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center !important;
        }
        .th-sub-selesai {
            background-color: #b91c1c;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center !important;
        }
        .schedule-table td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            font-size: 9px;
        }
        .day-column {
            font-weight: bold;
            background-color: #f1f5f9 !important;
            color: #0f172a;
            text-transform: uppercase;
            vertical-align: top;
        }
        .td-no {
            text-align: center;
            background-color: #f8fafc;
            color: #475569;
            font-weight: bold;
        }
        .td-mulai {
            text-align: center;
            background-color: #f0fdf4;
            color: #16a34a;
            font-weight: bold;
            font-family: Courier, monospace;
        }
        .td-selesai {
            text-align: center;
            background-color: #fef2f2;
            color: #dc2626;
            font-weight: bold;
            font-family: Courier, monospace;
        }
        .class-column {
            font-weight: bold;
            color: #0f172a;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 9px;
            color: #9ca3af;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Jadwal Mengajar Guru</h1>
        <p>Aplikasi SIM-JURU - Sistem Informasi Jurnal & Jadwal Guru</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Nama Guru</td>
            <td class="value">: <strong>{{ $guru->nama_lengkap }}</strong></td>
            <td class="label">Tahun Ajaran</td>
            <td class="value">: {{ $activeTahunAjaran->tahun }} - {{ $activeTahunAjaran->semester }}</td>
        </tr>
        <tr>
            <td class="label">NIP</td>
            <td class="value">: {{ $guru->nip ?? '-' }}</td>
            <td class="label">Tanggal Cetak</td>
            <td class="value">: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}</td>
        </tr>
    </table>

    <table class="schedule-table">
        <thead>
            <tr>
                <th class="th-main" rowspan="2" style="width: 5%;">No</th>
                <th class="th-main" rowspan="2" style="width: 15%;">Hari</th>
                <th class="th-main" rowspan="2" style="width: 35%;">Mata Pelajaran</th>
                <th class="th-main" rowspan="2" style="width: 15%;">Kelas</th>
                <th class="th-main" colspan="2" style="width: 20%;">Jam Pelajaran</th>
                <th class="th-main" rowspan="2" style="width: 10%;">Ket</th>
            </tr>
            <tr>
                <th class="th-sub-mulai" style="width: 10%;">Mulai</th>
                <th class="th-sub-selesai" style="width: 10%;">Selesai</th>
            </tr>
        </thead>
        <tbody>
            @php
                $currentHari = '';
                $dayCounter = 0;
            @endphp
            @forelse($jadwals as $jadwal)
                @php
                    $waktuMulai = $jamPelajarans[$jadwal->jam_ke_mulai]->jam_mulai ?? null;
                    $waktuSelesai = $jamPelajarans[$jadwal->jam_ke_selesai]->jam_selesai ?? null;
                    $waktuMulaiStr = $waktuMulai ? \Carbon\Carbon::parse($waktuMulai)->format('H:i') : '-';
                    $waktuSelesaiStr = $waktuSelesai ? \Carbon\Carbon::parse($waktuSelesai)->format('H:i') : '-';
                    $isHariChanged = $currentHari !== $jadwal->hari;
                    if ($isHariChanged) {
                        $currentHari = $jadwal->hari;
                        $dayCounter = 1;
                    } else {
                        $dayCounter++;
                    }
                @endphp
                <tr>
                    <td class="td-no">{{ $dayCounter }}</td>
                    @if($isHariChanged)
                        @php
                            $hariCount = $jadwals->where('hari', $jadwal->hari)->count();
                        @endphp
                        <td class="day-column" rowspan="{{ $hariCount }}">
                            {{ $jadwal->hari }}
                        </td>
                    @endif
                    <td>{{ $jadwal->mataPelajaran->nama_mapel }}</td>
                    <td class="class-column">{{ $jadwal->kelas->nama_kelas }}</td>
                    <td class="td-mulai">{{ $waktuMulaiStr }}</td>
                    <td class="td-selesai">{{ $waktuSelesaiStr }}</td>
                    <td style="color: #64748b; font-size: 8px; text-align: center;">
                        Jam Ke {{ $jadwal->jam_ke_mulai }}{{ $jadwal->jam_ke_mulai != $jadwal->jam_ke_selesai ? '-' . $jadwal->jam_ke_selesai : '' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px; color: #9ca3af;">
                        Belum ada jadwal mengajar yang terdaftar.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak secara otomatis oleh Sistem Informasi Jurnal Mengajar SIM-JURU pada {{ date('d-m-Y H:i:s') }}
    </div>
</body>
</html>
