<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan Tepi Kopi</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1F150C;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }
        .header {
            border-bottom: 3px solid #412D15;
            padding-bottom: 14px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 20px;
            margin: 0 0 4px 0;
            color: #412D15;
        }
        .header p {
            margin: 0;
            color: #6b6259;
            font-size: 11px;
        }
        .stats {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 22px;
        }
        .stats td {
            width: 25%;
            padding: 10px 12px;
            border: 1px solid #E1DCC9;
            vertical-align: top;
        }
        .stats .label {
            font-size: 9px;
            text-transform: uppercase;
            color: #8a7f6f;
            font-weight: bold;
            display: block;
            margin-bottom: 4px;
        }
        .stats .value {
            font-size: 14px;
            font-weight: bold;
            color: #1F150C;
        }
        h2.section-title {
            font-size: 13px;
            color: #412D15;
            border-bottom: 1px solid #E1DCC9;
            padding-bottom: 6px;
            margin: 22px 0 10px 0;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        table.data-table th {
            background: #412D15;
            color: #ffffff;
            text-align: left;
            padding: 7px 8px;
            font-size: 10px;
            text-transform: uppercase;
        }
        table.data-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #EFEBE2;
            font-size: 10px;
        }
        table.data-table tr:nth-child(even) td {
            background: #FBF9F4;
        }
        .badge {
            padding: 2px 7px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            display: inline-block;
        }
        .badge-selesai { background: #d1fae5; color: #065f46; }
        .badge-diproses { background: #dbeafe; color: #1e40af; }
        .badge-dikirim { background: #e0e7ff; color: #3730a3; }
        .badge-menunggu { background: #fef3c7; color: #92400e; }
        .badge-dibatalkan { background: #fee2e2; color: #991b1b; }
        .footer-note {
            margin-top: 24px;
            padding-top: 10px;
            border-top: 1px solid #E1DCC9;
            font-size: 9px;
            color: #9a9083;
            text-align: center;
        }
        .text-right { text-align: right; }
        .empty-note {
            padding: 14px;
            text-align: center;
            color: #9a9083;
            font-style: italic;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Penjualan — Tepi Kopi</h1>
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}</p>
        <p>Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }} WIB</p>
    </div>

    {{-- Kartu Statistik --}}
    <table class="stats">
        <tr>
            <td>
                <span class="label">Total Pendapatan</span>
                <span class="value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
            </td>
            <td>
                <span class="label">Total Pesanan</span>
                <span class="value">{{ $totalOrders }} Order</span>
            </td>
            <td>
                <span class="label">Item Terjual</span>
                <span class="value">{{ $totalItemsSold }} Pcs</span>
            </td>
            <td>
                <span class="label">Rata-rata / Order</span>
                <span class="value">Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</span>
            </td>
        </tr>
    </table>

    {{-- Produk Terlaris --}}
    <h2 class="section-title">Produk Terlaris</h2>
    @if($topProducts->isEmpty())
        <p class="empty-note">Tidak ada penjualan produk pada rentang tanggal ini.</p>
    @else
        <table class="data-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Jumlah Terjual</th>
                    <th class="text-right">Total Penjualan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topProducts as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'Produk Dihapus' }}</td>
                    <td>{{ $item->total_qty }} pcs</td>
                    <td class="text-right">Rp {{ number_format($item->total_sales, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- Daftar Pesanan --}}
    <h2 class="section-title">Daftar Pesanan ({{ $orders->count() }} pesanan)</h2>
    @if($orders->isEmpty())
        <p class="empty-note">Tidak ada pesanan pada rentang tanggal ini.</p>
    @else
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID Order</th>
                    <th>Pelanggan</th>
                    <th>Tanggal</th>
                    <th>Jumlah Item</th>
                    <th class="text-right">Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                @php
                    $badgeClass = match($order->status) {
                        'Selesai' => 'badge-selesai',
                        'Diproses' => 'badge-diproses',
                        'Dikirim' => 'badge-dikirim',
                        'Menunggu Pembayaran' => 'badge-menunggu',
                        'Dibatalkan' => 'badge-dibatalkan',
                        default => '',
                    };
                @endphp
                <tr>
                    <td>#ORD-{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $order->user->name ?? 'Tamu' }}</td>
                    <td>{{ $order->created_at->translatedFormat('d M Y') }}</td>
                    <td>{{ $order->items_count }}</td>
                    <td class="text-right">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td><span class="badge {{ $badgeClass }}">{{ $order->status }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <p class="footer-note">Laporan ini dibuat otomatis oleh sistem Tepi Kopi. Pesanan berstatus "Dibatalkan" tidak dihitung dalam total pendapatan.</p>

</body>
</html>