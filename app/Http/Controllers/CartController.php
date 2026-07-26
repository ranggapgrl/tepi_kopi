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
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
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
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'variant_id' => 'nullable|integer|exists:product_variants,id',
            'quantity'   => 'nullable|integer|min:1|max:100',
        ]);

        $quantity = $validated['quantity'] ?? 1;
        $variantId = $validated['variant_id'] ?? null;

        if ($variantId) {
            $variantBelongsToProduct = ProductVariant::where('id', $variantId)
                ->where('product_id', $validated['product_id'])
                ->exists();

            if (! $variantBelongsToProduct) {
                return $this->jsonError('Varian produk tidak valid.', $request);
            }
        }

        $result = DB::transaction(function () use ($validated, $variantId, $quantity) {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

            $existingItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $validated['product_id'])
                ->where('variant_id', $variantId)
                ->lockForUpdate()
                ->first();

            $newQuantity = $existingItem ? $existingItem->quantity + $quantity : $quantity;

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
            return $this->jsonError($result['error'], $request);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk ditambahkan ke keranjang.',
                'cart_count' => $result['total_items']
            ]);
        }

        return back()->with(
            'success',
            "Kopi berhasil ditambahkan ke keranjang! Sekarang ada {$result['total_items']} item di keranjang."
        );
    }

    /**
     * Helper to return JSON error responses.
     */
    private function jsonError(string $message, Request $request)
    {
        if ($request->wantsJson()) {
            return response()->json(['error' => $message], 422);
        }
        return back()->with('error', $message);
    }

    public function destroy(CartItem $cartItem)
    {
        $cart = Cart::where('user_id', Auth::id())->first();

        // Pastikan item ini benar-benar milik keranjang user yang sedang login
        abort_unless($cart && $cartItem->cart_id === $cart->id, 403);

        $cartItem->delete();

        return back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    public function update(Request $request, CartItem $cartItem)
{
    $cart = Cart::where('user_id', Auth::id())->first();
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