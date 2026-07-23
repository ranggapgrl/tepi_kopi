<?php

namespace App\Console\Commands;

use App\Models\ActivityLog;
use App\Models\Order;
use App\Notifications\OrderStatusUpdatedNotification;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

/**
 * GAP FIX: sebelumnya pesanan berstatus "Dikirim" hanya bisa berpindah ke
 * "Selesai" lewat konfirmasi manual customer (OrderController::confirmReceived)
 * atau admin. Kalau customer lupa/tidak pernah konfirmasi, pesanan itu akan
 * nyangkut selamanya di "Dikirim" — padahal barangnya mungkin sudah lama
 * sampai. Command ini menandai pesanan seperti itu sebagai "Selesai" secara
 * otomatis setelah melewati config('tepikopi.auto_complete_shipped_after_days'),
 * persis seperti pola ExpireStaleOrders untuk pesanan yang macet di
 * "Menunggu Pembayaran".
 */
#[Signature('orders:auto-complete')]
#[Description('Tandai "Selesai" pesanan "Dikirim" yang sudah lama tidak dikonfirmasi customer')]
class AutoCompleteShippedOrders extends Command
{
    public function handle(): int
    {
        $days = config('tepikopi.auto_complete_shipped_after_days', 7);
        $cutoff = now()->subDays($days);

        $staleOrders = Order::where('status', 'Dikirim')
            ->whereNotNull('shipped_at')
            ->where('shipped_at', '<=', $cutoff)
            ->with('user')
            ->get();

        if ($staleOrders->isEmpty()) {
            $this->info('Tidak ada pesanan "Dikirim" yang perlu diselesaikan otomatis.');

            return self::SUCCESS;
        }

        $count = 0;

        foreach ($staleOrders as $order) {
            DB::transaction(function () use ($order) {
                // Kunci ulang order supaya tidak bentrok kalau di saat yang sama
                // customer sendiri baru saja mengklik "Pesanan Diterima".
                $lockedOrder = Order::whereKey($order->id)
                    ->where('status', 'Dikirim')
                    ->lockForUpdate()
                    ->first();

                if (! $lockedOrder) {
                    return; // sudah dikonfirmasi/diubah pihak lain, lewati
                }

                $lockedOrder->update([
                    'status' => 'Selesai',
                    'completed_at' => now(),
                ]);

                ActivityLog::record(
                    'Pesanan',
                    'update',
                    'Menyelesaikan otomatis pesanan #' . $lockedOrder->order_code
                        . ' karena tidak dikonfirmasi customer dalam batas waktu yang ditentukan.'
                );

                if ($order->user) {
                    Notification::send(
                        $order->user,
                        new OrderStatusUpdatedNotification($lockedOrder, 'Dikirim')
                    );
                }
            });

            $count++;
        }

        $this->info("{$count} pesanan berhasil diselesaikan otomatis.");

        return self::SUCCESS;
    }
}