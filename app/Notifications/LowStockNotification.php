<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected string $itemName,
        protected int $stock,
        protected ?int $productId = null,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Stok Menipis — ' . $this->itemName)
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Stok salah satu produk di Tepi Kopi sudah menipis:')
            ->line($this->itemName . ' — sisa stok: ' . $this->stock)
            ->line('Yuk segera restock supaya tidak kehabisan.')
            ->action('Kelola Produk', route('products.index'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'       => 'low_stock',
            'product_id' => $this->productId,
            'item_name'  => $this->itemName,
            'stock'      => $this->stock,
            'message'    => "Stok \"{$this->itemName}\" tinggal {$this->stock}.",
        ];
    }
}