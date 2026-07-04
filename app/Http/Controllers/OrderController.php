<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        return DB::transaction(function () use ($cartItems, $cart) {
            // Kunci baris produk/varian yang mau dibeli, lalu cek ulang stok
            // TERKINI di dalam transaksi. Ini mencegah race condition kalau ada
            // 2 orang checkout barang stok terakhir secara bersamaan — transaksi
            // kedua akan menunggu transaksi pertama selesai, baru baca stok yang
            // sudah ter-update.
            $insufficient = [];
            $lockedItems = [];

            foreach ($cartItems as $item) {
                if ($item->variant_id) {
                    $variant = ProductVariant::whereKey($item->variant_id)->lockForUpdate()->first();

                    if (! $variant) {
                        $insufficient[] = ($item->product->name ?? 'Produk') . ' — varian sudah tidak tersedia';
                        continue;
                    }

                    if ($variant->stock < $item->quantity) {
                        $insufficient[] = "{$item->product->name} ({$variant->name}) — sisa stok {$variant->stock}, di keranjang {$item->quantity}";
                        continue;
                    }

                    $lockedItems[] = ['item' => $item, 'variant' => $variant, 'product' => null, 'price' => $variant->price];
                } else {
                    $product = Product::whereKey($item->product_id)->lockForUpdate()->first();

                    if (! $product) {
                        $insufficient[] = 'Produk sudah tidak tersedia';
                        continue;
                    }

                    if ($product->stock < $item->quantity) {
                        $insufficient[] = "{$product->name} — sisa stok {$product->stock}, di keranjang {$item->quantity}";
                        continue;
                    }

                    $lockedItems[] = ['item' => $item, 'variant' => null, 'product' => $product, 'price' => $product->price];
                }
            }

            if (! empty($insufficient)) {
                return redirect('/cart')->with(
                    'error',
                    'Stok tidak cukup untuk: ' . implode('; ', $insufficient) . '. Silakan sesuaikan jumlahnya di keranjang.'
                );
            }

            // Hitung total pakai harga yang baru saja dikunci (bukan dari cart items lama)
            $totalPrice = 0;
            foreach ($lockedItems as $entry) {
                $totalPrice += $entry['price'] * $entry['item']->quantity;
            }
            $totalPrice = $totalPrice + ($totalPrice * 0.11); // Plus pajak

            // Buat Order
            $order = Order::create([
                'user_id' => Auth::id() ?? 1,
                'total_price' => $totalPrice,
                'status' => 'Menunggu Pembayaran'
            ]);

            // Pindahkan cart items ke order items & kurangi stok yang sudah dikunci
            foreach ($lockedItems as $entry) {
                $item = $entry['item'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'quantity' => $item->quantity,
                    'price' => $entry['price']
                ]);

                if ($entry['variant']) {
                    $entry['variant']->decrement('stock', $item->quantity);
                } else {
                    $entry['product']->decrement('stock', $item->quantity);
                }
            }

            // Kosongkan keranjang
            $cart->items()->delete();

            return redirect('/katalog')->with('success', 'Checkout berhasil! Silakan lakukan pembayaran.');
        });
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
     * CUSTOMER — PATCH /my-orders/{order}/cancel
     * Customer membatalkan pesanan miliknya sendiri, selama masih "Menunggu Pembayaran".
     */
    public function cancel(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);

        if ($order->status !== 'Menunggu Pembayaran') {
            return back()->with('error', 'Pesanan ini sudah diproses dan tidak bisa dibatalkan lagi.');
        }

        // Kembalikan stok yang tadi dikurangi saat checkout
        $order->load('items');
        foreach ($order->items as $item) {
            if ($item->variant_id) {
                $item->variant?->increment('stock', $item->quantity);
            } else {
                $item->product?->increment('stock', $item->quantity);
            }
        }

        $order->update(['status' => 'Dibatalkan']);

        return redirect()->route('orders.myShow', $order)->with('success', 'Pesanan berhasil dibatalkan.');
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