<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil 4 produk terbaru untuk ditampilkan di halaman Home.
        // Kalau kamu punya kolom lain (mis. is_featured), boleh diganti
        // jadi: Product::where('is_featured', true)->take(4)->get();
        $products = Product::latest()->take(4)->get();

        return view('welcome', compact('products'));
    }
}