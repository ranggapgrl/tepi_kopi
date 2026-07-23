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
use App\Notifications\LowStockNotification;

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

    /**
     * Transisi status yang diperbolehkan untuk admin (dari => [tujuan yang valid]).
     * Mencegah loncat status (mis. "Menunggu Pembayaran" langsung ke "Selesai")
     * atau mundur status (mis. "Selesai" balik ke "Diproses") lewat dashboard admin.
     */
    private const ALLOWED_TRANSITIONS = [
        'Menunggu Pembayaran' => ['Diproses', 'Dibatalkan'],
        'Diproses'            => ['Dikirim', 'Dibatalkan'],
        'Dikirim'             => ['Selesai', 'Dibatalkan'],
        'Selesai'             => [],
        'Dibatalkan'          => [],
    ];

    /**
     * Dipakai dari view (resources/views/orders/show.blade.php) untuk tahu
     * status mana saja yang boleh dipilih admin dari status saat ini.
     */
    public static function allowedTransitionsFrom(string $status): array
    {
        return self::ALLOWED_TRANSITIONS[$status] ?? [];
    }

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

        $addresses = Auth::user()->addresses;

        // Dibutuhkan di view agar Snap.js bisa dimuat langsung di halaman checkout,
        // supaya popup pembayaran muncul di sini tanpa redirect ke halaman terpisah.
        $midtransClientKey = config('services.midtrans.client_key');
        $midtransIsProduction = (bool) config('services.midtrans.is_production');

        return view('checkout.index', compact(
            'cartItems', 'subtotal', 'tax', 'total', 'lastOrder', 'addresses',
            'midtransClientKey', 'midtransIsProduction'
        ));
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
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Keranjang belanja kosong.'], 422);
            }
            return redirect('/cart')->with('error', 'Keranjang belanja kosong.');
        }

        $result = DB::transaction(function () use ($cartItems, $cart, $validated) {
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
                return [
                    'error' => 'Stok tidak cukup untuk: ' . implode('; ', $insufficient) . '. Silakan sesuaikan jumlahnya di keranjang.',
                ];
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

            $lowStockThreshold = config('tepikopi.low_stock_threshold', 5);
            $lowStockAlerts = [];

            foreach ($lockedItems as $entry) {
                $item = $entry['item'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    // Snapshot nama produk saat pesanan dibuat, supaya riwayat
                    // pesanan tetap terbaca walau produknya nanti dihapus.
                    'product_name' => $item->product->name ?? 'Produk',
                    'variant_id' => $item->variant_id,
                    'quantity' => $item->quantity,
                    'price' => $entry['price']
                ]);

                if ($entry['variant']) {
                    $stockBefore = $entry['variant']->stock;
                    $entry['variant']->decrement('stock', $item->quantity);
                    $stockAfter = $stockBefore - $item->quantity;
                    $itemName = ($item->product->name ?? 'Produk') . ' — ' . $entry['variant']->name;
                    $productId = $item->product_id;
                } else {
                    $stockBefore = $entry['product']->stock;
                    $entry['product']->decrement('stock', $item->quantity);
                    $stockAfter = $stockBefore - $item->quantity;
                    $itemName = $entry['product']->name;
                    $productId = $entry['product']->id;
                }

                // Hanya catat begitu stok BARU SAJA turun melewati batas gara-gara
                // pesanan ini, supaya admin tidak dibanjiri notifikasi berulang
                // untuk produk yang memang sudah lama menipis.
                if ($stockAfter <= $lowStockThreshold && $stockBefore > $lowStockThreshold) {
                    $lowStockAlerts[] = ['name' => $itemName, 'stock' => $stockAfter, 'product_id' => $productId];
                }
            }

            $cart->items()->delete();

            // Beri tahu semua admin: notifikasi in-app (bell icon) + email.
            $order->load('user');
            $admins = User::where('role', 'admin')->get();
            if ($admins->isNotEmpty()) {
                Notification::send($admins, new NewOrderNotification($order));

                foreach ($lowStockAlerts as $alert) {
                    Notification::send(
                        $admins,
                        new LowStockNotification($alert['name'], $alert['stock'], $alert['product_id'])
                    );
                }
            }

            return ['order' => $order];
        });

        if (isset($result['error'])) {
            if ($request->wantsJson()) {
                return response()->json(['message' => $result['error']], 422);
            }
            return redirect('/cart')->with('error', $result['error']);
        }

        $order = $result['order'];

        // AJAX buat token Snap dan
        // kembalikan sebagai JSON supaya popup pembayaran bisa langsung dibuka
        // di halaman checkout, tanpa redirect ke halaman lain.
        if ($request->wantsJson()) {
            $snapToken = $this->generateSnapToken($order);

            return response()->json([
                'snap_token' => $snapToken,
                'order_id' => $order->id,
                'redirect_url' => route('orders.myShow', $order),
            ]);
        }

        return redirect()->route('orders.pay', $order);
    }

    /**
     * CUSTOMER — /checkout/{order}/pay
     * Menampilkan halaman pembayaran Midtrans Snap.
     * Dipakai sebagai fallback (mis. non-JS, atau kembali lewat link lama);
     * alur utama sekarang membuka Snap langsung dari halaman checkout.
     */
    public function pay(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);

        if ($order->status !== 'Menunggu Pembayaran') {
            return redirect()->route('orders.myShow', $order)->with('error', 'Pesanan ini sudah tidak bisa dibayar.');
        }

        $snapToken = $this->generateSnapToken($order);

        return view('orders.pay', compact('order', 'snapToken'));
    }

    /**
     * Bangun parameter transaksi Midtrans dan ambil Snap token untuk sebuah order.
     * Dipakai oleh checkout() (AJAX, langsung di halaman checkout) dan
     * pay() (halaman fallback terpisah).
     */
    private function generateSnapToken(Order $order): string
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $order->load('items.product', 'items.variant', 'user');

        // Reuse order_id Midtrans yang sudah pernah dibuat untuk order ini,
        // supaya tidak tercipta transaksi baru tiap kali user buka ulang halaman bayar.
        $midtransOrderId = $order->midtrans_order_id ?: 'TEPIKOPI-' . $order->id . '-' . now()->timestamp;

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
                'order_id' => $midtransOrderId,
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

        if (! $order->midtrans_order_id) {
            $order->update(['midtrans_order_id' => $midtransOrderId]);
        }

        return $snapToken;
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
        $midtransOrderId = $notif->order_id;

        \Log::info('Midtrans callback diterima', [
            'midtrans_order_id' => $midtransOrderId,
            'transaction_status' => $transactionStatus,
            'fraud_status' => $fraudStatus,
        ]);

        // Cari order berdasarkan midtrans_order_id (akurat), fallback ke cara lama
        // (parsing dari string) untuk order lama sebelum kolom ini ada.
        $order = Order::where('midtrans_order_id', $midtransOrderId)->first();

        if (! $order) {
            $parts = explode('-', $midtransOrderId);
            $orderId = $parts[1] ?? null;
            $order = Order::find($orderId);
        }

        if (! $order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $this->applyMidtransTransactionStatus($order, $transactionStatus, $fraudStatus, $notif->payment_type);

        return response()->json(['message' => 'OK']);
    }

    /**
     * CUSTOMER — POST /orders/{order}/verify-status
     * Dipanggil dari frontend (callback onSuccess/onPending Snap.js) segera
     * setelah pembayaran selesai di browser customer.
     *
     * BUGFIX: sebelumnya update status order 100% bergantung pada webhook
     * midtransCallback(). Di local development webhook itu tidak akan pernah
     * sampai karena Midtrans tidak bisa mengakses localhost, dan bahkan di
     * production webhook bisa telat/gagal terkirim. Endpoint ini melakukan
     * pengecekan aktif (bukan pasif menunggu) ke Transaction Status API
     * Midtrans begitu customer selesai bayar, supaya status order langsung
     * ter-update tanpa perlu menunggu webhook.
     */
    public function verifyStatus(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);

        if (! $order->midtrans_order_id) {
            return response()->json(['status' => $order->status]);
        }

        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        try {
            $status = \Midtrans\Transaction::status($order->midtrans_order_id);
        } catch (\Exception $e) {
            \Log::warning('Gagal cek status transaksi Midtrans', [
                'order_id' => $order->id,
                'midtrans_order_id' => $order->midtrans_order_id,
                'error' => $e->getMessage(),
            ]);

            return response()->json(['status' => $order->status]);
        }

        $this->applyMidtransTransactionStatus(
            $order,
            $status->transaction_status ?? null,
            $status->fraud_status ?? null,
            $status->payment_type ?? null
        );

        return response()->json(['status' => $order->fresh()->status]);
    }

    /**
     * Terapkan hasil status transaksi Midtrans (dari webhook ATAU dari
     * pengecekan manual verifyStatus()) ke order. Idempoten: aman dipanggil
     * berkali-kali untuk order yang sama.
     */
    private function applyMidtransTransactionStatus(Order $order, ?string $transactionStatus, ?string $fraudStatus, ?string $paymentType): void
    {
        // Idempotensi: kalau order sudah tidak lagi "Menunggu Pembayaran", abaikan.
        // Mencegah notifikasi/pengecekan telat atau duplikat (mis. dari percobaan
        // bayar lama yang akhirnya expire) merusak status yang sudah final, dan
        // mencegah stok dikembalikan keliru.
        if ($order->status !== 'Menunggu Pembayaran') {
            return;
        }

        $oldStatus = $order->status;

        if (($transactionStatus == 'capture' && $fraudStatus == 'accept') || $transactionStatus == 'settlement') {
            $order->update([
                'status' => 'Diproses',
                'payment_type' => $paymentType,
                'paid_at' => now(),
            ]);
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

        // BUGFIX: sebelumnya customer tidak pernah dikabari saat status order
        // berubah lewat webhook (pembayaran sukses -> "Diproses", atau
        // gagal/kadaluwarsa -> "Dibatalkan"). Notifikasi cuma dikirim kalau
        // admin yang ubah status manual dari dashboard. Sekarang disamakan.
        if ($order->user && $order->status !== $oldStatus) {
            Notification::send($order->user, new \App\Notifications\OrderStatusUpdatedNotification($order, $oldStatus));
        }
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

        // Produk mana saja (dari pesanan ini) yang sudah pernah direview user,
        // dipakai view untuk memutuskan tampilkan form ulasan atau badge "sudah direview".
        $reviewedProductIds = \App\Models\Review::where('user_id', Auth::id())
            ->whereIn('product_id', $order->items->pluck('product_id')->filter())
            ->pluck('product_id');

        return view('orders.my-show', compact('order', 'reviewedProductIds'));
    }


    /**
     * CUSTOMER — GET /my-orders/{order}/invoice
     * Unduh invoice/struk pesanan dalam bentuk PDF. Cuma bisa diakses
     * pemilik pesanan, dan cuma untuk pesanan yang sudah dibayar
     * (bukan "Menunggu Pembayaran" atau "Dibatalkan").
     */
    public function downloadInvoice(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);

        if (in_array($order->status, ['Menunggu Pembayaran', 'Dibatalkan'])) {
            return back()->with('error', 'Invoice hanya tersedia untuk pesanan yang sudah dibayar.');
        }

        $order->load('items.product', 'items.variant', 'user');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('orders.invoice-pdf', compact('order'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('invoice-' . $order->order_code . '.pdf');
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

    /**
     * CUSTOMER — PATCH /my-orders/{order}/confirm
     * Customer menandai pesanan sebagai diterima. Hanya bisa dilakukan
     * kalau status masih "Dikirim", supaya tidak ada yang loncat status
     * (mis. dari "Diproses" langsung "Selesai").
     */
    public function confirmReceived(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);

        if ($order->status !== 'Dikirim') {
            return back()->with('error', 'Pesanan ini belum bisa dikonfirmasi selesai.');
        }

        $oldStatus = $order->status;

        $order->update([
            'status' => 'Selesai',
            'completed_at' => now(),
        ]);

        \App\Models\ActivityLog::record(
            'Pesanan',
            'update',
            'Pesanan #ORD-' . str_pad($order->id, 3, '0', STR_PAD_LEFT) . ' dikonfirmasi diterima oleh customer.'
        );

        // Beri tahu admin juga kalau perlu; minimal customer dapat feedback sukses.
        if ($order->user && $order->status !== $oldStatus) {
            // Tidak perlu notif ke diri sendiri; cukup flash message.
        }

        return redirect()->route('orders.myShow', $order)->with('success', 'Terima kasih! Pesanan sudah dikonfirmasi diterima.');
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

        $oldStatus = $order->status;
        $newStatus = $validated['status'];

        // BUGFIX: sebelumnya admin bisa mengubah status ke apa saja tanpa
        // aturan urutan (mis. "Menunggu Pembayaran" langsung ke "Selesai",
        // atau "Selesai" dimundurkan ke "Diproses"). Sekarang dibatasi hanya
        // ke transisi yang masuk akal sesuai alur pesanan.
        if ($oldStatus !== $newStatus && ! in_array($newStatus, self::ALLOWED_TRANSITIONS[$oldStatus] ?? [], true)) {
            return back()->with('error', "Status tidak bisa diubah dari \"{$oldStatus}\" ke \"{$newStatus}\".");
        }

        DB::transaction(function () use ($order, $oldStatus, $newStatus) {
            $lockedOrder = Order::whereKey($order->id)->lockForUpdate()->first();

            // BUGFIX: sebelumnya kalau admin membatalkan pesanan lewat dashboard,
            // stok produk yang sudah dikurangi saat checkout TIDAK dikembalikan —
            // beda dengan pembatalan oleh customer, webhook Midtrans, dan
            // ExpireStaleOrders yang semuanya sudah restock. Sekarang disamakan.
            if ($newStatus === 'Dibatalkan' && $oldStatus !== 'Dibatalkan') {
                $lockedOrder->load('items');
                foreach ($lockedOrder->items as $item) {
                    if ($item->variant_id) {
                        $item->variant?->increment('stock', $item->quantity);
                    } else {
                        $item->product?->increment('stock', $item->quantity);
                    }
                }
            }

            $lockedOrder->update([
                'status' => $newStatus,
                'shipped_at' => $newStatus === 'Dikirim' ? now() : $lockedOrder->shipped_at,
            ]);
        });

        $order->refresh();

        \App\Models\ActivityLog::record(
            'Pesanan',
            'update',
            'Mengubah status pesanan #ORD-' . str_pad($order->id, 3, '0', STR_PAD_LEFT) . ' dari "' . $oldStatus . '" ke "' . $order->status . '".'
        );

        // Kabari customer kalau statusnya memang berubah, supaya tidak perlu
        // cek manual ke halaman "Pesanan Saya".
        if ($oldStatus !== $order->status && $order->user) {
            Notification::send($order->user, new \App\Notifications\OrderStatusUpdatedNotification($order, $oldStatus));
        }

        return redirect()->route('orders.show', $order)->with('success', 'Status pesanan berhasil diperbarui.');
    }
}