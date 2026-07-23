@extends('layouts.admin')

@section('title', 'Pesanan Masuk - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col md:flex-row gap-8">

    @include('admin.partials.sidebar')

    <div class="flex-grow space-y-6">

        <div>
            <h1 class="font-display text-2xl font-semibold text-[#1F150C]">Pesanan Masuk</h1>
            <p class="text-[#1F150C]/50 text-sm mt-1">Pantau dan kelola status pesanan pelanggan.</p>
        </div>

        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
        @endif

        {{-- Filter status --}}
        <form action="{{ route('orders.index') }}" method="GET" class="flex flex-wrap items-center gap-2 bg-white p-2 rounded-xl border border-black/10 w-fit">
            <a href="{{ route('orders.index') }}"
               class="text-xs font-bold px-3.5 py-2 rounded-lg transition-colors {{ !request('status') ? 'text-white' : 'text-[#1F150C]/60 hover:bg-black/[0.03]' }}"
               style="{{ !request('status') ? 'background:#412D15' : '' }}">
                Semua
            </a>
            @foreach($statuses as $status)
                <a href="{{ route('orders.index', ['status' => $status]) }}"
                   class="text-xs font-bold px-3.5 py-2 rounded-lg transition-colors whitespace-nowrap {{ request('status') === $status ? 'text-white' : 'text-[#1F150C]/60 hover:bg-black/[0.03]' }}"
                   style="{{ request('status') === $status ? 'background:#412D15' : '' }}">
                    {{ $status }}
                </a>
            @endforeach
        </form>

        <div class="bg-white rounded-2xl border border-black/10 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-black/5 text-white flex justify-between items-center" style="background:#1F150C;">
                <h3 class="font-bold">Daftar Pesanan</h3>
                <span class="text-xs text-white/50">{{ $orders->count() }} pesanan</span>
            </div>

            @if($orders->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" style="background:#E1DCC9; color:#412D15;">
                        <i class="fa-solid fa-receipt text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-[#1F150C] mb-1">Belum ada pesanan</h3>
                    <p class="text-sm text-[#1F150C]/45">Pesanan dari pelanggan akan muncul di sini.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-[#1F150C]/70">
                        <thead class="bg-black/[0.02] text-[#1F150C] border-b border-black/5">
                            <tr>
                                <th class="px-6 py-3 font-bold">ID Order</th>
                                <th class="px-6 py-3 font-bold">Pelanggan</th>
                                <th class="px-6 py-3 font-bold">Item</th>
                                <th class="px-6 py-3 font-bold">Total</th>
                                <th class="px-6 py-3 font-bold">Status</th>
                                <th class="px-6 py-3 font-bold">Tanggal</th>
                                <th class="px-6 py-3 font-bold text-right">Aksi</th>
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
                            <tr class="border-b border-black/5 hover:bg-black/[0.015] align-middle cursor-pointer transition-colors"
                                onclick="window.location='{{ route('orders.show', $order) }}'">
                                <td class="px-6 py-4 font-bold text-[#1F150C]">#ORD-{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-6 py-4">
                                <div class="flex items-center gap-2.5">
                                    @if($order->user && $order->user->avatar)
                                        <img src="{{ asset('storage/'.$order->user->avatar) }}"
                                            alt="{{ $order->user->name }}"
                                            class="w-7 h-7 shrink-0 rounded-full object-cover">
                                    @else
                                        <span class="w-7 h-7 shrink-0 rounded-full text-white text-[10px] font-bold flex items-center justify-center" style="background:#412D15;">
                                            {{ strtoupper(substr($order->user->name ?? 'T', 0, 1)) }}
                                        </span>
                                    @endif
                                    {{ $order->user->name ?? 'Tamu' }}
                                </div>
                                </td>
                                <td class="px-6 py-4">{{ $order->items_count }} item</td>
                                <td class="px-6 py-4 font-bold text-[#1F150C] whitespace-nowrap">
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="{{ $statusColor }} px-2.5 py-1 rounded-md text-xs font-bold whitespace-nowrap">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs text-[#1F150C]/40 whitespace-nowrap">
                                    {{ $order->created_at->translatedFormat('d M Y, H:i') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('orders.show', $order) }}" onclick="event.stopPropagation()"
                                       class="inline-flex items-center gap-1.5 text-xs font-bold" style="color:#412D15;">
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