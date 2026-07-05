<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification as MidtransNotification;

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

    public function showCheckout()
    {
        $cart = Cart::where('user_id', Auth::id() ?? 1)->first();
        $cartItems = $cart ? $cart->items()->with(['product', 'variant'])->get() : collect();

        if ($cartItems->isEmpty()) {
            return redirect('/cart')->with('error', 'Keranjang belanja kosong.');
        }

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $price = $item->variant ? $item->variant->price : $item->product->price;
            $subtotal += $price * $item->quantity;
        }
        $tax = $subtotal * 0.11;
        $total = $subtotal + $tax;

        $lastOrder = Order::where('user_id', Auth::id())
            ->whereNotNull('shipping_address')
            ->latest()
            ->first();

        return view('checkout.index', compact('cartItems', 'subtotal', 'tax', 'total', 'lastOrder'));
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'shipping_address' => 'required|string|max:500',
            'shipping_phone'   => 'required|string|max:20',
            'shipping_notes'   => 'nullable|string|max:255',
        ], [
            'shipping_address.required' => 'Alamat pengiriman wajib diisi.',
            'shipping_phone.required'   => 'Nomor HP wajib diisi.',
        ]);

        $cart = Cart::where('user_id', Auth::id() ?? 1)->first();
        $cartItems = $cart ? $cart->items()->with(['product', 'variant'])->get() : collect();

        if (count($cartItems) == 0) {
            return redirect('/cart')->with('error', 'Keranjang belanja kosong.');
        }

        return DB::transaction(function () use ($cartItems, $cart, $validated) {
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

            $totalPrice = 0;
            foreach ($lockedItems as $entry) {
                $totalPrice += $entry['price'] * $entry['item']->quantity;
            }
            $totalPrice = $totalPrice + ($totalPrice * 0.11);

            $order = Order::create([
                'user_id' => Auth::id() ?? 1,
                'total_price' => $totalPrice,
                'status' => 'Menunggu Pembayaran',
                'shipping_address' => $validated['shipping_address'],
                'shipping_phone' => $validated['shipping_phone'],
                'shipping_notes' => $validated['shipping_notes'] ?? null,
            ]);

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

            $cart->items()->delete();

            // Beri tahu semua admin: notifikasi in-app (bell icon) + email.
            $order->load('user');
            $admins = User::where('role', 'admin')->get();
            if ($admins->isNotEmpty()) {
                Notification::send($admins, new NewOrderNotification($order));
            }

            return redirect()->route('orders.pay', $order);
        });
    }

    /**
     * CUSTOMER — /checkout/{order}/pay
     * Menampilkan halaman pembayaran Midtrans Snap.
     */
    public function pay(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);

        if ($order->status !== 'Menunggu Pembayaran') {
            return redirect()->route('orders.myShow', $order)->with('error', 'Pesanan ini sudah tidak bisa dibayar.');
        }

        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $order->load('items.product', 'items.variant', 'user');

        $itemDetails = [];
        $subtotal = 0;

        foreach ($order->items as $item) {
            $name = $item->product->name ?? 'Produk';
            if ($item->variant) {
                $name .= ' - ' . $item->variant->name;
            }

            $lineTotal = $item->price * $item->quantity;
            $subtotal += $lineTotal;

            $itemDetails[] = [
                'id' => (string) $item->id,
                'price' => (int) round($item->price),
                'quantity' => $item->quantity,
                'name' => substr($name, 0, 50),
            ];
        }

        $tax = (int) round($order->total_price - $subtotal);
        if ($tax > 0) {
            $itemDetails[] = [
                'id' => 'TAX',
                'price' => $tax,
                'quantity' => 1,
                'name' => 'Pajak (11%)',
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id' => 'TEPIKOPI-' . $order->id . '-' . now()->timestamp,
                'gross_amount' => (int) round($order->total_price),
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $order->user->name ?? 'Pelanggan',
                'email' => $order->user->email ?? 'guest@tepikopi.com',
                'phone' => $order->shipping_phone,
                'shipping_address' => [
                    'address' => $order->shipping_address,
                ],
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return view('orders.pay', compact('order', 'snapToken'));
    }

    /**
     * WEBHOOK — POST /midtrans/callback
     * Dipanggil server Midtrans untuk update status pembayaran.
     */
    public function midtransCallback(Request $request)
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $notif = new MidtransNotification();

        $transactionStatus = $notif->transaction_status;
        $fraudStatus = $notif->fraud_status;

        // order_id format: TEPIKOPI-{id}-{timestamp}
        $parts = explode('-', $notif->order_id);
        $orderId = $parts[1] ?? null;

        $order = Order::find($orderId);

        if (! $order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if (($transactionStatus == 'capture' && $fraudStatus == 'accept') || $transactionStatus == 'settlement') {
            $order->update(['status' => 'Diproses']);
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $order->load('items');
            foreach ($order->items as $item) {
                if ($item->variant_id) {
                    $item->variant?->increment('stock', $item->quantity);
                } else {
                    $item->product?->increment('stock', $item->quantity);
                }
            }
            $order->update(['status' => 'Dibatalkan']);
        }

        return response()->json(['message' => 'OK']);
    }

    public function myOrders()
    {
        $orders = Order::withCount('items')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('orders.my-index', compact('orders'));
    }

    public function myOrderShow(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);

        $order->load('items.product', 'items.variant');

        return view('orders.my-show', compact('order'));
    }

    public function cancel(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);

        if ($order->status !== 'Menunggu Pembayaran') {
            return back()->with('error', 'Pesanan ini sudah diproses dan tidak bisa dibatalkan lagi.');
        }

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

    public function show(Order $order)
    {
        $order->load('user', 'items.product', 'items.variant');

        $statuses = self::STATUSES;

        return view('orders.show', compact('order', 'statuses'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', self::STATUSES),
        ]);

        $order->update($validated);

        return redirect()->route('orders.show', $order)->with('success', 'Status pesanan berhasil diperbarui.');
    }
}