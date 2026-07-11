<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusUpdatedNotification extends Notification
{
    use Queueable;

    public function __construct(protected Order $order, protected string $oldStatus)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Status Pesanan Diperbarui — ' . $this->order->order_code)
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Status pesanan kamu di Tepi Kopi baru saja diperbarui.')
            ->line('Kode Pesanan: ' . $this->order->order_code)
            ->line('Status sebelumnya: ' . $this->oldStatus)
            ->line('Status sekarang: ' . $this->order->status)
            ->action('Lihat Detail Pesanan', route('orders.myShow', $this->order));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'        => 'order_status',
            'order_id'    => $this->order->id,
            'order_code'  => $this->order->order_code,
            'old_status'  => $this->oldStatus,
            'new_status'  => $this->order->status,
            'message'     => 'Pesanan ' . $this->order->order_code . ' sekarang berstatus "' . $this->order->status . '".',
        ];
    }
}