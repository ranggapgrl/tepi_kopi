@extends('layouts.admin')

@section('title', 'Pesanan Masuk - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col md:flex-row gap-8">

    @include('admin.partials.sidebar')

    <div class="flex-grow space-y-6">

        <div>
            <h1 class="text-2xl font-bold text-amber-950">Pesanan Masuk</h1>
            <p class="text-amber-700/80 text-sm">Pantau dan kelola status pesanan pelanggan.</p>
        </div>

        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
        @endif

        {{-- Filter status --}}
        <form action="{{ route('orders.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
            <a href="{{ route('orders.index') }}"
               class="text-xs font-semibold px-3.5 py-2 rounded-lg transition-colors {{ !request('status') ? 'bg-amber-800 text-white' : 'bg-white border border-amber-100 text-amber-800 hover:bg-amber-50' }}">
                Semua
            </a>
            @foreach($statuses as $status)
                <a href="{{ route('orders.index', ['status' => $status]) }}"
                   class="text-xs font-semibold px-3.5 py-2 rounded-lg transition-colors {{ request('status') === $status ? 'bg-amber-800 text-white' : 'bg-white border border-amber-100 text-amber-800 hover:bg-amber-50' }}">
                    {{ $status }}
                </a>
            @endforeach
        </form>

        <div class="bg-white rounded-2xl border border-amber-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-amber-50 bg-amber-950 text-white flex justify-between items-center">
                <h3 class="font-bold">Daftar Pesanan</h3>
                <span class="text-xs text-amber-200">{{ $orders->count() }} pesanan</span>
            </div>

            @if($orders->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-amber-50 rounded-full flex items-center justify-center text-amber-600 mx-auto mb-4">
                        <i class="fa-solid fa-receipt text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-amber-950 mb-1">Belum ada pesanan</h3>
                    <p class="text-sm text-gray-500">Pesanan dari pelanggan akan muncul di sini.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-amber-50/50 text-amber-900 border-b border-amber-100">
                            <tr>
                                <th class="px-6 py-3 font-semibold">ID Order</th>
                                <th class="px-6 py-3 font-semibold">Pelanggan</th>
                                <th class="px-6 py-3 font-semibold">Item</th>
                                <th class="px-6 py-3 font-semibold">Total</th>
                                <th class="px-6 py-3 font-semibold">Status</th>
                                <th class="px-6 py-3 font-semibold">Tanggal</th>
                                <th class="px-6 py-3 font-semibold text-right">Aksi</th>
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
                            <tr class="border-b border-gray-100 hover:bg-gray-50 align-middle">
                                <td class="px-6 py-4 font-semibold text-amber-950">#ORD-{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-6 py-4">{{ $order->user->name ?? 'Tamu' }}</td>
                                <td class="px-6 py-4">{{ $order->items_count }} item</td>
                                <td class="px-6 py-4 font-bold text-amber-800 whitespace-nowrap">
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="{{ $statusColor }} px-2.5 py-1 rounded-md text-xs font-bold whitespace-nowrap">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-400 whitespace-nowrap">
                                    {{ $order->created_at->translatedFormat('d M Y, H:i') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('orders.show', $order) }}"
                                       class="inline-flex items-center gap-1.5 text-xs font-semibold text-amber-800 hover:text-amber-950">
                                        Detail <i class="fa-solid fa-chevron-right text-[10px]"></i>
                                    </a>
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