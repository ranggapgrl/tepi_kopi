<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class CustomerNotificationController extends Controller
{
    public function read(string $id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect()->route('orders.myShow', $notification->data['order_id']);
    }

    public function readAll()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}