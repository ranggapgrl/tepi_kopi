@extends('layouts.app')

@section('title', 'Detail Pesanan Saya - Tepi Kopi')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">

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
        <a href="{{ route('orders.my') }}" class="w-9 h-9 flex items-center justify-center rounded-lg border border-amber-100 text-amber-700 hover:bg-amber-50 transition-colors">
            <i class="fa-solid fa-arrow-left text-xs"></i>
        </a>
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-amber-950 tracking-tight">
                Pesanan #{{ $order->order_code }}
            </h1>
            <p class="text-amber-700/80 text-sm">Dibuat {{ $order->created_at->translatedFormat('d M Y, H:i') }}</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">

        {{-- Daftar item --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-amber-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-amber-50 bg-amber-950 text-white">
                <h3 class="font-bold">Item Pesanan</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-amber-50/50 text-amber-900 border-b border-amber-100">
                        <tr>
                            <th class="px-6 py-3 font-semibold">Produk</th>
                            <th class="px-6 py-3 font-semibold">Harga Satuan</th>
                            <th class="px-6 py-3 font-semibold">Qty</th>
                            <th class="px-6 py-3 font-semibold text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr class="border-b border-gray-100">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-amber-50 border border-amber-100 overflow-hidden flex-shrink-0 flex items-center justify-center text-amber-300">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fa-solid fa-mug-hot text-xs"></i>
                                        @endif
                                    </div>
                                    <span class="font-semibold text-amber-950">{{ $item->product->name ?? 'Produk dihapus' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 text-right font-bold text-amber-800 whitespace-nowrap">
                                Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-amber-50/40">
                            <td colspan="3" class="px-6 py-4 text-right font-bold text-amber-950">Total (termasuk pajak 11%)</td>
                            <td class="px-6 py-4 text-right font-extrabold text-amber-800 text-base whitespace-nowrap">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Status pesanan --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-amber-100 shadow-sm p-6">
                <h3 class="font-bold text-amber-950 mb-4">Status Pesanan</h3>
                <span class="{{ $statusColor }} inline-block px-3 py-1.5 rounded-md text-sm font-bold">
                    {{ $order->status }}
                </span>

                @if($order->status === 'Menunggu Pembayaran')
                    <p class="text-xs text-gray-500 mt-4 leading-relaxed">
                        Pesananmu sedang menunggu pembayaran. Segera lakukan pembayaran agar pesanan bisa diproses.
                    </p>

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
                    <p class="text-xs text-gray-500 mt-4 leading-relaxed">
                        Pesanan ini telah dibatalkan.
                    </p>
                @else
                    <p class="text-xs text-gray-500 mt-4 leading-relaxed">
                        Kami akan memperbarui status pesanan ini seiring prosesnya. Terima kasih sudah berbelanja di Tepi Kopi!
                    </p>
                @endif
            </div>

            <div class="bg-white rounded-2xl border border-amber-100 shadow-sm p-6">
                <h3 class="font-bold text-amber-950 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-location-dot text-amber-700"></i> Alamat Pengiriman
                </h3>
                @if($order->shipping_address)
                    <dl class="space-y-3 text-sm">
                        <div>
                            <dt class="text-gray-400 mb-1">Alamat</dt>
                            <dd class="font-semibold text-amber-950">{{ $order->shipping_address }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-400 mb-1">No. HP</dt>
                            <dd class="font-semibold text-amber-950">{{ $order->shipping_phone }}</dd>
                        </div>
                        @if($order->shipping_notes)
                        <div>
                            <dt class="text-gray-400 mb-1">Catatan</dt>
                            <dd class="font-semibold text-amber-950">{{ $order->shipping_notes }}</dd>
                        </div>
                        @endif
                    </dl>
                @else
                    <p class="text-xs text-gray-400 italic">Pesanan ini dibuat sebelum fitur alamat pengiriman ada.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection