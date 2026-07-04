@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 min-h-[60vh]">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Keranjang Belanja Anda</h1>
        <a href="/katalog" class="text-sm font-medium text-amber-700 hover:text-amber-800 transition-colors flex items-center">
            <i class="fa-solid fa-arrow-left mr-2"></i> Lanjut Belanja
        </a>
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
    <div class="bg-white rounded-3xl p-12 text-center border border-gray-200 shadow-sm max-w-md mx-auto my-12">
        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 mx-auto mb-6">
            <i class="fa-solid fa-cart-shopping text-3xl"></i>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">Keranjang Anda Kosong</h3>
        <p class="text-gray-500 text-sm mb-6">Belum ada kopi yang Anda pilih. Yuk, lihat katalog kami!</p>
        <a href="/katalog" class="inline-flex items-center px-6 py-3 bg-amber-700 hover:bg-amber-800 text-white text-sm font-bold rounded-xl transition-all shadow-md hover:-translate-y-0.5">
            Mulai Belanja
        </a>
    </div>
    @else
    <div class="flex flex-col lg:flex-row gap-8">

        <div class="w-full lg:w-2/3 space-y-4">
            @foreach($cartItems as $item)
            @php
                $itemPrice = $item->variant ? $item->variant->price : $item->product->price;
            @endphp
            <div class="relative bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex flex-col sm:flex-row items-center gap-4 transition-all hover:shadow-md">

                <form action="{{ route('cart.destroy', $item->id) }}" method="POST"
                      onsubmit="return confirm('Hapus {{ $item->product->name }} dari keranjang?')"
                      class="absolute top-3 right-3">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            title="Hapus dari keranjang"
                            class="w-8 h-8 flex items-center justify-center rounded-full text-rose-500 hover:text-rose-700 hover:bg-rose-50 transition-colors">
                        <i class="fa-solid fa-trash-can text-sm"></i>
                    </button>
                </form>

                <div class="w-24 h-24 bg-gray-100 rounded-xl flex-shrink-0 overflow-hidden border border-gray-200">
                    @if($item->product->image)
                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <i class="fa-solid fa-mug-hot text-2xl"></i>
                        </div>
                    @endif
                </div>

                <div class="flex-grow text-center sm:text-left w-full sm:w-auto">
                    <span class="text-[10px] font-bold tracking-widest text-amber-600 uppercase">{{ $item->product->category->name ?? 'Kopi' }}</span>
                    <h3 class="text-lg font-bold text-gray-900 line-clamp-1 pr-8 sm:pr-0">
                        {{ $item->product->name }}
                        @if($item->variant)
                            <span class="text-gray-500 font-medium text-sm">— {{ $item->variant->name }}</span>
                        @endif
                    </h3>
                    <p class="text-amber-700 font-bold mt-1">Rp {{ number_format($itemPrice, 0, ',', '.') }}</p>
                </div>

                <div class="flex items-center justify-center w-full sm:w-auto mt-4 sm:mt-0 pt-4 sm:pt-0 border-t sm:border-t-0 border-gray-100">
                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center gap-3 bg-gray-50 px-2 py-1.5 rounded-xl border border-gray-200">
                        @csrf
                        @method('PATCH')

                        <button type="submit"
                                name="quantity"
                                value="{{ max(1, $item->quantity - 1) }}"
                                {{ $item->quantity <= 1 ? 'disabled' : '' }}
                                class="w-7 h-7 flex items-center justify-center rounded-lg bg-white border border-gray-200 text-gray-600 hover:bg-amber-50 hover:text-amber-800 disabled:opacity-40 disabled:cursor-not-allowed transition-colors">
                            <i class="fa-solid fa-minus text-[10px]"></i>
                        </button>

                        <span class="font-extrabold text-sm text-gray-900 w-6 text-center">{{ $item->quantity }}</span>

                        <button type="submit"
                                name="quantity"
                                value="{{ $item->quantity + 1 }}"
                                class="w-7 h-7 flex items-center justify-center rounded-lg bg-white border border-gray-200 text-gray-600 hover:bg-amber-50 hover:text-amber-800 transition-colors">
                            <i class="fa-solid fa-plus text-[10px]"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <div class="w-full lg:w-1/3">
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm lg:sticky lg:top-24">
                <h3 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-100 pb-4">Ringkasan Pesanan</h3>

                <div class="space-y-3 mb-6 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal ({{ $cartItems->sum('quantity') }} Barang)</span>
                        <span class="font-semibold text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Pajak (11%)</span>
                        <span class="font-semibold text-gray-900">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="flex justify-between items-center mb-6 pt-4 border-t border-gray-100">
                    <span class="font-bold text-gray-900">Total Akhir</span>
                    <span class="text-xl font-extrabold text-amber-700">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>

                <form action="/checkout" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-3.5 bg-amber-700 hover:bg-amber-800 text-white font-bold rounded-xl shadow-md transition-all flex items-center justify-center gap-2 hover:-translate-y-0.5">
                        Checkout Sekarang <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection