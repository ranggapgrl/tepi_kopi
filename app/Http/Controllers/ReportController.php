<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * ADMIN ONLY — /laporan
     * Laporan penjualan dengan filter rentang tanggal.
     */
    public function index(Request $request)
    {
        // Default: 30 hari terakhir kalau belum ada filter
        $startDate = $request->filled('start_date')
            ? $request->start_date
            : now()->subDays(29)->toDateString();

        $endDate = $request->filled('end_date')
            ? $request->end_date
            : now()->toDateString();

        // Query dasar: pesanan dalam rentang tanggal, tidak termasuk yang dibatalkan
        $baseQuery = Order::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->where('status', '!=', 'Dibatalkan');

        // ==== Kartu Statistik ====
        $totalRevenue = (clone $baseQuery)->sum('total_price');
        $totalOrders = (clone $baseQuery)->count();

        $totalItemsSold = OrderItem::whereHas('order', function ($query) use ($startDate, $endDate) {
            $query->whereDate('created_at', '>=', $startDate)
                ->whereDate('created_at', '<=', $endDate)
                ->where('status', '!=', 'Dibatalkan');
        })->sum('quantity');

        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // ==== Data Grafik: pendapatan per hari (termasuk hari yang tidak ada penjualan = 0) ====
        $dailyRevenue = (clone $baseQuery)
            ->selectRaw('DATE(created_at) as date, SUM(total_price) as total')
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $chartLabels = [];
        $chartData = [];

        foreach (CarbonPeriod::create($startDate, $endDate) as $date) {
            $dateKey = $date->toDateString();
            $chartLabels[] = $date->translatedFormat('d M');
            $chartData[] = (int) ($dailyRevenue[$dateKey]->total ?? 0);
        }

        // ==== Produk Terlaris dalam rentang tanggal ====
        $topProducts = OrderItem::whereHas('order', function ($query) use ($startDate, $endDate) {
                $query->whereDate('created_at', '>=', $startDate)
                    ->whereDate('created_at', '<=', $endDate)
                    ->where('status', '!=', 'Dibatalkan');
            })
            ->selectRaw('product_id, SUM(quantity) as total_qty, SUM(quantity * price) as total_sales')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->with('product')
            ->take(5)
            ->get();

        // ==== Tabel Pesanan dalam rentang tanggal ====
        $orders = (clone $baseQuery)
            ->with('user')
            ->withCount('items')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.reports.index', compact(
            'startDate',
            'endDate',
            'totalRevenue',
            'totalOrders',
            'totalItemsSold',
            'averageOrderValue',
            'chartLabels',
            'chartData',
            'topProducts',
            'orders'
        ));
    }
}