@extends('layouts.app')

@section('title', 'Pesanan Saya - Tepi Kopi')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

    <div class="mb-8">
        <h1 class="font-display text-2xl sm:text-3xl font-semibold text-[#1F150C] mt-2">Pesanan Saya</h1>
        <p class="text-[#1F150C]/50 text-sm mt-1">Riwayat dan status semua pesanan yang pernah kamu buat.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($orders->isEmpty())

        <div class="bg-white rounded-2xl border border-black/10 shadow-sm p-12 text-center">
            <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" style="background:#E1DCC9; color:#412D15;">
                <i class="fa-solid fa-receipt text-2xl"></i>
            </div>
            <h3 class="font-bold text-[#1F150C] mb-1">Belum ada pesanan</h3>
            <p class="text-sm text-[#1F150C]/45 mb-6">Yuk mulai belanja kopi favoritmu di katalog kami.</p>
            <a href="{{ route('katalog.index') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 btn-primary text-white text-sm font-bold rounded-lg transition-colors shadow-sm">
                Lihat Katalog
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

    @else

        <div class="space-y-4">

            @foreach($orders as $order)

                @php
                    $steps = ['Menunggu Pembayaran', 'Diproses', 'Dikirim', 'Selesai'];
                    $stepIndex = array_search($order->status, $steps);
                    $isCancelled = $order->status === 'Dibatalkan';
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
                   class="block bg-white rounded-2xl border border-black/10 shadow-sm p-5 sm:p-6 hover:border-[#412D15]/30 hover:shadow-md transition-all">

                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <p class="font-bold text-[#1F150C]">#ORD-{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</p>
                            <p class="text-xs text-[#1F150C]/40 mt-0.5">
                                {{ $order->created_at->translatedFormat('d M Y, H:i') }} &middot; {{ $order->items_count }} item
                            </p>
                            @if($order->status === 'Menunggu Pembayaran')
                                <p class="text-[11px] text-rose-500 font-semibold mt-1">Masih bisa dibatalkan</p>
                            @endif
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="{{ $statusColor }} px-2.5 py-1 rounded-md text-xs font-bold whitespace-nowrap">
                                {{ $order->status }}
                            </span>
                            <span class="font-bold whitespace-nowrap" style="color:#412D15;">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </span>
                            <i class="fa-solid fa-chevron-right text-xs" style="color:#412D15; opacity:.4;"></i>
                        </div>
                    </div>

                    {{-- Progress bar --}}
                    @if(!$isCancelled)
                    <div class="flex gap-1.5 mt-4 pt-4 border-t border-black/5">
                        @foreach($steps as $i => $step)
                            <div class="h-1 flex-1 rounded-full" style="background:{{ $i <= $stepIndex ? '#412D15' : 'rgba(0,0,0,0.08)' }};"></div>
                        @endforeach
                    </div>
                    @endif
                </a>

            @endforeach

        </div>

    @endif

</div>
@endsection