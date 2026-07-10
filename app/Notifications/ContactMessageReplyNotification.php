<?php

namespace App\Notifications;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactMessageReplyNotification extends Notification
{
    use Queueable;

    public function __construct(protected ContactMessage $contactMessage)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Re: ' . $this->contactMessage->subject . ' — Tepi Kopi')
            ->greeting('Halo, ' . $this->contactMessage->name . '!')
            ->line('Terima kasih sudah menghubungi Tepi Kopi. Berikut balasan dari tim kami:')
            ->line($this->contactMessage->reply_message)
            ->line('---')
            ->line('Pesan kamu sebelumnya:')
            ->line('"' . $this->contactMessage->message . '"')
            ->line('Kalau masih ada pertanyaan, langsung balas email ini aja ya.');
    }
}