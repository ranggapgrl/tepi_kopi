@extends('layouts.app')

@section('title', 'Pesanan Saya - Tepi Kopi')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">

    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-amber-950 tracking-tight mb-2">
            Pesanan Saya
        </h1>
        <p class="text-amber-700/80 text-sm">
            Riwayat dan status semua pesanan yang pernah kamu buat.
        </p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($orders->isEmpty())

        <div class="bg-white rounded-2xl border border-amber-100 shadow-sm p-12 text-center">
            <div class="w-16 h-16 bg-amber-50 rounded-full flex items-center justify-center text-amber-600 mx-auto mb-4">
                <i class="fa-solid fa-receipt text-2xl"></i>
            </div>

            <h3 class="font-bold text-amber-950 mb-1">
                Belum ada pesanan
            </h3>

            <p class="text-sm text-gray-500 mb-6">
                Yuk mulai belanja kopi favoritmu di katalog kami.
            </p>

            <a href="{{ route('katalog.index') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-800 hover:bg-amber-900 text-white text-sm font-bold rounded-lg transition-colors shadow-sm">
                Lihat Katalog
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

    @else

        <div class="space-y-4">

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

                <a href="{{ route('orders.myShow', $order) }}"
                   class="block bg-white rounded-2xl border border-amber-100 shadow-sm p-5 sm:p-6 hover:border-amber-300 transition-colors">

                    <div class="flex flex-wrap items-center justify-between gap-3">

                        <div>
                            <p class="font-bold text-amber-950">
                                #ORD-{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}
                            </p>

                            <p class="text-xs text-gray-400 mt-0.5">
                                {{ $order->created_at->translatedFormat('d M Y, H:i') }}
                                &middot;
                                {{ $order->items_count }} item
                            </p>

                            @if($order->status === 'Menunggu Pembayaran')
                                <p class="text-[11px] text-rose-500 font-semibold mt-1">
                                    Masih bisa dibatalkan
                                </p>
                            @endif
                        </div>

                        <div class="flex items-center gap-3">

                            <span class="{{ $statusColor }} px-2.5 py-1 rounded-md text-xs font-bold whitespace-nowrap">
                                {{ $order->status }}
                            </span>

                            <span class="font-bold text-amber-800 whitespace-nowrap">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </span>

                            <i class="fa-solid fa-chevron-right text-xs text-amber-300"></i>

                        </div>

                    </div>

                </a>

            @endforeach

        </div>

    @endif

</div>
@endsection