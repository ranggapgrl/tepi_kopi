<?php

return [
    'low_stock_threshold' => env('LOW_STOCK_THRESHOLD', 5),

    // BUGFIX: order yang macet di status "Menunggu Pembayaran" (mis. user
    // menutup popup Midtrans tanpa bayar, atau webhook gagal terkirim) akan
    // dibatalkan otomatis dan stoknya dikembalikan kalau sudah melewati
    // durasi ini. Lihat App\Console\Commands\ExpireStaleOrders.
    'unpaid_order_expiry_minutes' => env('UNPAID_ORDER_EXPIRY_MINUTES', 1440), // default 24 jam
];