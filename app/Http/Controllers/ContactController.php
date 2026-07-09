<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\User;
use App\Notifications\ContactMessageNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // 1. Simpan ke database supaya pesan tidak hilang & bisa dilihat admin nanti.
        $contactMessage = ContactMessage::create($validated);

        // 2. Kirim notifikasi (in-app bell icon + email) ke semua admin,
        // konsisten dengan alur NewOrderNotification & LowStockNotification.
        $admins = User::where('role', 'admin')->get();
        if ($admins->isNotEmpty()) {
            Notification::send($admins, new ContactMessageNotification($contactMessage));
        }

        return redirect('/contact')->with('success', 'Pesan kamu sudah terkirim. Terima kasih sudah menghubungi kami!');
    }
}