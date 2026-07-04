@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 min-h-[60vh]">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Checkout</h1>
        <a href="{{ route('cart.index') }}" class="text-sm font-medium text-amber-700 hover:text-amber-800 transition-colors flex items-center">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Keranjang
        </a>
    </div>

    @if($errors->any())
    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl">
        <p class="text-sm font-bold mb-1">Cek lagi isian di bawah:</p>
        <ul class="text-sm list-disc list-inside space-y-0.5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('checkout') }}" method="POST">
        @csrf
        <div class="flex flex-col lg:flex-row gap-8">

            {{-- Form alamat pengiriman --}}
            <div class="w-full lg:w-2/3 space-y-4">
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-location-dot text-amber-700"></i> Alamat Pengiriman
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label for="shipping_address" class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">
                                Alamat Lengkap
                            </label>
                            <textarea name="shipping_address" id="shipping_address" rows="3"
                                placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan, kota, kode pos"
                                class="w-full px-4 py-3 rounded-xl border {{ $errors->has('shipping_address') ? 'border-rose-300' : 'border-gray-200' }} bg-gray-50 text-sm text-gray-900 outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300 transition-all">{{ old('shipping_address', $lastOrder->shipping_address ?? '') }}</textarea>
                        </div>

                        <div>
                            <label for="shipping_phone" class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">
                                Nomor HP Aktif
                            </label>
                            <input type="text" name="shipping_phone" id="shipping_phone"
                                value="{{ old('shipping_phone', $lastOrder->shipping_phone ?? '') }}"
                                placeholder="Contoh: 081234567890"
                                class="w-full px-4 py-3 rounded-xl border {{ $errors->has('shipping_phone') ? 'border-rose-300' : 'border-gray-200' }} bg-gray-50 text-sm text-gray-900 outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300 transition-all">
                        </div>

                        <div>
                            <label for="shipping_notes" class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">
                                Catatan Pengiriman <span class="text-gray-400 font-normal normal-case">(opsional)</span>
                            </label>
                            <input type="text" name="shipping_notes" id="shipping_notes"
                                value="{{ old('shipping_notes') }}"
                                placeholder="Contoh: Rumah cat hijau, sebelah warung"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm text-gray-900 outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300 transition-all">
                        </div>
                    </div>
                </div>

                {{-- Ringkasan item --}}
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Item Pesanan</h3>
                    <div class="space-y-3">
                        @foreach($cartItems as $item)
                        @php $itemPrice = $item->variant ? $item->variant->price : $item->product->price; @endphp
                        <div class="flex items-center justify-between text-sm border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                            <div>
                                <p class="font-semibold text-gray-900">
                                    {{ $item->product->name }}
                                    @if($item->variant)
                                        <span class="text-gray-500 font-normal">— {{ $item->variant->name }}</span>
                                    @endif
                                </p>
                                <p class="text-gray-400 text-xs">{{ $item->quantity }} x Rp {{ number_format($itemPrice, 0, ',', '.') }}</p>
                            </div>
                            <p class="font-bold text-gray-900">Rp {{ number_format($itemPrice * $item->quantity, 0, ',', '.') }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Ringkasan total & tombol bayar --}}
            <div class="w-full lg:w-1/3">
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm lg:sticky lg:top-24">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-100 pb-4">Ringkasan Pembayaran</h3>

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

                    <button type="submit" class="w-full py-3.5 bg-amber-700 hover:bg-amber-800 text-white font-bold rounded-xl shadow-md transition-all flex items-center justify-center gap-2 hover:-translate-y-0.5">
                        Buat Pesanan <i class="fa-solid fa-check"></i>
                    </button>
                    <p class="text-xs text-gray-400 text-center mt-3">Stok akan divalidasi ulang saat pesanan dibuat.</p>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection