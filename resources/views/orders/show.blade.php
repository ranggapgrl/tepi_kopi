@extends('layouts.admin')

@section('title', 'Detail Pesanan - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col md:flex-row gap-8">

    @include('admin.partials.sidebar')

    <div class="flex-grow space-y-6">

        <div class="flex items-center gap-3">
            <a href="{{ route('orders.index') }}" class="w-9 h-9 flex items-center justify-center rounded-lg border border-black/10 hover:bg-black/[0.03] transition-colors" style="color:#412D15;">
                <i class="fa-solid fa-arrow-left text-xs"></i>
            </a>
            <div>
                <h1 class="font-display text-2xl font-semibold text-[#1F150C]">Pesanan #{{ $order->order_code }}</h1>
                <p class="text-[#1F150C]/50 text-sm">Dibuat {{ $order->created_at->translatedFormat('d M Y, H:i') }}</p>
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
            <div class="lg:col-span-2 bg-white rounded-2xl border border-black/10 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-black/5 text-white" style="background:#1F150C;">
                    <h3 class="font-bold">Item Pesanan</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-[#1F150C]/70">
                        <thead class="bg-black/[0.02] text-[#1F150C] border-b border-black/5">
                            <tr>
                                <th class="px-6 py-3 font-bold">Produk</th>
                                <th class="px-6 py-3 font-bold">Harga Satuan</th>
                                <th class="px-6 py-3 font-bold">Qty</th>
                                <th class="px-6 py-3 font-bold text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr class="border-b border-black/5">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg border border-black/10 overflow-hidden flex-shrink-0 flex items-center justify-center" style="background:#E1DCC9; color:#412D15;">
                                            @if($item->product && $item->product->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}" class="w-full h-full object-cover">
                                            @else
                                                <i class="fa-solid fa-mug-hot text-xs opacity-60"></i>
                                            @endif
                                        </div>
                                        <span class="font-semibold text-[#1F150C]">{{ $item->product->name ?? 'Produk dihapus' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 text-right font-bold text-[#1F150C] whitespace-nowrap">
                                    Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background:#E1DCC9;">
                                <td colspan="3" class="px-6 py-4 text-right font-bold text-[#1F150C]">Total (termasuk pajak 11%)</td>
                                <td class="px-6 py-4 text-right font-extrabold text-base whitespace-nowrap" style="color:#412D15;">
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Info pelanggan & status --}}
            <div class="space-y-6">
                <div class="bg-white rounded-2xl border border-black/10 shadow-sm p-6">
                    <h3 class="font-bold text-[#1F150C] mb-4">Informasi Pelanggan</h3>
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-[#1F150C]/40">Nama</dt>
                            <dd class="font-semibold text-[#1F150C]">{{ $order->user->name ?? 'Tamu' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-[#1F150C]/40">Email</dt>
                            <dd class="font-semibold text-[#1F150C]">{{ $order->user->email ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="bg-white rounded-2xl border border-black/10 shadow-sm p-6">
                    <h3 class="font-bold text-[#1F150C] mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-location-dot" style="color:#412D15;"></i> Informasi Pengiriman
                    </h3>
                    @if($order->shipping_address)
                        <dl class="space-y-3 text-sm">
                            <div>
                                <dt class="text-[#1F150C]/40 mb-1">Alamat</dt>
                                <dd class="font-semibold text-[#1F150C]">{{ $order->shipping_address }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-[#1F150C]/40">No. HP</dt>
                                <dd class="font-semibold text-[#1F150C]">{{ $order->shipping_phone }}</dd>
                            </div>
                            @if($order->shipping_notes)
                            <div>
                                <dt class="text-[#1F150C]/40 mb-1">Catatan</dt>
                                <dd class="font-semibold text-[#1F150C]">{{ $order->shipping_notes }}</dd>
                            </div>
                            @endif
                        </dl>
                    @else
                        <p class="text-sm text-[#1F150C]/40 italic">Pesanan ini dibuat sebelum fitur alamat pengiriman ada, jadi datanya tidak tersedia. Hubungi customer secara manual.</p>
                    @endif
                </div>

                {{-- Status changer --}}
                <div class="bg-white rounded-2xl border border-black/10 shadow-sm p-6">
                    <h3 class="font-bold text-[#1F150C] mb-4">Ubah Status Pesanan</h3>
                    <form action="{{ route('orders.update', $order) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-2 gap-2">
                            @foreach($statuses as $status)
                            <label class="cursor-pointer">
                                <input type="radio" name="status" value="{{ $status }}" class="peer hidden" {{ $order->status === $status ? 'checked' : '' }}>
                                <span class="block text-center px-2 py-2.5 rounded-lg border border-black/10 text-[11px] font-bold text-[#1F150C]/55 peer-checked:text-white peer-checked:border-[#412D15] peer-checked:bg-[#412D15] hover:bg-black/[0.02] transition-colors">
                                    {{ $status }}
                                </span>
                            </label>
                            @endforeach
                        </div>

                        <button type="submit"
                                class="w-full px-6 py-3 btn-primary text-white font-bold rounded-xl text-sm shadow-md transition-colors flex items-center justify-center gap-2">
                            <i class="fa-solid fa-check"></i> Simpan Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection