<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;

// 1. Halaman Utama
Route::get('/', function () { return view('welcome'); });

// 2. Route untuk Pelanggan (Lihat Katalog)
// Pastikan baris ini ada dan persis sama!
Route::get('/katalog', [ProductController::class, 'index']);

// 3. Route untuk Admin (Kelola Produk) - Dilindungi Middleware Admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('products', ProductController::class);
    Route::get('/admin', [AdminController::class, 'index']);
});

// 4. Route Keranjang & Checkout
Route::get('/cart', [CartController::class, 'index']);
Route::post('/cart/add', [CartController::class, 'store']);
Route::post('/checkout', [OrderController::class, 'checkout']);