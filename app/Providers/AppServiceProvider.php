<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Bagikan $cartCount ke layout utama supaya badge keranjang
        // di navbar selalu update di semua halaman.
        View::composer('layouts.app', function ($view) {
            $cartCount = 0;

            if (Auth::check()) {
                $cart = Cart::where('user_id', Auth::id())->first();
                $cartCount = $cart
                    ? CartItem::where('cart_id', $cart->id)->sum('quantity')
                    : 0;
            }

            $view->with('cartCount', $cartCount);
        });

        // Bagikan $pendingOrdersCount ke sidebar admin supaya badge notifikasi
        // "Pesanan Masuk" selalu update di semua halaman admin.
        View::composer('admin.partials.sidebar', function ($view) {
            $view->with(
                'pendingOrdersCount',
                Order::where('status', 'Menunggu Pembayaran')->count()
            );
        });
    }
}