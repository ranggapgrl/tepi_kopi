<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $newOrdersCount = Order::where('status', 'Menunggu Pembayaran')->count();
        $totalRevenue = Order::where('status', 'Selesai')->sum('total_price');
        $latestOrders = Order::latest()->take(5)->get();

        return view('admin.index', compact(
            'totalProducts', 'newOrdersCount', 'totalRevenue', 'latestOrders'
        ));
    }
}