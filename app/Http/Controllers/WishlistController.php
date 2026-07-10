<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * CUSTOMER — GET /wishlist
     * Menampilkan semua produk yang disimpan user yang sedang login.
     */
    public function index()
    {
        $wishlists = Wishlist::with(['product.category', 'product.variants'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('wishlist.index', compact('wishlists'));
    }

    /**
     * CUSTOMER — POST /wishlist/toggle/{product}
     * Dipanggil lewat AJAX dari tombol hati di katalog & detail produk.
     * Kalau produk belum ada di wishlist -> ditambahkan. Kalau sudah ada -> dihapus.
     */
    public function toggle(Request $request, Product $product)
    {
        $userId = Auth::id();

        $existing = Wishlist::where('user_id', $userId)
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $wishlisted = false;
        } else {
            Wishlist::create([
                'user_id'    => $userId,
                'product_id' => $product->id,
            ]);
            $wishlisted = true;
        }

        $wishlistCount = Wishlist::where('user_id', $userId)->count();

        if ($request->wantsJson()) {
            return response()->json([
                'wishlisted' => $wishlisted,
                'wishlist_count' => $wishlistCount,
            ]);
        }

        return back()->with('success', $wishlisted
            ? 'Produk ditambahkan ke wishlist.'
            : 'Produk dihapus dari wishlist.');
    }

    /**
     * CUSTOMER — DELETE /wishlist/{product}
     * Dipakai buat tombol "Hapus" di halaman wishlist itu sendiri.
     */
    public function destroy(Product $product)
    {
        Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->delete();

        return back()->with('success', 'Produk dihapus dari wishlist.');
    }
}