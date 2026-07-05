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
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminNotificationController;

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
    Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::post('/cart/add', [CartController::class, 'store'])->name('cart.add');
    Route::get('/checkout', [OrderController::class, 'showCheckout'])->name('checkout.show');
    Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::get('/checkout/{order}/pay', [OrderController::class, 'pay'])->name('orders.pay'); // BARU
    Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
});

// === AUTH ===
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
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

// Pesanan Saya (customer, untuk semua user yang login)
Route::middleware('auth')->group(function () {
    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('orders.my');
    Route::get('/my-orders/{order}', [OrderController::class, 'myOrderShow'])->name('orders.myShow');
    Route::patch('/my-orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
});

// Admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::get('/products', [ProductController::class, 'manage'])->name('products.index');
    Route::resource('products', ProductController::class)->except(['index']);
    Route::delete('/products/{product}/images/{image}', [ProductController::class, 'destroyImage'])->name('products.images.destroy');

    Route::resource('categories', CategoryController::class);
    Route::resource('orders', OrderController::class)->only(['index', 'show', 'update']);

    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');

    Route::resource('users', UserController::class)->except(['show']);

    // Notifikasi admin (pesanan baru)
    Route::post('/notifications/read-all', [AdminNotificationController::class, 'readAll'])->name('notifications.readAll');
    Route::post('/notifications/{id}/read', [AdminNotificationController::class, 'read'])->name('notifications.read');
});

// Redirect dashboard default
Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('home');
})->middleware('auth')->name('dashboard');

// Webhook Midtrans — tanpa middleware auth, karena dipanggil server Midtrans, bukan browser user
Route::post('/midtrans/callback', [OrderController::class, 'midtransCallback'])->name('midtrans.callback'); // BARU