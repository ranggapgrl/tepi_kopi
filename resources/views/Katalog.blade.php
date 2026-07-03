@extends('layouts.app')

@section('title', 'Katalog Produk Tepi Kopi')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10">

    <div class="mb-6 sm:mb-10 text-center sm:text-left flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-200 pb-6 sm:pb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight mb-2">Katalog Kopi Terbaik</h1>
            <p class="text-gray-500 text-xs sm:text-sm">Pilih dan nikmati varian kopi pilihan langsung dari etalase kami.</p>
        </div>
        <div class="text-sm bg-gray-100 text-gray-800 px-4 py-2.5 rounded-xl font-medium self-center sm:self-auto">
            Total: <span class="font-bold">{{ $products->total() }}</span> Produk
        </div>
    </div>

    <form action="/katalog" method="GET" class="mb-8 sm:mb-10">
        <div class="flex flex-col sm:flex-row gap-3 bg-white p-3 sm:p-4 rounded-2xl border border-gray-200 shadow-sm">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..."
                       class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm text-gray-900 placeholder-gray-400 outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300 transition-all">
            </div>
            <div class="flex gap-3">
                <select name="kategori"
                        class="flex-1 sm:flex-none sm:w-48 px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm text-gray-900 outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300 transition-all">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ (string) request('kategori') === (string) $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit"
                        class="px-5 sm:px-6 py-3 bg-amber-700 hover:bg-amber-800 text-white rounded-xl text-sm font-bold transition-colors shadow-md flex items-center justify-center gap-2 whitespace-nowrap">
                    <i class="fa-solid fa-filter"></i>
                    <span class="hidden sm:inline">Terapkan</span>
                </button>
            </div>
        </div>

        @if(request('search') || request('kategori'))
        <div class="flex flex-wrap items-center gap-2 mt-4">
            <span class="text-xs text-gray-600 font-medium">Filter aktif:</span>
            @if(request('search'))
            <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-800 text-xs font-semibold px-3 py-1.5 rounded-full">
                "{{ request('search') }}"
                <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="hover:text-rose-600"><i class="fa-solid fa-xmark"></i></a>
            </span>
            @endif
            @if(request('kategori'))
            <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-800 text-xs font-semibold px-3 py-1.5 rounded-full">
                {{ optional($categories->firstWhere('id', request('kategori')))->name ?? 'Kategori' }}
                <a href="{{ request()->fullUrlWithQuery(['kategori' => null]) }}" class="hover:text-rose-600"><i class="fa-solid fa-xmark"></i></a>
            </span>
            @endif
            <a href="/katalog" class="text-xs text-amber-700 underline hover:text-amber-900 ml-1">Reset semua</a>
        </div>
        @endif
    </form>

    @if(session('success'))
    <div class="mb-8 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-3">
        <i class="fa-solid fa-circle-check"></i>
        <span class="text-sm font-medium">{{ session('success') }}</span>
    </div>
    @endif

    @if($products->isEmpty())
    <div class="bg-white rounded-3xl p-8 sm:p-12 text-center border border-gray-200 shadow-sm max-w-md mx-auto my-12">
        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 mx-auto mb-6">
            <i class="fa-solid fa-box-open text-2xl sm:text-3xl"></i>
        </div>
        @if(request('search') || request('kategori'))
            <h3 class="text-lg font-bold text-gray-900 mb-2">Produk tidak ditemukan</h3>
            <p class="text-gray-500 text-sm mb-6">Coba kata kunci lain atau reset filter pencarian.</p>
            <a href="/katalog" class="inline-flex items-center gap-2 text-sm font-semibold text-amber-700 hover:text-amber-800">
                <i class="fa-solid fa-arrow-rotate-left"></i> Reset Filter
            </a>
        @else
            <h3 class="text-lg font-bold text-gray-900 mb-2">Belum ada produk</h3>
            <p class="text-gray-500 text-sm">Etalase masih kosong, silakan cek lagi nanti.</p>
        @endif
    </div>
    @else
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
    @foreach($products as $product)
    <div class="group relative rounded-2xl overflow-hidden aspect-square shadow-sm hover:shadow-xl transition-all duration-300">

        {{-- Gambar Produk --}}
        <a href="/katalog/{{ $product->id }}" class="block w-full h-full">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                    class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
            @else
                <div class="absolute inset-0 w-full h-full bg-gray-100 flex flex-col items-center justify-center text-gray-400">
                    <i class="fa-solid fa-mug-hot text-4xl sm:text-5xl mb-2"></i>
                    <span class="text-[10px] sm:text-xs font-medium uppercase tracking-wider">No Image</span>
                </div>
            @endif

            {{-- Badge Kategori --}}
            <span class="absolute top-2.5 left-2.5 sm:top-4 sm:left-4 bg-white/90 backdrop-blur-sm text-gray-900 text-[9px] sm:text-[10px] font-bold tracking-widest uppercase px-2 sm:px-3 py-1 sm:py-1.5 rounded-md shadow-sm border border-gray-200 z-10">
                {{ $product->category->name ?? 'Kopi' }}
            </span>

            {{-- Badge Stok Habis --}}
            @if($product->stock <= 0)
                <span class="absolute top-2.5 right-2.5 sm:top-4 sm:right-4 bg-red-600 text-white text-[9px] sm:text-[10px] font-bold px-3 py-1 rounded-full z-10 shadow">
                    Habis
                </span>
            @endif

            {{-- Overlay Gelap + Info Produk --}}
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent p-4 sm:p-5 flex flex-col justify-end">
                <h3 class="text-base sm:text-lg font-bold text-white leading-tight line-clamp-1">{{ $product->name }}</h3>
                <p class="text-amber-300 font-extrabold text-sm sm:text-base mt-1">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                {{-- Tombol Beli (muncul saat hover) --}}
                <div class="mt-3 opacity-0 translate-y-2 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300 ease-out">
                    <form action="/cart/add" method="POST" onclick="event.stopPropagation()">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit"
                                class="w-full py-2.5 bg-amber-700 hover:bg-amber-600 text-white text-sm font-bold rounded-lg shadow-md transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                {{ $product->stock < 1 ? 'disabled' : '' }}>
                            <i class="fa-solid fa-cart-plus mr-2"></i> Beli
                        </button>
                    </form>
                </div>
            </div>
        </a>
    </div>
    @endforeach
    </div>
    <div class="mt-10 sm:mt-12 bg-white border border-gray-200 rounded-2xl shadow-sm px-4 sm:px-6 py-4">
        {{ $products->links('pagination.tepikopi') }}
    </div>
    @endif
</div>
@endsection