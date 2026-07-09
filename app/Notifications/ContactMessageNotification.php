<?php

namespace App\Notifications;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactMessageNotification extends Notification
{
    use Queueable;

    public function __construct(protected ContactMessage $contactMessage)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pesan Baru dari Contact Form — ' . $this->contactMessage->subject)
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Ada pesan baru masuk lewat form kontak Tepi Kopi.')
            ->line('Dari: ' . $this->contactMessage->name . ' (' . $this->contactMessage->email . ')')
            ->line('Subjek: ' . $this->contactMessage->subject)
            ->line('Pesan: ' . $this->contactMessage->message);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'               => 'contact',
            'contact_message_id' => $this->contactMessage->id,
            'sender_name'        => $this->contactMessage->name,
            'subject'            => $this->contactMessage->subject,
            'message'            => 'Pesan baru dari ' . $this->contactMessage->name . ': "' . \Illuminate\Support\Str::limit($this->contactMessage->subject, 40) . '"',
        ];
    }
}