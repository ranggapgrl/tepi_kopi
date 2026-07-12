<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\Wishlist;
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
        // Bagikan $cartCount & $wishlistCount ke layout utama supaya badge di
        // navbar (keranjang & wishlist) selalu update di semua halaman.
        View::composer('layouts.app', function ($view) {
            $cartCount = 0;
            $wishlistCount = 0;
            $unreadCustomerNotifications = collect();

            if (Auth::check()) {
                $cart = Cart::where('user_id', Auth::id())->first();
                $cartCount = $cart
                    ? CartItem::where('cart_id', $cart->id)->sum('quantity')
                    : 0;

                $wishlistCount = Wishlist::where('user_id', Auth::id())->count();

                $unreadCustomerNotifications = Auth::user()->unreadNotifications;
            }

            $view->with('cartCount', $cartCount);
            $view->with('wishlistCount', $wishlistCount);
            $view->with('unreadCustomerNotifications', $unreadCustomerNotifications);
        });

        // Bagikan $wishlistedProductIds ke semua halaman yang menampilkan kartu
        // produk (biar ikon hati langsung ke-render terisi/kosong sesuai
        // status wishlist user, tanpa nunggu request AJAX tambahan).
        View::composer(['katalog', 'product-detail', 'homepage', 'wishlist.index'], function ($view) {
            $wishlistedProductIds = Auth::check()
                ? Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray()
                : [];

            $view->with('wishlistedProductIds', $wishlistedProductIds);
        });

        // Bagikan $pendingOrdersCount & $unreadContactCount ke sidebar admin
        // supaya badge-nya selalu update di semua halaman admin.
        View::composer('admin.partials.sidebar', function ($view) {
            $view->with(
                'pendingOrdersCount',
                Order::where('status', 'Menunggu Pembayaran')->count()
            );
            $view->with(
                'unreadContactCount',
                ContactMessage::whereNull('read_at')->count()
            );
        });
    }
}