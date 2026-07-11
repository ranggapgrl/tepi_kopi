<?php

namespace App\Console\Commands;

use App\Models\ActivityLog;
use App\Models\Order;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * BUGFIX: sebelumnya tidak ada mekanisme apa pun untuk membereskan pesanan
 * yang macet di status "Menunggu Pembayaran" — misalnya karena customer
 * menutup popup Midtrans tanpa menyelesaikan pembayaran, atau notifikasi
 * webhook dari Midtrans gagal sampai ke server (jaringan, downtime, dll).
 * Tanpa ini, stok produk yang sudah dikurangi saat order dibuat akan
 * terkunci selamanya walau pembayaran tidak pernah terjadi.
 *
 * Command ini mencari order yang masih "Menunggu Pembayaran" dan sudah
 * lebih tua dari batas waktu di config('tepikopi.unpaid_order_expiry_minutes'),
 * lalu membatalkannya dan mengembalikan stoknya — persis seperti alur
 * pembatalan manual oleh customer (OrderController::cancel) dan pembatalan
 * otomatis lewat webhook Midtrans (OrderController::midtransCallback).
 */
#[Signature('orders:expire-stale')]
#[Description('Batalkan pesanan "Menunggu Pembayaran" yang sudah kedaluwarsa dan kembalikan stoknya')]
class ExpireStaleOrders extends Command
{
    public function handle(): int
    {
        $expiryMinutes = config('tepikopi.unpaid_order_expiry_minutes', 1440);
        $cutoff = now()->subMinutes($expiryMinutes);

        $staleOrders = Order::where('status', 'Menunggu Pembayaran')
            ->where('created_at', '<=', $cutoff)
            ->with('items')
            ->get();

        if ($staleOrders->isEmpty()) {
            $this->info('Tidak ada pesanan kedaluwarsa yang perlu dibatalkan.');

            return self::SUCCESS;
        }

        $count = 0;

        foreach ($staleOrders as $order) {
            DB::transaction(function () use ($order) {
                // Kunci ulang order supaya tidak bentrok kalau di saat yang sama
                // webhook Midtrans atau customer sendiri sedang membatalkannya.
                $lockedOrder = Order::whereKey($order->id)
                    ->where('status', 'Menunggu Pembayaran')
                    ->lockForUpdate()
                    ->first();

                if (! $lockedOrder) {
                    return; // sudah diproses pihak lain (race condition), lewati
                }

                foreach ($order->items as $item) {
                    if ($item->variant_id) {
                        $item->variant?->increment('stock', $item->quantity);
                    } else {
                        $item->product?->increment('stock', $item->quantity);
                    }
                }

                $lockedOrder->update(['status' => 'Dibatalkan']);

                ActivityLog::record(
                    'Pesanan',
                    'update',
                    'Membatalkan otomatis pesanan #' . $lockedOrder->order_code
                        . ' karena tidak dibayar dalam batas waktu yang ditentukan.'
                );
            });

            $count++;
        }

        $this->info("{$count} pesanan kedaluwarsa berhasil dibatalkan dan stoknya dikembalikan.");

        return self::SUCCESS;
    }
}