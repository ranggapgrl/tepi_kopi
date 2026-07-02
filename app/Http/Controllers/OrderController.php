<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $cart = Cart::where('user_id', Auth::id() ?? 1)->first();
        $cartItems = $cart ? $cart->items : [];

        if (count($cartItems) == 0) {
            return redirect('/cart')->with('error', 'Keranjang belanja kosong.');
        }

        // Hitung total
        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $totalPrice += ($item->product->price * $item->quantity);
        }
        $totalPrice = $totalPrice + ($totalPrice * 0.11); // Plus pajak

        // Buat Order
        $order = Order::create([
            'user_id' => Auth::id() ?? 1,
            'total_price' => $totalPrice,
            'status' => 'Menunggu Pembayaran'
        ]);

        // Pindahkan cart items ke order items
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price
            ]);

            // Kurangi stok
            $item->product->decrement('stock', $item->quantity);
        }

        // Kosongkan keranjang
        $cart->items()->delete();

        return redirect('/katalog')->with('success', 'Checkout berhasil! Silakan lakukan pembayaran.');
    }
}