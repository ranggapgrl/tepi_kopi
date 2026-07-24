@extends('layouts.app')

@section('title', 'Detail Pesanan Saya - Tepi Kopi')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

    @php
        $steps = ['Menunggu Pembayaran', 'Diproses', 'Dikirim', 'Selesai'];
        $stepIndex = array_search($order->status, $steps);
        $isCancelled = $order->status === 'Dibatalkan';
        $stepIcons = ['fa-file-invoice', 'fa-box', 'fa-truck-fast', 'fa-circle-check'];
    @endphp

    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
        <i class="fa-solid fa-circle-check"></i>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
        <i class="fa-solid fa-circle-exclamation"></i>
        {{ session('error') }}
    </div>
    @endif

    <div class="flex items-center gap-3 mb-8">
        <a href="{{ route('orders.my') }}" class="w-9 h-9 flex items-center justify-center rounded-lg border border-black/10 hover:bg-black/[0.03] transition-colors" style="color:#412D15;">
            <i class="fa-solid fa-arrow-left text-xs"></i>
        </a>
        <div class="flex-grow">
            <h1 class="font-display text-2xl sm:text-3xl font-semibold text-[#1F150C]">Pesanan #{{ $order->order_code }}</h1>
            <p class="text-[#1F150C]/50 text-sm">Dibuat {{ $order->created_at->translatedFormat('d M Y, H:i') }}</p>
        </div>

        @unless(in_array($order->status, ['Menunggu Pembayaran', 'Dibatalkan']))
        <a href="{{ route('orders.invoice', $order) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 btn-primary text-sm font-bold rounded-lg shadow-sm transition-colors shrink-0">
            <i class="fa-solid fa-file-invoice"></i>
            <span class="hidden sm:inline">Download Invoice</span>
        </a>
        @endunless
    </div>

    {{-- ============ VISUAL STATUS TIMELINE ============ --}}
    <div class="bg-white rounded-2xl border border-black/10 shadow-sm p-6 sm:p-8 mb-6">
        @if($isCancelled)
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 shrink-0 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </div>
                <div>
                    <p class="font-bold text-[#1F150C]">Pesanan Dibatalkan</p>
                    <p class="text-sm text-[#1F150C]/50">Pesanan ini telah dibatalkan dan tidak akan diproses lebih lanjut.</p>
                </div>
            </div>
        @else
            <div class="flex items-start justify-between">
                @foreach($steps as $i => $step)
                <div class="flex-1 flex flex-col items-center relative">
                    @if($i > 0)
                        <div class="absolute top-5 right-1/2 w-full h-0.5" style="background:{{ $i <= $stepIndex ? '#412D15' : 'rgba(0,0,0,0.08)' }};"></div>
                    @endif
                    <div class="relative z-10 w-10 h-10 rounded-full flex items-center justify-center text-sm shrink-0"
                         style="{{ $i <= $stepIndex ? 'background:#412D15; color:#fff;' : 'background:#f2f0ea; color:rgba(31,21,12,0.3);' }}">
                        <i class="fa-solid {{ $stepIcons[$i] }}"></i>
                    </div>
                    <p class="text-[11px] sm:text-xs font-bold mt-2.5 text-center px-1" style="color:{{ $i <= $stepIndex ? '#1F150C' : 'rgba(31,21,12,0.35)' }};">
                        {{ $step }}
                    </p>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="grid lg:grid-cols-3 gap-6">

        {{-- ============ KOLOM KIRI: item pesanan + ulasan ============ --}}
        <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl border border-black/10 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-black/5 text-white" style="background:#1F150C;">
                <h3 class="font-bold">Item Pesanan</h3>
            </div>
            <div class="divide-y divide-black/5">
                @foreach($order->items as $item)
                <div class="p-5 sm:p-6 flex items-center gap-4">
                    <div class="w-14 h-14 shrink-0 rounded-xl overflow-hidden border border-black/10 flex items-center justify-center" style="background:#E1DCC9; color:#412D15;">
                        @if($item->product && $item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fa-solid fa-mug-hot text-sm opacity-50"></i>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-[#1F150C] text-sm sm:text-base truncate">{{ $item->product->name ?? $item->product_name ?? 'Produk dihapus' }}</p>
                        <p class="text-xs text-[#1F150C]/45 mt-0.5">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                    </div>
                    <p class="font-bold text-[#1F150C] text-sm sm:text-base shrink-0">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                </div>
                @endforeach
            </div>
            <div class="px-5 sm:px-6 py-4 flex justify-between items-center" style="background:#E1DCC9;">
                <span class="font-bold text-[#1F150C] text-sm">Total (termasuk pajak 11%)</span>
                <span class="font-display font-semibold text-lg" style="color:#412D15;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- ============ BERI ULASAN (hanya untuk pesanan berstatus Selesai) ============ --}}
        @if($order->status === 'Selesai')
        <div class="bg-white rounded-2xl border border-black/10 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-black/5" style="background:#F2F0EA;">
                <h3 class="font-bold text-[#1F150C]">Beri Ulasan Produk</h3>
                <p class="text-xs text-[#1F150C]/45 mt-0.5">Bagikan pengalamanmu untuk produk yang sudah kamu terima.</p>
            </div>
            <div class="divide-y divide-black/5">
                @foreach($order->items->unique('product_id') as $item)
                    @continue(! $item->product)
                    <div class="p-5 sm:p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 shrink-0 rounded-xl overflow-hidden border border-black/10 flex items-center justify-center" style="background:#E1DCC9; color:#412D15;">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fa-solid fa-mug-hot text-sm opacity-50"></i>
                                @endif
                            </div>
                            <p class="font-bold text-[#1F150C] text-sm">{{ $item->product->name }}</p>
                        </div>

                        @if($reviewedProductIds->contains($item->product_id))
                            <a href="{{ route('katalog.show', $item->product) }}#tulis-ulasan"
                               class="text-xs text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg px-3 py-2 inline-flex items-center gap-2 hover:bg-emerald-100 transition-colors">
                                <i class="fa-solid fa-circle-check"></i> Sudah diulas — lihat / edit ulasan
                            </a>
                        @else
                            <form action="{{ route('reviews.store', $item->product) }}" method="POST" class="space-y-3" x-data="{ selectedRating: 0 }">
                                @csrf
                                <div class="flex gap-1">
                                    <template x-for="i in 5" :key="i">
                                        <button type="button" @click="selectedRating = i" class="text-xl">
                                            <i class="fa-star" :class="i <= selectedRating ? 'fa-solid' : 'fa-regular text-black/20'" :style="i <= selectedRating ? 'color:#412D15' : ''"></i>
                                        </button>
                                    </template>
                                </div>
                                <input type="hidden" name="rating" :value="selectedRating">
                                <textarea name="comment" rows="2" placeholder="Ceritakan pengalamanmu (opsional)"
                                          class="w-full px-3 py-2.5 rounded-xl border border-black/10 bg-black/[0.02] text-sm text-[#1F150C] outline-none focus:ring-2 focus:ring-[#412D15]/20 resize-none"></textarea>
                                <button type="submit" :disabled="selectedRating < 1"
                                        class="px-5 py-2 btn-primary font-bold rounded-xl text-xs shadow-sm transition-colors disabled:opacity-40 disabled:pointer-events-none">
                                    Kirim Ulasan
                                </button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif
        </div>

        {{-- ============ SIDEBAR status action + shipping details ============ --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-black/10 shadow-sm p-6">
                <h3 class="font-bold text-[#1F150C] mb-4">Status Pesanan</h3>
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
                <span class="{{ $statusColor }} inline-block px-3 py-1.5 rounded-md text-sm font-bold">
                    {{ $order->status }}
                </span>

                @if($order->status === 'Menunggu Pembayaran')
                    <p class="text-xs text-[#1F150C]/45 mt-4 leading-relaxed">
                        Pesananmu sedang menunggu pembayaran. Segera lakukan pembayaran agar pesanan bisa diproses.
                    </p>
                    <a href="{{ route('orders.pay', $order) }}"
                       class="w-full mt-4 py-2.5 btn-primary text-white font-bold text-sm rounded-xl flex items-center justify-center gap-2">
                        <i class="fa-solid fa-credit-card"></i> Lanjutkan Pembayaran
                    </a>
                    <form action="{{ route('orders.cancel', $order) }}" method="POST"
                          onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')" class="mt-4">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="w-full py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-sm rounded-xl border border-rose-200 transition-colors flex items-center justify-center gap-2">
                            <i class="fa-solid fa-xmark"></i> Batalkan Pesanan
                        </button>
                    </form>
                @elseif($order->status === 'Dibatalkan')
                    <p class="text-xs text-[#1F150C]/45 mt-4 leading-relaxed">Pesanan ini telah dibatalkan.</p>
                @elseif($order->status === 'Dikirim')
                    <p class="text-xs text-[#1F150C]/45 mt-4 leading-relaxed">
                        Pesananmu sedang dalam perjalanan. Kalau barang sudah sampai, konfirmasi di bawah ini ya.
                    </p>
                    <form action="{{ route('orders.confirm', $order) }}" method="POST"
                          onsubmit="return confirm('Pastikan barang sudah kamu terima sebelum konfirmasi ya.')" class="mt-4">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="w-full py-2.5 btn-primary text-white font-bold text-sm rounded-xl flex items-center justify-center gap-2">
                            <i class="fa-solid fa-check"></i> Pesanan Diterima
                        </button>
                    </form>
                @elseif($order->status === 'Selesai')
                    <p class="text-xs text-[#1F150C]/45 mt-4 leading-relaxed">
                        Pesanan ini sudah selesai. Terima kasih sudah berbelanja di Tepi Kopi!
                    </p>
                @else
                    <p class="text-xs text-[#1F150C]/45 mt-4 leading-relaxed">
                        Kami akan memperbarui status pesanan ini seiring prosesnya. Terima kasih sudah berbelanja di Tepi Kopi!
                    </p>
                @endif
            </div>

            <div class="bg-white rounded-2xl border border-black/10 shadow-sm p-6">
                <h3 class="font-bold text-[#1F150C] mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-location-dot" style="color:#412D15;"></i> Alamat Pengiriman
                </h3>
                @if($order->shipping_address)
                    <dl class="space-y-4 text-sm">

                        <div>
                            <dt class="text-[#1F150C]/40 mb-2">Alamat</dt>
                            <dd class="font-semibold text-[#1F150C] leading-6 whitespace-pre-wrap break-all">
                                {{ $order->shipping_address }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-[#1F150C]/40 mb-2">No. HP</dt>
                            <dd class="font-semibold text-[#1F150C] break-all">
                                {{ $order->shipping_phone }}
                            </dd>
                        </div>

                        @if($order->shipping_notes)
                        <div>
                            <dt class="text-[#1F150C]/40 mb-2">Catatan</dt>
                            <dd class="font-semibold text-[#1F150C] leading-6 whitespace-pre-wrap break-all">
                                {{ $order->shipping_notes }}
                            </dd>
                        </div>
                        @endif

                    </dl>
                @else
                    <p class="text-xs text-[#1F150C]/40 italic">Pesanan ini dibuat sebelum fitur alamat pengiriman ada.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection