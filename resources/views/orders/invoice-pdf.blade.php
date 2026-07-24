<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $order->order_code }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1F150C;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }
        .top-row {
            width: 100%;
            margin-bottom: 24px;
        }
        .top-row td { vertical-align: top; }
        .brand {
            font-size: 22px;
            font-weight: bold;
            color: #412D15;
        }
        .brand-sub {
            font-size: 10px;
            color: #8a7f6f;
            margin-top: 2px;
        }
        .invoice-title {
            font-size: 16px;
            font-weight: bold;
            color: #1F150C;
            text-align: right;
        }
        .invoice-meta {
            text-align: right;
            font-size: 10px;
            color: #6b6259;
            margin-top: 4px;
        }
        .status-badge {
            display: inline-block;
            margin-top: 6px;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            background: #d1fae5;
            color: #065f46;
        }
        .info-table {
            width: 100%;
            margin-bottom: 22px;
        }
        .info-box {
            width: 48%;
            padding: 12px 14px;
            border: 1px solid #E1DCC9;
            border-radius: 6px;
            vertical-align: top;
        }
        .info-label {
            font-size: 9px;
            text-transform: uppercase;
            font-weight: bold;
            color: #8a7f6f;
            margin-bottom: 5px;
            display: block;
        }
        .info-box p {
            margin: 0 0 2px 0;
            font-size: 11px;
        }
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }
        table.items th {
            background: #412D15;
            color: #ffffff;
            text-align: left;
            padding: 8px 10px;
            font-size: 10px;
            text-transform: uppercase;
        }
        table.items td {
            padding: 8px 10px;
            border-bottom: 1px solid #EFEBE2;
            font-size: 10.5px;
        }
        table.items tr:nth-child(even) td {
            background: #FBF9F4;
        }
        .text-right { text-align: right; }
        .totals {
            width: 100%;
            margin-top: 10px;
        }
        .totals td {
            padding: 5px 10px;
            font-size: 11px;
        }
        .totals .grand-total td {
            border-top: 2px solid #412D15;
            font-size: 14px;
            font-weight: bold;
            color: #412D15;
            padding-top: 10px;
        }
        .footer-note {
            margin-top: 40px;
            padding-top: 12px;
            border-top: 1px solid #E1DCC9;
            font-size: 9px;
            color: #9a9083;
            text-align: center;
        }
    </style>
</head>
<body>

    <table class="top-row">
        <tr>
            <td style="width: 55%;">
                <div class="brand">Tepi Kopi</div>
                <div class="brand-sub">Kopi pilihan, diracik dengan cerita.</div>
            </td>
            <td style="width: 45%;">
                <div class="invoice-title">INVOICE / STRUK PESANAN</div>
                <div class="invoice-meta">
                    No. Pesanan: <strong>{{ $order->order_code }}</strong><br>
                    Tanggal: {{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB
                </div>
                <div style="text-align: right;">
                    <span class="status-badge">{{ $order->status }}</span>
                </div>
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td class="info-box">
                <span class="info-label">Ditagihkan Kepada</span>
                <p><strong>{{ $order->user->name ?? 'Tamu' }}</strong></p>
                <p>{{ $order->user->email ?? '-' }}</p>
                <p>{{ $order->shipping_phone }}</p>
            </td>
            <td style="width: 4%;"></td>
            <td class="info-box">
                <span class="info-label">Dikirim Ke</span>
                <p>{{ $order->shipping_address }}</p>
                @if($order->shipping_notes)
                    <p style="color:#8a7f6f; margin-top:4px;"><em>Catatan: {{ $order->shipping_notes }}</em></p>
                @endif
            </td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Varian</th>
                <th class="text-right">Harga</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product->name ?? $item->product_name ?? 'Produk Dihapus' }}</td>
                <td>{{ $item->variant->name ?? '-' }}</td>
                <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                <td class="text-right">{{ $item->quantity }}</td>
                <td class="text-right">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        @if($order->discount_amount > 0)
        <tr>
            <td class="text-right" style="width: 80%;">Diskon Kupon ({{ $order->coupon_code }})</td>
            <td class="text-right" style="width: 20%;">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
        </tr>
        @endif
        <tr class="grand-total">
            <td class="text-right" style="width: 80%;">Total Pembayaran</td>
            <td class="text-right" style="width: 20%;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
        </tr>
    </table>

    <p class="footer-note">
        Terima kasih sudah berbelanja di Tepi Kopi. Invoice ini dibuat otomatis oleh sistem dan sah tanpa tanda tangan.
    </p>

</body>
</html>