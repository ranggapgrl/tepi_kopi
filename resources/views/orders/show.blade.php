@extends('layouts.admin')

@section('title', 'Detail Pesanan - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col md:flex-row gap-8">

    @include('admin.partials.sidebar')

    <div class="flex-grow space-y-6">

        <div class="flex items-center gap-3">
            <a href="{{ route('orders.index') }}" class="w-9 h-9 flex items-center justify-center rounded-lg border border-amber-100 text-amber-700 hover:bg-amber-50 transition-colors">
                <i class="fa-solid fa-arrow-left text-xs"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-amber-950">Pesanan #{{ $order->order_code }}</h1>
                <p class="text-amber-700/80 text-sm">Dibuat {{ $order->created_at->translatedFormat('d M Y, H:i') }}</p>
            </div>
        </div>

        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
        @endif

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

            {{-- Info pelanggan & status --}}
            <div class="space-y-6">
                <div class="bg-white rounded-2xl border border-amber-100 shadow-sm p-6">
                    <h3 class="font-bold text-amber-950 mb-4">Informasi Pelanggan</h3>
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-400">Nama</dt>
                            <dd class="font-semibold text-amber-950">{{ $order->user->name ?? 'Tamu' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-400">Email</dt>
                            <dd class="font-semibold text-amber-950">{{ $order->user->email ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="bg-white rounded-2xl border border-amber-100 shadow-sm p-6">
                    <h3 class="font-bold text-amber-950 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-location-dot text-amber-700"></i> Informasi Pengiriman
                    </h3>
                    @if($order->shipping_address)
                        <dl class="space-y-3 text-sm">
                            <div>
                                <dt class="text-gray-400 mb-1">Alamat</dt>
                                <dd class="font-semibold text-amber-950">{{ $order->shipping_address }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-400">No. HP</dt>
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
                        <p class="text-sm text-gray-400 italic">Pesanan ini dibuat sebelum fitur alamat pengiriman ada, jadi datanya tidak tersedia. Hubungi customer secara manual.</p>
                    @endif
                </div>

                <div class="bg-white rounded-2xl border border-amber-100 shadow-sm p-6">
                    <h3 class="font-bold text-amber-950 mb-4">Ubah Status Pesanan</h3>
                    <form action="{{ route('orders.update', $order) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <select name="status"
                                class="w-full px-4 py-3 rounded-xl border border-amber-100 bg-amber-50/40 text-sm text-amber-950 outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300 transition-all">
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit"
                                class="w-full px-6 py-3 bg-amber-800 hover:bg-amber-900 text-white font-bold rounded-xl text-sm shadow-md transition-colors flex items-center justify-center gap-2">
                            <i class="fa-solid fa-check"></i> Simpan Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection