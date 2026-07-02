@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col md:flex-row gap-8">
    
    <aside class="w-full md:w-64 flex-shrink-0">
        <div class="bg-white p-5 rounded-2xl border border-amber-100 shadow-sm space-y-2">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 px-2">Menu Kelola</p>
            <a href="/admin" class="flex items-center gap-3 px-3 py-2.5 bg-amber-50 text-amber-800 font-semibold rounded-xl">
                <i class="fa-solid fa-chart-pie w-5"></i> Dashboard
            </a>
            <a href="/products" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 hover:bg-amber-50 hover:text-amber-800 font-medium rounded-xl transition-colors">
                <i class="fa-solid fa-mug-hot w-5"></i> Kelola Produk
            </a>
            <a href="/categories" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 hover:bg-amber-50 hover:text-amber-800 font-medium rounded-xl transition-colors">
                <i class="fa-solid fa-tags w-5"></i> Kategori Menu
            </a>
            <a href="/orders" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 hover:bg-amber-50 hover:text-amber-800 font-medium rounded-xl transition-colors">
                <i class="fa-solid fa-receipt w-5"></i> Pesanan Masuk
            </a>
        </div>
    </aside>

    <div class="flex-grow space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-amber-950">Selamat Datang, Admin!</h1>
            <p class="text-amber-700/80 text-sm">Ringkasan performa toko Tepi Kopi hari ini.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-amber-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-sack-dollar"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase">Pendapatan Hari Ini</p>
                    <h3 class="text-xl font-extrabold text-amber-950">Rp 1.250.000</h3>
                </div>
            </div>
            
            <div class="bg-white p-5 rounded-2xl border border-amber-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-box-open"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase">Total Produk</p>
                    <h3 class="text-xl font-extrabold text-amber-950">24 Varian</h3>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-amber-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-bell"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase">Pesanan Baru</p>
                    <h3 class="text-xl font-extrabold text-amber-950">5 Order</h3>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-amber-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-amber-50 bg-amber-950 text-white flex justify-between items-center">
                <h3 class="font-bold">Pesanan Terbaru</h3>
                <a href="/orders" class="text-xs text-amber-200 hover:text-white">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-amber-50/50 text-amber-900 border-b border-amber-100">
                        <tr>
                            <th class="px-6 py-3 font-semibold">ID Order</th>
                            <th class="px-6 py-3 font-semibold">Pelanggan</th>
                            <th class="px-6 py-3 font-semibold">Total</th>
                            <th class="px-6 py-3 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium">#ORD-001</td>
                            <td class="px-6 py-4">Budi Santoso</td>
                            <td class="px-6 py-4 font-bold text-amber-800">Rp 166.500</td>
                            <td class="px-6 py-4">
                                <span class="bg-amber-100 text-amber-700 px-2 py-1 rounded-md text-xs font-bold">Menunggu Pembayaran</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection