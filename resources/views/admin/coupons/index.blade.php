@extends('layouts.admin')

@section('title', 'Kupon Diskon - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col md:flex-row gap-8">

    @include('admin.partials.sidebar')

    <div class="flex-grow space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-amber-950">Kupon Diskon</h1>
                <p class="text-amber-700/80 text-sm">Kelola kode kupon yang bisa dipakai customer saat checkout.</p>
            </div>
            <a href="{{ route('coupons.create') }}"
            class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-amber-800 hover:bg-amber-900 text-white font-bold rounded-xl text-sm shadow-md transition-colors whitespace-nowrap">
                <i class="fa-solid fa-plus"></i> Tambah Kupon
            </a>
        </div>

        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation"></i>
            {{ session('error') }}
        </div>
        @endif

        <div class="bg-white rounded-2xl border border-amber-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-amber-50 bg-amber-950 text-white flex justify-between items-center">
                <h3 class="font-bold">Daftar Kupon</h3>
                <span class="text-xs text-amber-200">{{ $coupons->total() }} kupon</span>
            </div>

            @if($coupons->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-amber-50 rounded-full flex items-center justify-center text-amber-600 mx-auto mb-4">
                        <i class="fa-solid fa-ticket text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-amber-950 mb-1">Belum ada kupon</h3>
                    <p class="text-sm text-gray-500 mb-5">Buat kupon pertama untuk memberi diskon ke customer.</p>
                    <a href="{{ route('coupons.create') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-amber-800 hover:text-amber-950">
                        <i class="fa-solid fa-plus"></i> Tambah Kupon
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-amber-50/50 text-amber-900 border-b border-amber-100">
                            <tr>
                                <th class="px-6 py-3 font-semibold">Kode</th>
                                <th class="px-6 py-3 font-semibold">Potongan</th>
                                <th class="px-6 py-3 font-semibold">Min. Belanja</th>
                                <th class="px-6 py-3 font-semibold">Pemakaian</th>
                                <th class="px-6 py-3 font-semibold">Berlaku Sampai</th>
                                <th class="px-6 py-3 font-semibold">Status</th>
                                <th class="px-6 py-3 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($coupons as $coupon)
                            @php
                                $isExpired = $coupon->expires_at && $coupon->expires_at->isPast();
                                $isMaxedOut = $coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit;
                            @endphp
                            <tr class="border-b border-gray-100 hover:bg-gray-50 align-middle">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 flex-shrink-0">
                                            <i class="fa-solid fa-ticket text-xs"></i>
                                        </div>
                                        <p class="font-semibold text-amber-950 font-mono">{{ $coupon->code }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($coupon->type === 'percentage')
                                        {{ $coupon->value }}%
                                        @if($coupon->max_discount)
                                            <span class="text-xs text-gray-400">(maks Rp {{ number_format($coupon->max_discount, 0, ',', '.') }})</span>
                                        @endif
                                    @else
                                        Rp {{ number_format($coupon->value, 0, ',', '.') }}
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    {{ $coupon->min_purchase > 0 ? 'Rp ' . number_format($coupon->min_purchase, 0, ',', '.') : '—' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $coupon->used_count }}{{ $coupon->usage_limit ? ' / ' . $coupon->usage_limit : '' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $coupon->expires_at ? $coupon->expires_at->format('d M Y') : 'Tanpa batas' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if(! $coupon->is_active)
                                        <span class="bg-gray-100 text-gray-600 text-xs font-semibold px-2.5 py-1 rounded-md">Nonaktif</span>
                                    @elseif($isExpired)
                                        <span class="bg-rose-100 text-rose-700 text-xs font-semibold px-2.5 py-1 rounded-md">Kedaluwarsa</span>
                                    @elseif($isMaxedOut)
                                        <span class="bg-rose-100 text-rose-700 text-xs font-semibold px-2.5 py-1 rounded-md">Habis</span>
                                    @else
                                        <span class="bg-emerald-100 text-emerald-700 text-xs font-semibold px-2.5 py-1 rounded-md">Aktif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('coupons.edit', $coupon) }}"
                                        class="w-9 h-9 flex items-center justify-center rounded-lg border border-amber-100 text-amber-700 hover:bg-amber-50 transition-colors"
                                        title="Edit">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </a>
                                        <form action="{{ route('coupons.destroy', $coupon) }}" method="POST"
                                            onsubmit="return confirm('Hapus kupon &quot;{{ $coupon->code }}&quot;?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="w-9 h-9 flex items-center justify-center rounded-lg border border-rose-100 text-rose-600 hover:bg-rose-50 transition-colors"
                                                    title="Hapus">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-amber-50">
                    {{ $coupons->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
