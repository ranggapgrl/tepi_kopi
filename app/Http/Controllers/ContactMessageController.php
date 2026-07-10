<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Notifications\ContactMessageReplyNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class ContactMessageController extends Controller
{
    /**
     * ADMIN ONLY — /pesan-kontak
     */
    public function index(Request $request)
    {
        $query = ContactMessage::latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $messages = $query->paginate(10)->withQueryString();

        $totalMessages = ContactMessage::count();
        $unreadCount = ContactMessage::whereNull('read_at')->count();

        return view('admin.contact-messages.index', compact('messages', 'totalMessages', 'unreadCount'));
    }

    /**
     * ADMIN ONLY — /pesan-kontak/{contactMessage}
     */
    public function show(ContactMessage $contactMessage)
    {
        if (! $contactMessage->read_at) {
            $contactMessage->update(['read_at' => now()]);
        }

        return view('admin.contact-messages.show', compact('contactMessage'));
    }

    /**
     * ADMIN ONLY — POST /pesan-kontak/{contactMessage}/reply
     * Kirim balasan email ke customer, dan simpan riwayat balasannya.
     */
    public function reply(Request $request, ContactMessage $contactMessage)
    {
        $validated = $request->validate([
            'reply_message' => 'required|string|max:2000',
        ]);

        $contactMessage->update([
            'reply_message' => $validated['reply_message'],
            'replied_at'    => now(),
            'replied_by'    => Auth::id(),
        ]);

        // Kirim ke email customer langsung (bukan user terdaftar, jadi
        // pakai on-demand notification lewat alamat emailnya).
        Notification::route('mail', $contactMessage->email)
            ->notify(new ContactMessageReplyNotification($contactMessage));

        return back()->with('success', 'Balasan berhasil dikirim ke ' . $contactMessage->email . '.');
    }

    /**
     * ADMIN ONLY — DELETE /pesan-kontak/{contactMessage}
     */
    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();

        return redirect()->route('contact-messages.index')->with('success', 'Pesan berhasil dihapus.');
    }
}