<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Barryvdh\DomPDF\Facade\Pdf;
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
        $data = $this->buildReportData($request);

        // Tabel pesanan untuk tampilan web tetap dipaginate biar ringan.
        $data['orders'] = (clone $data['baseQuery'])
            ->with('user')
            ->withCount('items')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        unset($data['baseQuery']);

        return view('admin.reports.index', $data);
    }

    /**
     * ADMIN ONLY — /laporan/export-pdf
     * Unduh laporan penjualan (rentang tanggal yang sama dengan filter
     * yang lagi aktif) dalam bentuk PDF siap cetak.
     */
    public function exportPdf(Request $request)
    {
        $data = $this->buildReportData($request);

        // Untuk PDF, semua pesanan dalam rentang tanggal ditampilkan
        // (tidak dipaginate) supaya laporannya lengkap.
        $data['orders'] = (clone $data['baseQuery'])
            ->with('user')
            ->withCount('items')
            ->latest()
            ->get();

        unset($data['baseQuery']);

        $pdf = Pdf::loadView('admin.reports.pdf', $data)->setPaper('a4', 'portrait');

        $filename = 'laporan-penjualan-tepikopi-' . $data['startDate'] . '-sampai-' . $data['endDate'] . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Logic laporan yang dipakai bareng oleh index() (tampilan web) dan
     * exportPdf() (unduhan PDF), biar angka yang ditampilkan selalu konsisten.
     */
    private function buildReportData(Request $request): array
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

        return compact(
            'startDate',
            'endDate',
            'baseQuery',
            'totalRevenue',
            'totalOrders',
            'totalItemsSold',
            'averageOrderValue',
            'chartLabels',
            'chartData',
            'topProducts'
        );
    }
}