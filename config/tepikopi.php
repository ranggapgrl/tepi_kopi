<?php

return [
    'low_stock_threshold' => env('LOW_STOCK_THRESHOLD', 5),

    // BUGFIX: order yang macet di status "Menunggu Pembayaran" (mis. user
    // menutup popup Midtrans tanpa bayar, atau webhook gagal terkirim) akan
    // dibatalkan otomatis dan stoknya dikembalikan kalau sudah melewati
    // durasi ini. Lihat App\Console\Commands\ExpireStaleOrders.
    'unpaid_order_expiry_minutes' => env('UNPAID_ORDER_EXPIRY_MINUTES', 1440), // default 24 jam

    // Pesanan berstatus "Dikirim" yang tidak pernah dikonfirmasi diterima oleh
    // customer akan otomatis ditandai "Selesai" setelah durasi ini, supaya
    // pesanan tidak nyangkut selamanya kalau customer lupa/tidak konfirmasi.
    // Lihat App\Console\Commands\AutoCompleteShippedOrders.
    'auto_complete_shipped_after_days' => env('AUTO_COMPLETE_SHIPPED_AFTER_DAYS', 7),
];