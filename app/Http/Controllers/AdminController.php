<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * ADMIN ONLY — /admin
     */
    public function index()
    {
        // Pendapatan hari ini: total dari order yang dibuat hari ini, kecuali yang dibatalkan
        $todayRevenue = Order::whereDate('created_at', today())
            ->where('status', '!=', 'Dibatalkan')
            ->sum('total_price');

        // Total varian produk yang ada di katalog
        $totalProducts = Product::count();

        // Pesanan baru: yang masih menunggu pembayaran (belum diproses)
        $newOrdersCount = Order::where('status', 'Menunggu Pembayaran')->count();

        // 5 pesanan terbaru untuk tabel "Pesanan Terbaru"
        $latestOrders = Order::with('user')->latest()->take(5)->get();

        // Stok menipis: produk tanpa varian dan varian produk yang stoknya
        // sudah di ambang batas (low_stock_threshold) atau habis.
        $lowStockThreshold = config('tepikopi.low_stock_threshold', 5);

        $lowStockProducts = Product::whereDoesntHave('variants')
            ->where('stock', '<=', $lowStockThreshold)
            ->orderBy('stock')
            ->take(10)
            ->get();

        $lowStockVariants = ProductVariant::with('product')
            ->where('stock', '<=', $lowStockThreshold)
            ->orderBy('stock')
            ->take(10)
            ->get();

        // Gabungkan jadi satu daftar seragam untuk ditampilkan di tabel,
        // lalu urutkan lagi berdasarkan stok paling sedikit.
        $lowStockItems = $lowStockProducts->map(fn ($product) => [
            'name'  => $product->name,
            'stock' => $product->stock,
            'edit_url' => route('products.edit', $product),
        ])->concat($lowStockVariants->map(fn ($variant) => [
            'name'  => ($variant->product->name ?? 'Produk') . ' — ' . $variant->name,
            'stock' => $variant->stock,
            'edit_url' => $variant->product ? route('products.edit', $variant->product) : route('products.index'),
        ]))->sortBy('stock')->take(10)->values();

        return view('admin.index', compact(
            'todayRevenue', 'totalProducts', 'newOrdersCount', 'latestOrders',
            'lowStockItems', 'lowStockThreshold'
        ));
    }
}