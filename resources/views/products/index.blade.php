@extends('layouts.app')

@section('title', 'Kelola Produk')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col md:flex-row gap-8">

    @include('admin.partials.sidebar')

    <div class="flex-grow space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-amber-950">Kelola Produk</h1>
                <p class="text-amber-700/80 text-sm">Panel admin untuk menambah, mengubah, dan menghapus produk.</p>
            </div>
            <a href="{{ route('products.create') }}"
               class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-amber-800 hover:bg-amber-900 text-white font-bold rounded-xl text-sm shadow-md transition-all hover:-translate-y-0.5 whitespace-nowrap">
                <i class="fa-solid fa-plus"></i> Tambah Produk
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

        {{-- Ringkasan cepat --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
            <div class="bg-white border border-amber-100 rounded-xl px-4 py-3 flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-boxes-stacked text-sm"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] uppercase tracking-wide text-gray-400 font-semibold">Total Produk</p>
                    <p class="text-sm font-extrabold text-amber-950">{{ $products->total() }}</p>
                </div>
            </div>
            <div class="bg-white border border-amber-100 rounded-xl px-4 py-3 flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-check-circle text-sm"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] uppercase tracking-wide text-gray-400 font-semibold">Stok Tersedia</p>
                    <p class="text-sm font-extrabold text-amber-950">{{ $inStockCount }}</p>
                </div>
            </div>
            <div class="bg-white border border-amber-100 rounded-xl px-4 py-3 flex items-center gap-3 col-span-2 sm:col-span-1">
                <div class="w-9 h-9 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] uppercase tracking-wide text-gray-400 font-semibold">Stok Habis</p>
                    <p class="text-sm font-extrabold text-amber-950">{{ $outOfStockCount }}</p>
                </div>
            </div>
        </div>

        @if($products->isEmpty())
            <div class="bg-white rounded-3xl p-12 text-center border border-amber-100 shadow-sm max-w-md mx-auto my-12">
                <div class="w-20 h-20 bg-amber-50 rounded-full flex items-center justify-center text-amber-600 mx-auto mb-6">
                    <i class="fa-solid fa-box-open text-3xl"></i>
                </div>
                <h3 class="text-lg font-bold text-amber-950 mb-2">Belum ada produk</h3>
                <p class="text-amber-700/70 text-sm mb-6">Etalase masih kosong.</p>
                <a href="{{ route('products.create') }}" class="inline-flex items-center px-6 py-3 bg-amber-700 hover:bg-amber-800 text-white text-sm font-medium rounded-xl transition-colors shadow-md">
                    Tambah Produk Pertama
                </a>
            </div>
        @else
            <div class="space-y-3">
                @foreach($products as $product)
                <div class="bg-white rounded-2xl border border-amber-100 shadow-sm hover:shadow-md hover:border-amber-200 transition-all p-4 flex flex-col sm:flex-row gap-4">

                    {{-- Thumbnail --}}
                    <div class="w-full sm:w-28 h-40 sm:h-28 rounded-xl bg-amber-50 border border-amber-100 overflow-hidden flex-shrink-0">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover" alt="{{ $product->name }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-amber-300">
                                <i class="fa-solid fa-mug-hot text-2xl"></i>
                            </div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="flex-grow min-w-0">
                        <div class="flex flex-wrap items-start justify-between gap-2 mb-1.5">
                            <h3 class="font-bold text-amber-950 text-base leading-snug">{{ $product->name }}</h3>
                            <span class="bg-amber-100 text-amber-800 text-[11px] font-bold px-2.5 py-1 rounded-md whitespace-nowrap">
                                {{ $product->category->name ?? '-' }}
                            </span>
                        </div>

                        <p class="text-xs text-gray-500 line-clamp-2 mb-3 max-w-2xl">
                            {{ $product->description ?? 'Tidak ada deskripsi.' }}
                        </p>

                        <div class="flex flex-wrap items-center gap-x-6 gap-y-2">
                            <div>
                                <span class="text-[10px] text-gray-400 block uppercase tracking-wide font-semibold">Harga</span>
                                <span class="text-sm font-extrabold text-amber-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-gray-400 block uppercase tracking-wide font-semibold">Stok</span>
                                <span class="inline-flex items-center text-xs font-semibold {{ $product->stock > 0 ? 'text-emerald-700 bg-emerald-50' : 'text-rose-700 bg-rose-50' }} px-2 py-0.5 rounded-md">
                                    {{ $product->stock > 0 ? $product->stock . ' Pcs' : 'Habis' }}
                                </span>
                            </div>
                            @if($product->variants->isNotEmpty())
                            <div>
                                <span class="text-[10px] text-gray-400 block uppercase tracking-wide font-semibold">Varian</span>
                                <span class="inline-flex items-center text-xs font-semibold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-md">
                                    {{ $product->variants->count() }} varian
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Aksi --}}
                    <div class="flex sm:flex-col items-center sm:items-end justify-end gap-2 flex-shrink-0">
                        <a href="{{ route('products.edit', $product->id) }}"
                           class="w-10 h-10 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-100 rounded-xl flex items-center justify-center transition-colors"
                           title="Edit">
                            <i class="fa-regular fa-pen-to-square text-sm"></i>
                        </a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-10 h-10 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-100 rounded-xl flex items-center justify-center transition-colors" title="Hapus">
                                <i class="fa-regular fa-trash-can text-sm"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="bg-white border border-amber-100 rounded-2xl shadow-sm px-4 sm:px-6 py-4">
                {{ $products->links('pagination.tepikopi') }}
            </div>
        @endif
    </div>
</div>
@endsection