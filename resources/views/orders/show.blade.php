@extends('layouts.admin')

@section('title', 'Detail Pesanan - Admin')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col md:flex-row gap-8">

    @include('admin.partials.sidebar')

    <div class="flex-grow space-y-6">

        {{-- Header --}}
        <div class="flex items-center gap-3">

            <a href="{{ route('orders.index') }}"
               class="w-9 h-9 flex items-center justify-center rounded-lg border border-black/10 hover:bg-black/[0.03] transition"
               style="color:#412D15;">

                <i class="fa-solid fa-arrow-left text-xs"></i>

            </a>

            <div>

                <h1 class="font-display text-2xl font-semibold text-[#1F150C]">
                    Pesanan #{{ $order->order_code }}
                </h1>

                <p class="text-[#1F150C]/50 text-sm">
                    Dibuat {{ $order->created_at->translatedFormat('d M Y, H:i') }}
                </p>

            </div>

        </div>

        {{-- Alert --}}
        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3 flex items-center gap-2 text-emerald-700 text-sm font-medium">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
        @endif

        <div class="grid lg:grid-cols-3 gap-6 items-start">

            {{-- ======================= --}}
            {{-- ITEM PESANAN --}}
            {{-- ======================= --}}

            <div class="lg:col-span-2 bg-white rounded-2xl border border-black/10 shadow-sm overflow-hidden">

                <div class="px-6 py-4 text-white" style="background:#1F150C;">
                    <h3 class="font-bold">
                        Item Pesanan
                    </h3>
                </div>

                <div class="overflow-x-auto">

                    <table class="w-full text-left text-sm">

                        <thead class="bg-black/[0.02] border-b border-black/5">

                        <tr>

                            <th class="px-6 py-3 font-bold text-[#1F150C]">
                                Produk
                            </th>

                            <th class="px-6 py-3 font-bold text-[#1F150C] whitespace-nowrap">
                                Harga
                            </th>

                            <th class="px-6 py-3 font-bold text-[#1F150C]">
                                Qty
                            </th>

                            <th class="px-6 py-3 font-bold text-right text-[#1F150C]">
                                Subtotal
                            </th>

                        </tr>

                        </thead>

                        <tbody>

                        @foreach($order->items as $item)

                        <tr class="border-b border-black/5 hover:bg-[#FAF8F4] transition">

                            <td class="px-6 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="w-11 h-11 rounded-lg overflow-hidden border border-black/10 flex items-center justify-center bg-[#E1DCC9]">

                                        @if($item->product && $item->product->image)

                                            <img
                                                src="{{ asset('storage/'.$item->product->image) }}"
                                                class="w-full h-full object-cover">

                                        @else

                                            <i class="fa-solid fa-mug-hot text-[#412D15]"></i>

                                        @endif

                                    </div>

                                    <span class="font-semibold text-[#1F150C]">

                                        {{ $item->product->name ?? $item->product_name ?? 'Produk dihapus' }}

                                    </span>

                                </div>

                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">

                                Rp {{ number_format($item->price,0,',','.') }}

                            </td>

                            <td class="px-6 py-4">

                                {{ $item->quantity }}

                            </td>

                            <td class="px-6 py-4 text-right font-bold text-[#1F150C] whitespace-nowrap">

                                Rp {{ number_format($item->price*$item->quantity,0,',','.') }}

                            </td>

                        </tr>

                        @endforeach

                        </tbody>

                        <tfoot>

                        <tr style="background:#E1DCC9;">

                            <td colspan="3"
                                class="px-6 py-4 text-right font-bold text-[#1F150C]">

                                Total (termasuk pajak 11%)

                            </td>

                            <td class="px-6 py-4 text-right font-extrabold text-base whitespace-nowrap text-[#412D15]">

                                Rp {{ number_format($order->total_price,0,',','.') }}

                            </td>

                        </tr>

                        </tfoot>

                    </table>

                </div>

            </div>

            {{-- ======================= --}}
            {{-- SIDEBAR --}}
            {{-- ======================= --}}

            <div class="space-y-6">

                {{-- Informasi Pelanggan --}}
                <div class="bg-white rounded-2xl border border-black/10 shadow-sm p-6">

                    <h3 class="font-bold text-[#1F150C] mb-5 flex items-center gap-2">

                        <i class="fa-solid fa-user text-[#412D15]"></i>

                        Informasi Pelanggan

                    </h3>

                    <div class="space-y-4">

                        <div>

                            <p class="text-xs text-[#1F150C]/45 mb-1">

                                Nama

                            </p>

                            <p class="font-semibold text-[#1F150C]">

                                {{ $order->user->name ?? 'Tamu' }}

                            </p>

                        </div>

                        <div>

                            <p class="text-xs text-[#1F150C]/45 mb-1">

                                Email

                            </p>

                            <p class="font-semibold text-[#1F150C] break-all">

                                {{ $order->user->email ?? '-' }}

                            </p>

                        </div>

                    </div>

                </div>

                {{-- Informasi Pengiriman --}}
                <div class="bg-white rounded-2xl border border-black/10 shadow-sm p-6 overflow-hidden">

                    <h3 class="font-bold text-[#1F150C] mb-5 flex items-center gap-2">

                        <i class="fa-solid fa-location-dot text-[#412D15]"></i>

                        Informasi Pengiriman

                    </h3>

                    @if($order->shipping_address)

                    <div class="space-y-5">

                        <div>

                            <p class="text-xs text-[#1F150C]/45 mb-2">
                                Alamat
                            </p>

                            <div class="rounded-xl border border-[#E5DFD2] bg-[#F8F6F2] p-3">

                                <p class="text-sm text-[#1F150C] leading-6 whitespace-pre-wrap break-all">

                                    {{ $order->shipping_address }}

                                </p>

                            </div>

                        </div>

                        <div>

                            <p class="text-xs text-[#1F150C]/45 mb-2">
                                Nomor HP
                            </p>

                            <p class="font-semibold text-[#1F150C]">

                                {{ $order->shipping_phone }}

                            </p>

                        </div>

                        @if($order->shipping_notes)

                        <div>

                            <p class="text-xs text-[#1F150C]/45 mb-2">
                                Catatan
                            </p>

                            <div class="rounded-xl border border-amber-200 bg-amber-50 p-3">

                                <p class="text-sm text-[#1F150C] leading-6 whitespace-pre-wrap break-words">

                                    {{ $order->shipping_notes }}

                                </p>

                            </div>

                        </div>

                        @endif

                    </div>

                    @else

                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">

                        <p class="text-sm italic text-[#1F150C]/50">

                            Pesanan ini dibuat sebelum fitur alamat pengiriman tersedia.

                        </p>

                    </div>

                    @endif
                </div>

                                {{-- Status changer --}}
                <div class="bg-white rounded-2xl border border-black/10 shadow-sm p-6">

                    <h3 class="font-bold text-[#1F150C] mb-5 flex items-center gap-2">
                        <i class="fa-solid fa-arrows-rotate text-[#412D15]"></i>
                        Ubah Status Pesanan
                    </h3>

                    <form action="{{ route('orders.update', $order) }}" method="POST" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-2 gap-3">

                            @foreach($statuses as $status)

                            @php
                                $icon = match($status){
                                    'Menunggu Pembayaran' => 'fa-hourglass-half',
                                    'Diproses' => 'fa-gear',
                                    'Dikirim' => 'fa-truck-fast',
                                    'Selesai' => 'fa-circle-check',
                                    'Dibatalkan' => 'fa-circle-xmark',
                                    default => 'fa-circle'
                                };
                            @endphp

                            <label class="cursor-pointer">

                                <input
                                    type="radio"
                                    name="status"
                                    value="{{ $status }}"
                                    class="peer sr-only"
                                    {{ $order->status == $status ? 'checked' : '' }}>

                                <div
                                    class="h-20 rounded-xl border border-black/10
                                    flex flex-col items-center justify-center
                                    gap-2
                                    bg-white
                                    transition-all duration-200

                                    hover:border-[#412D15]
                                    hover:bg-[#FAF8F4]
                                    hover:shadow-sm

                                    peer-checked:border-[#412D15]
                                    peer-checked:ring-2
                                    peer-checked:ring-[#412D15]/20">

                                    <i class="fa-solid {{ $icon }}
                                              text-base
                                              text-[#412D15]
                                              peer-checked:text-white"></i>

                                    <span class="text-[11px] font-semibold text-center leading-4
                                                 text-[#1F150C]/70
                                                 peer-checked:text-white px-2">

                                        {{ $status }}

                                    </span>

                                </div>

                            </label>

                            @endforeach

                        </div>

                        <div class="rounded-xl border border-amber-200 bg-amber-50 p-3">

                            <div class="flex gap-2 items-start">

                                <i class="fa-solid fa-circle-info text-amber-600 mt-0.5"></i>

                                <p class="text-xs text-[#1F150C]/70 leading-5">

                                    Pilih status pesanan yang sesuai, kemudian klik
                                    <strong>Simpan Status</strong> untuk memperbarui status
                                    pesanan pelanggan.

                                </p>

                            </div>

                        </div>

                        <button
                            type="submit"
                            class="w-full h-12 rounded-xl
                                   bg-[#412D15]
                                   hover:bg-[#5B3B1F]
                                   text-white
                                   font-semibold
                                   transition
                                   flex items-center justify-center gap-2">

                            <i class="fa-solid fa-floppy-disk"></i>

                            Simpan Status

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection