<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// BUGFIX: sebelumnya tidak ada job terjadwal apa pun. Order yang macet di
// "Menunggu Pembayaran" (webhook Midtrans gagal sampai, atau customer
// menutup popup pembayaran) tidak pernah dibatalkan otomatis, sehingga
// stok yang sudah dikunci sejak order dibuat bisa tertahan selamanya.
Schedule::command('orders:expire-stale')->hourly();