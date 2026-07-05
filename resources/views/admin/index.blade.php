@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col md:flex-row gap-8">

    @include('admin.partials.sidebar')

    <div class="flex-grow space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-amber-950">Selamat Datang, Admin!</h1>
            <p class="text-amber-700/80 text-sm">Ringkasan performa toko Tepi Kopi hari ini.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-amber-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl flex-shrink-0">
                    <i class="fa-solid fa-sack-dollar"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-gray-400 uppercase">Pendapatan Hari Ini</p>
                    <h3 class="text-xl font-extrabold text-amber-950 truncate">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h3>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-amber-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xl flex-shrink-0">
                    <i class="fa-solid fa-box-open"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-gray-400 uppercase">Total Produk</p>
                    <h3 class="text-xl font-extrabold text-amber-950">{{ $totalProducts }} Varian</h3>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-amber-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-xl flex-shrink-0">
                    <i class="fa-solid fa-bell"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-gray-400 uppercase">Pesanan Baru</p>
                    <h3 class="text-xl font-extrabold text-amber-950">{{ $newOrdersCount }} Order</h3>
                </div>
            </div>
        </div>

        {{-- ⬇️ WIDGET BARU: STOK MENIPIS ⬇️ --}}
        <div class="bg-white rounded-2xl border border-amber-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-amber-50 bg-amber-950 text-white flex justify-between items-center">
                <h3 class="font-bold flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation text-amber-400"></i>
                    Stok Menipis
                </h3>
                <a href="{{ route('products.index') }}" class="text-xs text-amber-200 hover:text-white">Kelola Produk</a>
            </div>

            @if($lowStockItems->isEmpty())
                <div class="p-10 text-center text-sm text-gray-400">
                    Semua stok masih aman (di atas {{ $lowStockThreshold }}).
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-amber-50/50 text-amber-900 border-b border-amber-100">
                            <tr>
                                <th class="px-6 py-3 font-semibold">Produk</th>
                                <th class="px-6 py-3 font-semibold">Sisa Stok</th>
                                <th class="px-6 py-3 font-semibold"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lowStockItems as $item)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium">{{ $item['name'] }}</td>
                                <td class="px-6 py-4">
                                    <span class="{{ $item['stock'] <= 0 ? 'text-rose-700 bg-rose-100' : 'text-amber-700 bg-amber-100' }} px-2 py-1 rounded-md text-xs font-bold">
                                        {{ $item['stock'] <= 0 ? 'Habis' : $item['stock'] . ' tersisa' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ $item['edit_url'] }}" class="text-xs font-semibold text-amber-700 hover:text-amber-900 hover:underline">
                                        Restock <i class="fa-solid fa-arrow-right ml-0.5"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        {{-- ⬆️ WIDGET BARU: STOK MENIPIS ⬆️ --}}

        <div class="bg-white rounded-2xl border border-amber-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-amber-50 bg-amber-950 text-white flex justify-between items-center">
                <h3 class="font-bold">Pesanan Terbaru</h3>
                <a href="{{ route('orders.index') }}" class="text-xs text-amber-200 hover:text-white">Lihat Semua</a>
            </div>

            @if($latestOrders->isEmpty())
                <div class="p-10 text-center text-sm text-gray-400">
                    Belum ada pesanan masuk.
                </div>
            @else
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
                            @foreach($latestOrders as $order)
                            @php
                                $statusColor = match($order->status) {
                                    'Menunggu Pembayaran' => 'text-amber-700 bg-amber-100',
                                    'Diproses' => 'text-blue-700 bg-blue-100',
                                    'Dikirim' => 'text-indigo-700 bg-indigo-100',
                                    'Selesai' => 'text-emerald-700 bg-emerald-100',
                                    'Dibatalkan' => 'text-rose-700 bg-rose-100',
                                    default => 'text-gray-700 bg-gray-100',
                                };
                            @endphp
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium">
                                    <a href="{{ route('orders.show', $order) }}" class="hover:text-amber-700">
                                        #ORD-{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}
                                    </a>
                                </td>
                                <td class="px-6 py-4">{{ $order->user->name ?? 'Tamu' }}</td>
                                <td class="px-6 py-4 font-bold text-amber-800 whitespace-nowrap">
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="{{ $statusColor }} px-2 py-1 rounded-md text-xs font-bold whitespace-nowrap">
                                        {{ $order->status }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection