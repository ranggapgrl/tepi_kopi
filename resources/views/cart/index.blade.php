@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 min-h-[60vh]">

    {{-- Header + step indicator — orients the user in the purchase flow --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <h1 class="font-display text-2xl sm:text-3xl font-semibold text-[#1F150C]">Keranjang Belanja</h1>
            <a href="/katalog" class="text-sm font-bold transition-colors flex items-center" style="color:#412D15;">
                <i class="fa-solid fa-arrow-left mr-2"></i> Lanjut Belanja
            </a>
        </div>

        <div class="flex items-center gap-2 sm:gap-3 text-xs sm:text-sm font-semibold">
            <div class="flex items-center gap-2">
                <span class="w-6 h-6 sm:w-7 sm:h-7 rounded-full flex items-center justify-center text-white text-[11px]" style="background:#412D15;">1</span>
                <span class="text-[#1F150C]">Keranjang</span>
            </div>
            <div class="flex-1 h-px bg-black/10 max-w-16 sm:max-w-24"></div>
            <div class="flex items-center gap-2 text-[#1F150C]/35">
                <span class="w-6 h-6 sm:w-7 sm:h-7 rounded-full border-2 border-black/10 flex items-center justify-center text-[11px]">2</span>
                <span>Checkout</span>
            </div>
            <div class="flex-1 h-px bg-black/10 max-w-16 sm:max-w-24"></div>
            <div class="flex items-center gap-2 text-[#1F150C]/35">
                <span class="w-6 h-6 sm:w-7 sm:h-7 rounded-full border-2 border-black/10 flex items-center justify-center text-[11px]">3</span>
                <span>Selesai</span>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-3">
        <i class="fa-solid fa-circle-check"></i>
        <span class="text-sm font-medium">{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl flex items-center gap-3">
        <i class="fa-solid fa-circle-exclamation"></i>
        <span class="text-sm font-medium">{{ session('error') }}</span>
    </div>
    @endif

    @if($cartItems->isEmpty())
    <div class="bg-white rounded-3xl p-12 text-center border border-black/10 shadow-sm max-w-md mx-auto my-12">
        <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6" style="background:#E1DCC9; color:#412D15;">
            <i class="fa-solid fa-cart-shopping text-3xl"></i>
        </div>
        <h3 class="text-lg font-bold text-[#1F150C] mb-2">Keranjang Anda Kosong</h3>
        <p class="text-[#1F150C]/50 text-sm mb-6">Belum ada kopi yang Anda pilih. Yuk, lihat katalog kami!</p>
        <a href="/katalog" class="inline-flex items-center px-6 py-3 btn-primary text-sm font-bold rounded-xl transition-all shadow-md hover:-translate-y-0.5">
            Mulai Belanja
        </a>
    </div>
    @else
    <div class="grid lg:grid-cols-[1fr_360px] gap-8 items-start">

        {{-- ============ ITEM LIST — single receipt-style card with row dividers ============ --}}
        <div class="bg-white rounded-2xl border border-black/10 shadow-sm divide-y divide-black/5">
            @foreach($cartItems as $item)
            @php
                $itemPrice = $item->variant ? $item->variant->price : $item->product->price;
                $lineTotal = $itemPrice * $item->quantity;
            @endphp
            <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center gap-4">

                <div class="flex items-center gap-4 flex-1 min-w-0">
                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl flex-shrink-0 overflow-hidden border border-black/10" style="background:#E1DCC9;">
                        @if($item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center" style="color:#412D15;">
                                <i class="fa-solid fa-mug-hot text-2xl opacity-50"></i>
                            </div>
                        @endif
                    </div>

                    <div class="min-w-0 flex-1">
                        <span class="text-[10px] font-bold tracking-widest uppercase" style="color:#412D15;">{{ $item->product->category->name ?? 'Kopi' }}</span>
                        <h3 class="text-base sm:text-lg font-bold text-[#1F150C] leading-snug line-clamp-1">
                            {{ $item->product->name }}
                            @if($item->variant)
                                <span class="text-[#1F150C]/45 font-medium text-sm">— {{ $item->variant->name }}</span>
                            @endif
                        </h3>
                        <p class="text-[#1F150C]/50 text-sm mt-0.5">Rp {{ number_format($itemPrice, 0, ',', '.') }} / pcs</p>

                        <form action="{{ route('cart.destroy', $item->id) }}" method="POST"
                              onsubmit="return confirm('Hapus {{ $item->product->name }} dari keranjang?')"
                              class="mt-2 sm:hidden">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs font-bold text-rose-600 hover:text-rose-700 flex items-center gap-1.5">
                                <i class="fa-solid fa-trash-can text-[10px]"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>

                <div class="flex items-center justify-between sm:justify-end gap-4 sm:gap-6 pt-3 sm:pt-0 border-t sm:border-t-0 border-black/5">
                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center gap-2.5 rounded-xl border border-black/10 px-2 py-1.5" style="background:#E1DCC9;">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                name="quantity"
                                value="{{ max(1, $item->quantity - 1) }}"
                                {{ $item->quantity <= 1 ? 'disabled' : '' }}
                                class="w-7 h-7 flex items-center justify-center rounded-lg bg-white border border-black/10 text-[#1F150C]/60 hover:text-[#412D15] disabled:opacity-40 disabled:cursor-not-allowed transition-colors">
                            <i class="fa-solid fa-minus text-[10px]"></i>
                        </button>
                        <span class="font-extrabold text-sm text-[#1F150C] w-5 text-center">{{ $item->quantity }}</span>
                        <button type="submit"
                                name="quantity"
                                value="{{ $item->quantity + 1 }}"
                                class="w-7 h-7 flex items-center justify-center rounded-lg bg-white border border-black/10 text-[#1F150C]/60 hover:text-[#412D15] transition-colors">
                            <i class="fa-solid fa-plus text-[10px]"></i>
                        </button>
                    </form>

                    <p class="font-black text-[#1F150C] text-sm sm:text-base w-24 text-right shrink-0">Rp {{ number_format($lineTotal, 0, ',', '.') }}</p>

                    <form action="{{ route('cart.destroy', $item->id) }}" method="POST"
                          onsubmit="return confirm('Hapus {{ $item->product->name }} dari keranjang?')"
                          class="hidden sm:block">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                title="Hapus dari keranjang"
                                class="w-9 h-9 flex items-center justify-center rounded-full text-[#1F150C]/30 hover:text-rose-600 hover:bg-rose-50 transition-colors">
                            <i class="fa-solid fa-trash-can text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        {{-- ============ ORDER SUMMARY — receipt-style with dashed divider ============ --}}
        <div class="bg-white rounded-2xl border border-black/10 shadow-sm lg:sticky lg:top-24 overflow-hidden">
            <div class="p-6">
                <h3 class="font-display text-lg font-semibold text-[#1F150C] mb-5">Ringkasan Pesanan</h3>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between text-[#1F150C]/60">
                        <span>Subtotal ({{ $cartItems->sum('quantity') }} Barang)</span>
                        <span class="font-semibold text-[#1F150C]">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-[#1F150C]/60">
                        <span>Pajak (11%)</span>
                        <span class="font-semibold text-[#1F150C]">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="border-t border-dashed border-black/15 mx-6"></div>

            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <span class="font-bold text-[#1F150C]">Total Akhir</span>
                    <span class="font-display text-2xl font-semibold" style="color:#412D15;">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>

                <form action="{{ route('checkout.show') }}" method="GET">
                    @csrf
                    <button type="submit" class="w-full py-3.5 btn-primary text-white font-bold rounded-xl shadow-md transition-all flex items-center justify-center gap-2 hover:-translate-y-0.5">
                        Checkout Sekarang <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </form>

                <p class="flex items-center justify-center gap-1.5 text-[10px] text-[#1F150C]/35 uppercase tracking-wider mt-4">
                    <i class="fa-solid fa-lock"></i> Pembayaran aman & terenkripsi
                </p>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection