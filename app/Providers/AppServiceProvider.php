<?php

namespace App\Providers;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Bagikan jumlah item keranjang ke semua view (dipakai untuk badge di navbar)
        View::composer('layouts.app', function ($view) {
            $cart = Cart::where('user_id', Auth::id() ?? 1)->first();
            $cartCount = $cart ? (int) $cart->items()->sum('quantity') : 0;

            $view->with('cartCount', $cartCount);
        });
    }
}