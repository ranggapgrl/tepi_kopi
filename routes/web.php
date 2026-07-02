<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;

// 1. Halaman Awal (Landing Page keren ala Editorial yang baru kita buat)
Route::get('/', function () {
    return view('welcome');
});

// 2. Semua Route untuk Produk (Katalognya sekarang murni di /products)
Route::resource('products', ProductController::class);

// 3. Route untuk Keranjang Belanja
Route::get('/cart', [CartController::class, 'index']);
Route::post('/cart/add', [CartController::class, 'store']);

// 4. Route untuk Checkout
Route::post('/checkout', [OrderController::class, 'checkout']);

// 5. Route untuk Dashboard Admin
Route::get('/admin', [AdminController::class, 'index']);