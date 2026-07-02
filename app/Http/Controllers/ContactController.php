<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // Belum ada pengiriman email / penyimpanan ke database di sini.
        // Kalau mau, ini tempat paling gampang buat nambahin salah satu dari:
        //   Mail::to('halo@tepikopi.com')->send(new ContactMessageMail($request->all()));
        // atau simpan ke tabel `contact_messages` lewat model.

        return redirect('/contact')->with('success', 'Pesan kamu sudah terkirim. Terima kasih sudah menghubungi kami!');
    }
}