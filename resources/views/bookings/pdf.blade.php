<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman</title>
    <style>
        /* Use A4 landscape to accommodate wide tables */
        @page { size: A4 landscape; margin: 18mm; }

        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #111; }
        .header { text-align: center; margin-bottom: 10px; }
        .header h2 { margin: 0; font-size: 18px; }
        .meta { margin-bottom: 8px; }

        /* Table styles tuned for PDF rendering */
        table { width: 100%; border-collapse: collapse; table-layout: fixed; word-wrap: break-word; }
        thead th { border: 1px solid #cfcfcf; padding: 6px 6px; font-size: 10px; }
        tbody td { border: 1px solid #e6e6e6; padding: 5px 6px; font-size: 10px; vertical-align: top; }
        th { background: #f7f7f7; font-weight: bold; }

        /* allow long text to wrap and avoid overflow */
        th, td { word-break: break-word; white-space: normal; }

        .small { font-size: 9px; color: #555; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Peminjaman Ruangan</h2>
        <div class="small">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Peminjam</th>
                <th>Email</th>
                <th>Ruangan</th>
                <th>Lokasi</th>
                <th>Keperluan</th>
                <th>Tgl Mulai</th>
                <th>Tgl Selesai</th>
                <th>Jam</th>
                <th>Jumlah</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 0; @endphp
            @foreach($bookings as $booking)
                @php $no++; @endphp
                <tr>
                    <td>{{ $no }}</td>
                    <td>{{ $booking->user->name ?? '-' }}</td>
                    <td>{{ $booking->user->email ?? '-' }}</td>
                    <td>{{ $booking->room->nama_room ?? '-' }}</td>
                    <td>{{ $booking->room->lokasi ?? '-' }}</td>
                    <td>{{ $booking->keperluan }}</td>
                    <td>{{ $booking->tanggal_mulai->format('d/m/Y') }}</td>
                    <td>{{ $booking->tanggal_selesai->format('d/m/Y') }}</td>
                    <td>{{ substr($booking->jam_mulai, 0, 5) }} - {{ substr($booking->jam_selesai, 0, 5) }}</td>
                    <td>{{ $booking->jumlah_peserta }}</td>
                    <td>{{ ucfirst($booking->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top:20px; font-size:11px; color:#666;">
        Laporan dihasilkan pada: {{ \Carbon\Carbon::now()->format('d M Y H:i') }}
    </div>
</body>
</html>
