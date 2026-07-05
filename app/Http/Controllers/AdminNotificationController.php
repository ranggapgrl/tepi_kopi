<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class AdminNotificationController extends Controller
{
    public function read(string $id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        if (($notification->data['type'] ?? 'order') === 'low_stock') {
            return redirect()->route('products.index');
        }

        return redirect()->route('orders.show', $notification->data['order_id']);
    }

    public function readAll()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}