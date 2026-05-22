<!DOCTYPE html>
<html>
<head>
    <title>Laporan Jurnal Mengajar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 5px 0;
        }
        .subtitle {
            font-size: 12px;
            color: #555;
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            font-size: 10px;
            padding: 2px 4px;
            border-radius: 3px;
        }
        .badge-pengganti {
            background-color: #fef08a;
            color: #854d0e;
        }
        .filter-info {
            font-size: 11px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">LAPORAN JURNAL MENGAJAR GURU</h1>
        <p class="subtitle">Periode: {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}</p>
    </div>

    <div class="filter-info">
        <strong>Filter Diterapkan:</strong><br>
        Guru: {{ $guruId ? $gurus->find($guruId)->nama_lengkap : 'Semua Guru' }}<br>
        Status: {{ $statusValidasi ? $statusValidasi : 'Semua Status' }}
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="20%">Guru</th>
                <th width="15%">Kelas & Mapel</th>
                <th width="10%">Jam Ke</th>
                <th width="25%">Materi</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jurnals as $index => $jurnal)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($jurnal->tanggal_mengajar)->translatedFormat('d M Y') }}</td>
                    <td>
                        {{ $jurnal->guruPengisi->nama_lengkap }}
                        @if($jurnal->guru_pengisi_id !== $jurnal->jadwal->guru_id)
                            <br><span class="badge badge-pengganti">Pengganti</span>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $jurnal->jadwal->kelas->nama_kelas }}</strong><br>
                        {{ $jurnal->jadwal->mataPelajaran->nama_mapel }}
                    </td>
                    <td class="text-center">{{ $jurnal->jadwal->jam_ke_mulai }} - {{ $jurnal->jadwal->jam_ke_selesai }}</td>
                    <td>{{ $jurnal->materi_pembelajaran }}</td>
                    <td class="text-center">{{ $jurnal->status_validasi }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data jurnal pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
