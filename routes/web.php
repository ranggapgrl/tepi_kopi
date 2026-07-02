<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Auth\AuthController;

// 1. Halaman Utama
Route::get('/', [HomeController::class, 'index']);

// Halaman Tentang & Kontak
Route::view('/about', 'about');
Route::get('/contact', [ContactController::class, 'index']);
Route::post('/contact', [ContactController::class, 'store']);

// Login, Register, Logout
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister']);
    Route::post('/register', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

// 2. Route untuk Pelanggan (Lihat Katalog)
// Pastikan baris ini ada dan persis sama!
Route::get('/katalog', [ProductController::class, 'index']);
Route::get('/katalog/{product}', [ProductController::class, 'show']);

// 3. Route untuk Admin (Kelola Produk) - Dilindungi Middleware Admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/products', [ProductController::class, 'manage'])->name('products.index');
    Route::resource('products', ProductController::class)->except(['index']);
    Route::get('/admin', [AdminController::class, 'index']);
});

// 4. Route Keranjang & Checkout
Route::get('/cart', [CartController::class, 'index']);
Route::post('/cart/add', [CartController::class, 'store']);
Route::post('/checkout', [OrderController::class, 'checkout']);