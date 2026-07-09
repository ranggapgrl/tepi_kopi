<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class AdminNotificationController extends Controller
{
    public function read(string $id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        $type = $notification->data['type'] ?? 'order';

        if ($type === 'low_stock') {
            return redirect()->route('products.index');
        }

        if ($type === 'contact') {
            return redirect()->route('contact-messages.show', $notification->data['contact_message_id']);
        }

        return redirect()->route('orders.show', $notification->data['order_id']);
    }

    public function readAll()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}