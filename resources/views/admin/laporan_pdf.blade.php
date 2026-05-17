<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan TechPart</title>
    <style>
        /* CSS khusus untuk DomPDF biasanya menggunakan format klasik */
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            color: #1e293b;
            font-size: 24px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #64748b;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: bold;
            text-transform: uppercase;
            padding: 10px;
            border: 1px solid #cbd5e1;
            text-align: left;
        }
        td {
            padding: 10px;
            border: 1px solid #cbd5e1;
        }
        .total-row {
            background-color: #eff6ff;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 50px;
            text-align: right;
            font-size: 11px;
            color: #94a3b8;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Penjualan Selesai</h2>
        <p>Sistem Informasi E-Commerce TechPart</p>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y - H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">ID Pesanan</th>
                <th width="30%">Nama Pelanggan</th>
                <th width="20%">Tanggal Selesai</th>
                <th width="25%" class="text-right">Total Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $index => $order)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td><strong>#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</strong></td>
                    <td>{{ $order->user->name }}</td>
                    <td>{{ $order->updated_at->format('d/m/Y') }}</td>
                    <td class="text-right">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">Belum ada data penjualan yang selesai.</td>
                </tr>
            @endforelse
            
            <tr class="total-row">
                <td colspan="4" class="text-right" style="font-size: 14px;"><strong>TOTAL PENDAPATAN :</strong></td>
                <td class="text-right" style="font-size: 14px; color: #16a34a;">
                    <strong>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</strong>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini dihasilkan secara otomatis oleh sistem.</p>
    </div>

</body>
</html>