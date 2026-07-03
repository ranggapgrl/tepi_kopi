@extends('layouts.admin')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col md:flex-row gap-8">

    @include('admin.partials.sidebar')

    <div class="flex-grow space-y-6 min-w-0">
        <div>
            <h1 class="text-2xl font-bold text-amber-950">Laporan Penjualan</h1>
            <p class="text-amber-700/80 text-sm">Ringkasan performa penjualan Tepi Kopi berdasarkan rentang tanggal.</p>
        </div>

        {{-- Filter Tanggal --}}
        <form method="GET" action="{{ route('reports.index') }}"
              class="bg-white p-5 rounded-2xl border border-amber-100 shadow-sm flex flex-wrap items-end gap-3">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}"
                       class="border border-amber-200 rounded-lg px-3 py-2 text-sm text-amber-950 focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}"
                       class="border border-amber-200 rounded-lg px-3 py-2 text-sm text-amber-950 focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>
            <button type="submit"
                    class="px-5 py-2.5 bg-amber-800 hover:bg-amber-900 text-white text-sm font-bold rounded-lg shadow-sm transition-colors">
                <i class="fa-solid fa-filter mr-1"></i> Terapkan
            </button>
            <a href="{{ route('reports.index') }}"
               class="px-5 py-2.5 border border-amber-200 text-amber-800 hover:bg-amber-50 text-sm font-bold rounded-lg transition-colors">
                Reset (30 Hari Terakhir)
            </a>
        </form>

        {{-- Kartu Statistik --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-amber-100 shadow-sm">
                <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg mb-3">
                    <i class="fa-solid fa-sack-dollar"></i>
                </div>
                <p class="text-xs font-bold text-gray-400 uppercase">Total Pendapatan</p>
                <h3 class="text-lg font-extrabold text-amber-950 truncate">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-amber-100 shadow-sm">
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-lg mb-3">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <p class="text-xs font-bold text-gray-400 uppercase">Total Pesanan</p>
                <h3 class="text-lg font-extrabold text-amber-950">{{ $totalOrders }} Order</h3>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-amber-100 shadow-sm">
                <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-lg mb-3">
                    <i class="fa-solid fa-box"></i>
                </div>
                <p class="text-xs font-bold text-gray-400 uppercase">Item Terjual</p>
                <h3 class="text-lg font-extrabold text-amber-950">{{ $totalItemsSold }} Item</h3>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-amber-100 shadow-sm">
                <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-lg mb-3">
                    <i class="fa-solid fa-chart-simple"></i>
                </div>
                <p class="text-xs font-bold text-gray-400 uppercase">Rata-rata / Order</p>
                <h3 class="text-lg font-extrabold text-amber-950 truncate">Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</h3>
            </div>
        </div>

        {{-- Grafik Pendapatan --}}
        <div class="bg-white p-6 rounded-2xl border border-amber-100 shadow-sm">
            <h3 class="font-bold text-amber-950 mb-4">Tren Pendapatan Harian</h3>
            @if($totalOrders > 0)
                <div class="relative" style="height: 280px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            @else
                <div class="py-16 text-center text-sm text-gray-400">
                    Belum ada data penjualan di rentang tanggal ini.
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Produk Terlaris --}}
            <div class="lg:col-span-1 bg-white rounded-2xl border border-amber-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-amber-50 bg-amber-950 text-white">
                    <h3 class="font-bold">Produk Terlaris</h3>
                </div>
                @if($topProducts->isEmpty())
                    <div class="p-8 text-center text-sm text-gray-400">Belum ada penjualan.</div>
                @else
                    <ul class="divide-y divide-amber-50">
                        @foreach($topProducts as $item)
                        <li class="px-6 py-3.5 flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-amber-950 truncate">{{ $item->product->name ?? 'Produk Dihapus' }}</p>
                                <p class="text-xs text-gray-400">{{ $item->total_qty }} terjual</p>
                            </div>
                            <span class="text-xs font-bold text-amber-700 whitespace-nowrap">
                                Rp {{ number_format($item->total_sales, 0, ',', '.') }}
                            </span>
                        </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- Tabel Pesanan --}}
            <div class="lg:col-span-2 bg-white rounded-2xl border border-amber-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-amber-50 bg-amber-950 text-white">
                    <h3 class="font-bold">Daftar Pesanan ({{ $startDate }} s/d {{ $endDate }})</h3>
                </div>

                @if($orders->isEmpty())
                    <div class="p-10 text-center text-sm text-gray-400">
                        Tidak ada pesanan pada rentang tanggal ini.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-600">
                            <thead class="bg-amber-50/50 text-amber-900 border-b border-amber-100">
                                <tr>
                                    <th class="px-6 py-3 font-semibold">ID Order</th>
                                    <th class="px-6 py-3 font-semibold">Pelanggan</th>
                                    <th class="px-6 py-3 font-semibold">Tanggal</th>
                                    <th class="px-6 py-3 font-semibold">Total</th>
                                    <th class="px-6 py-3 font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
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
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->created_at->translatedFormat('d M Y') }}</td>
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
                    <div class="px-6 py-4 border-t border-amber-50">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($totalOrders > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
    const ctx = document.getElementById('revenueChart');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: @json($chartData),
                borderColor: '#92400e',
                backgroundColor: 'rgba(146, 64, 14, 0.1)',
                borderWidth: 2,
                tension: 0.3,
                fill: true,
                pointRadius: 3,
                pointBackgroundColor: '#92400e',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
@endif
@endsection