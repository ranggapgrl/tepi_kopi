<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification
{
    use Queueable;

    public function __construct(protected Order $order)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pesanan Baru Masuk — ' . $this->order->order_code)
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Ada pesanan baru masuk di Tepi Kopi.')
            ->line('Kode Pesanan: ' . $this->order->order_code)
            ->line('Pelanggan: ' . ($this->order->user->name ?? '-'))
            ->line('Total: Rp' . number_format($this->order->total_price, 0, ',', '.'))
            ->action('Lihat Detail Pesanan', route('orders.show', $this->order));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'order_id'      => $this->order->id,
            'order_code'    => $this->order->order_code,
            'customer_name' => $this->order->user->name ?? 'Pelanggan',
            'message'       => 'Pesanan baru ' . $this->order->order_code . ' dari ' . ($this->order->user->name ?? 'pelanggan') . '.',
        ];
    }
}