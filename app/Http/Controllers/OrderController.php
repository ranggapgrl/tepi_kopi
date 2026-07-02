<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Status pesanan yang tersedia, dipakai di form update & badge tampilan.
     */
    public const STATUSES = [
        'Menunggu Pembayaran',
        'Diproses',
        'Dikirim',
        'Selesai',
        'Dibatalkan',
    ];

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

    /**
     * ADMIN ONLY — /orders
     */
    public function index(Request $request)
    {
        $orders = Order::with('user')
            ->withCount('items')
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest()
            ->get();

        $statuses = self::STATUSES;

        return view('orders.index', compact('orders', 'statuses'));
    }

    /**
     * ADMIN ONLY — /orders/{order}
     */
    public function show(Order $order)
    {
        $order->load('user', 'items.product');

        $statuses = self::STATUSES;

        return view('orders.show', compact('order', 'statuses'));
    }

    /**
     * ADMIN ONLY — PUT /orders/{order}
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', self::STATUSES),
        ]);

        $order->update($validated);

        return redirect()->route('orders.show', $order)->with('success', 'Status pesanan berhasil diperbarui.');
    }
}