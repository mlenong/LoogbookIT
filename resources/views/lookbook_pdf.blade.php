<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Lookbook IT</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #777;
            padding: 6px;
        }

        th {
            background-color: #f2f2f2;
        }

        .badge {
            padding: 3px 6px;
            color: white;
            border-radius: 4px;
            font-size: 10px;
        }

        .bg-success {
            background-color: #198754;
        }

        .bg-warning {
            background-color: #ffc107;
            color: #000;
        }

        .bg-danger {
            background-color: #dc3545;
        }

        .bg-secondary {
            background-color: #6c757d;
        }

        .ttd-img {
            max-height: 40px;
        }
    </style>
</head>

<body>

    <h2 class="text-center">LAPORAN AKTIVITAS LOGBOOK IT</h2>
    <p class="text-right">Tanggal Cetak: {{ date('d F Y H:i:s') }}</p>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="10%">Petugas</th>
                <th width="10%">Kategori</th>
                <th width="15%">Item / Unit</th>
                <th width="20%">Aktivitas</th>
                <th width="10%">Status</th>
                <th width="15%">TTD</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $index => $log)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $log->user ? $log->user->name : '-' }}</td>
                    <td class="text-center">
                        <span class="badge bg-secondary">{{ $log->kategori }}</span>
                    </td>
                    <td>
                        @if($log->kategori == 'Pembersihan')
                            <strong>Unit:</strong> {{ $log->unit }}
                        @else
                            {{ $log->item }}
                        @endif
                    </td>
                    <td>{{ $log->aktivitas }}</td>
                    <td class="text-center">
                        @if($log->status == 'Selesai')
                            <span class="badge bg-success">Selesai</span>
                        @elseif($log->status == 'Proses')
                            <span class="badge bg-warning">Proses</span>
                        @else
                            <span class="badge bg-danger">{{ $log->status }}</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($log->kategori == 'Pembersihan' && !empty($log->ttd))
                            <img src="{{ $log->ttd }}" class="ttd-img">
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>