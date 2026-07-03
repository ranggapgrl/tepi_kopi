<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
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

        return view('admin.index', compact(
            'todayRevenue', 'totalProducts', 'newOrdersCount', 'latestOrders'
        ));
    }
}