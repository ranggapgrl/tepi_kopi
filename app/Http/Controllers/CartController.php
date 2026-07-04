<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id() ?? 1]);
        $cartItems = CartItem::with(['product', 'variant'])->where('cart_id', $cart->id)->get();

        $subtotal = 0;
        foreach ($cartItems as $item) {
            // Kalau item punya varian, pakai harga varian. Kalau tidak, pakai harga produk.
            $price = $item->variant ? $item->variant->price : $item->product->price;
            $subtotal += $price * $item->quantity;
        }

        $tax = $subtotal * 0.11;
        $total = $subtotal + $tax;

        return view('cart.index', compact('cartItems', 'subtotal', 'tax', 'total'));
    }

    public function store(Request $request)
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id() ?? 1]);

        $variantId = $request->variant_id ?: null;

        $existingItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)
            ->where('variant_id', $variantId)
            ->first();

        if ($existingItem) {
            $existingItem->increment('quantity', $request->quantity ?? 1);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'variant_id' => $variantId,
                'quantity' => $request->quantity ?? 1
            ]);
        }

        // Total item (dijumlahkan quantity) yang sekarang ada di keranjang
        $totalItems = CartItem::where('cart_id', $cart->id)->sum('quantity');

        return back()->with(
            'success',
            "Kopi berhasil ditambahkan ke keranjang! Sekarang ada {$totalItems} item di keranjang."
        );
    }

    public function destroy(CartItem $cartItem)
    {
        $cart = Cart::where('user_id', Auth::id() ?? 1)->first();

        // Pastikan item ini benar-benar milik keranjang user yang sedang login
        abort_unless($cart && $cartItem->cart_id === $cart->id, 403);

        $cartItem->delete();

        return back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    public function update(Request $request, CartItem $cartItem)
{
    $cart = Cart::where('user_id', Auth::id() ?? 1)->first();
    abort_unless($cart && $cartItem->cart_id === $cart->id, 403);

    $validated = $request->validate([
        'quantity' => 'required|integer|min:1',
    ]);

    $availableStock = $cartItem->variant ? $cartItem->variant->stock : $cartItem->product->stock;

    if ($validated['quantity'] > $availableStock) {
        return back()->with('error', "Stok tidak cukup. Sisa stok: {$availableStock}.");
    }

    $cartItem->update(['quantity' => $validated['quantity']]);

    return back()->with('success', 'Jumlah item berhasil diperbarui.');
}
}