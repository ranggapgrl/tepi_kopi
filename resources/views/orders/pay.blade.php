<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - Tepi Kopi</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
</head>
<body class="bg-amber-50 font-sans antialiased text-amber-950 min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-md bg-white rounded-3xl shadow-lg p-8 text-center">
        <a href="/" class="text-2xl font-black text-amber-900 flex items-center justify-center gap-2 mb-8">
            <i class="fa-solid fa-mug-hot text-amber-700"></i> TepiKopi.
        </a>

        <h1 class="text-2xl font-extrabold mb-2">Selesaikan Pembayaran</h1>
        <p class="text-amber-800/70 font-medium mb-6">Pesanan #{{ $order->id }}</p>

        <div class="bg-amber-50 rounded-xl p-4 mb-6 text-left">
            <div class="flex justify-between text-sm font-bold text-amber-900">
                <span>Total Pembayaran</span>
                <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>
        </div>

        <button id="pay-button" class="w-full bg-amber-800 hover:bg-amber-900 text-white font-bold py-3.5 rounded-xl transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
            <span>Bayar Sekarang</span>
            <i class="fa-solid fa-credit-card"></i>
        </button>

        <a href="{{ route('orders.myShow', $order) }}" class="block mt-4 text-sm text-amber-700 font-bold hover:text-amber-950">
            Bayar nanti, lihat detail pesanan
        </a>
    </div>

    <script>
        function bayarSekarang() {
            snap.pay('{{ $snapToken }}', {
                onSuccess: function () {
                    window.location.href = "{{ route('orders.myShow', $order) }}";
                },
                onPending: function () {
                    window.location.href = "{{ route('orders.myShow', $order) }}";
                },
                onError: function () {
                    alert('Pembayaran gagal, silakan coba lagi.');
                }
            });
        }

        document.getElementById('pay-button').addEventListener('click', bayarSekarang);
        window.addEventListener('load', bayarSekarang);
    </script>
</body>
</html>