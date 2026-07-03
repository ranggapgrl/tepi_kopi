<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;

// Halaman Utama
Route::get('/', [HomeController::class, 'index'])->name('home');

// Halaman Statis
Route::view('/about', 'about')->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store']);

// Katalog
Route::get('/katalog', [ProductController::class, 'index'])->name('katalog.index');
Route::get('/katalog/{product}', [ProductController::class, 'show'])->name('katalog.show');
Route::post('/katalog/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');

// Keranjang & Checkout
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'store'])->name('cart.add');
    Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');
});

// === AUTH ===
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->middleware('auth')->name('logout');
// === END AUTH ===

// Profil User (untuk semua user yang login, bukan cuma admin)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// Admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');

    // 'index()' pada ProductController dipakai untuk /katalog (publik),
    // jadi resource 'products' tidak boleh ikut generate route index bawaan.
    // Route GET /products diarahkan manual ke manage().
    Route::get('/products', [ProductController::class, 'manage'])->name('products.index');
    Route::resource('products', ProductController::class)->except(['index']);
    Route::delete('/products/{product}/images/{image}', [ProductController::class, 'destroyImage'])->name('products.images.destroy');

    Route::resource('categories', CategoryController::class);
    Route::resource('orders', OrderController::class)->only(['index', 'show', 'update']);
});

// Redirect dashboard default
Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('home');
})->middleware('auth')->name('dashboard');