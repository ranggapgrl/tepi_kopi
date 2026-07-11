<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        // BUGFIX: sebelumnya product_id/variant_id/quantity dipakai langsung dari
        // request tanpa validasi. Produk yang tidak ada bisa membuat halaman cart
        // error (null pointer), dan quantity 0/negatif/non-integer bisa dipakai
        // untuk memanipulasi total harga saat checkout. Sekarang divalidasi dulu.
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'variant_id' => 'nullable|integer|exists:product_variants,id',
            'quantity'   => 'nullable|integer|min:1|max:100',
        ]);

        $quantity = $validated['quantity'] ?? 1;
        $variantId = $validated['variant_id'] ?? null;

        // BUGFIX: pastikan variant_id yang dikirim benar-benar milik product_id
        // yang dikirim, supaya orang tidak bisa mencampur produk A dengan
        // harga/stok varian produk B lewat request yang dipalsukan.
        if ($variantId) {
            $variantBelongsToProduct = ProductVariant::where('id', $variantId)
                ->where('product_id', $validated['product_id'])
                ->exists();

            if (! $variantBelongsToProduct) {
                return back()->with('error', 'Varian produk tidak valid.');
            }
        }

        $result = DB::transaction(function () use ($validated, $variantId, $quantity) {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id() ?? 1]);

            // Lock baris yang relevan supaya dua request "tambah ke keranjang"
            // yang datang bersamaan (mis. double-klik) tidak membuat baris
            // cart_items duplikat untuk produk/varian yang sama.
            $existingItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $validated['product_id'])
                ->where('variant_id', $variantId)
                ->lockForUpdate()
                ->first();

            $newQuantity = $existingItem ? $existingItem->quantity + $quantity : $quantity;

            // BUGFIX: sebelumnya stok baru dicek saat checkout. Sekarang dicek
            // juga saat ditambahkan ke keranjang supaya user langsung tahu kalau
            // stoknya tidak cukup, bukan baru gagal di halaman checkout.
            if ($variantId) {
                $variant = ProductVariant::whereKey($variantId)->lockForUpdate()->first();
                $availableStock = $variant->stock;
            } else {
                $product = Product::whereKey($validated['product_id'])->lockForUpdate()->first();
                $availableStock = $product->stock;
            }

            if ($newQuantity > $availableStock) {
                return ['error' => "Stok tidak cukup. Sisa stok: {$availableStock}."];
            }

            if ($existingItem) {
                $existingItem->update(['quantity' => $newQuantity]);
            } else {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $validated['product_id'],
                    'variant_id' => $variantId,
                    'quantity' => $quantity,
                ]);
            }

            $totalItems = CartItem::where('cart_id', $cart->id)->sum('quantity');

            return ['total_items' => $totalItems];
        });

        if (isset($result['error'])) {
            return back()->with('error', $result['error']);
        }

        return back()->with(
            'success',
            "Kopi berhasil ditambahkan ke keranjang! Sekarang ada {$result['total_items']} item di keranjang."
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