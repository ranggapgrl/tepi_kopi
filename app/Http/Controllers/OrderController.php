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
        $cartItems = $cart ? $cart->items()->with(['product', 'variant'])->get() : collect();

        if (count($cartItems) == 0) {
            return redirect('/cart')->with('error', 'Keranjang belanja kosong.');
        }

        // Hitung total — pakai harga varian kalau item punya varian, kalau tidak pakai harga produk
        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $price = $item->variant ? $item->variant->price : $item->product->price;
            $totalPrice += ($price * $item->quantity);
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
            $price = $item->variant ? $item->variant->price : $item->product->price;

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'variant_id' => $item->variant_id,
                'quantity' => $item->quantity,
                'price' => $price
            ]);

            // Kurangi stok — stok varian kalau ada, kalau tidak stok produk
            if ($item->variant) {
                $item->variant->decrement('stock', $item->quantity);
            } else {
                $item->product->decrement('stock', $item->quantity);
            }
        }

        // Kosongkan keranjang
        $cart->items()->delete();

        return redirect('/katalog')->with('success', 'Checkout berhasil! Silakan lakukan pembayaran.');
    }

    /**
     * CUSTOMER — /my-orders
     * Riwayat pesanan milik user yang sedang login.
     */
    public function myOrders()
    {
        $orders = Order::withCount('items')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('orders.my-index', compact('orders'));
    }

    /**
     * CUSTOMER — /my-orders/{order}
     * Detail satu pesanan, hanya bisa diakses oleh pemiliknya.
     */
    public function myOrderShow(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);

        $order->load('items.product', 'items.variant');

        return view('orders.my-show', compact('order'));
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
        $order->load('user', 'items.product', 'items.variant');

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