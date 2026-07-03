@extends('layouts.app')

@section('title', 'Katalog Produk Tepi Kopi')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10">

    <div class="mb-6 sm:mb-10 text-center sm:text-left flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-amber-100/60 pb-6 sm:pb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-amber-950 tracking-tight mb-2">Katalog E-Commerce Coffee Terbaik</h1>
            <p class="text-amber-700/80 text-xs sm:text-sm">Pilih dan nikmati varian berbagai kebutuhan <b><span style="color:#451A03">coffee</span></b> langsung dari etalase kami.</p>
        </div>
        <div class="text-sm bg-amber-100/50 text-amber-900 px-4 py-2.5 rounded-xl border border-amber-200/40 font-medium self-center sm:self-auto">
            Total: <span class="font-bold">{{ $products->total() }}</span> Produk
        </div>
    </div>

    {{-- Filter: Pencarian & Kategori --}}
    <form action="/katalog" method="GET" class="mb-8 sm:mb-10">
        <div class="flex flex-col sm:flex-row gap-3 bg-white p-3 sm:p-4 rounded-2xl border border-amber-100 shadow-sm">

            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-amber-400 text-sm"></i>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari produk disini..."
                    class="w-full pl-11 pr-4 py-3 rounded-xl border border-amber-100 bg-amber-50/40 text-sm text-amber-950 placeholder-amber-400 outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300 transition-all"
                >
            </div>

            <div class="flex gap-3">
                <select
                    name="kategori"
                    class="flex-1 sm:flex-none sm:w-48 px-4 py-3 rounded-xl border border-amber-100 bg-amber-50/40 text-sm text-amber-950 outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300 transition-all appearance-none bg-no-repeat bg-[right_1rem_center]"
                    style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2212%22 height=%228%22 viewBox=%220 0 12 8%22><path fill=%22%2392400e%22 d=%22M1 1l5 5 5-5%22 stroke=%22%2392400e%22 stroke-width=%221.5%22 fill=%22none%22/></svg>');"
                >
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ (string) request('kategori') === (string) $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit"
                        class="px-5 sm:px-6 py-3 bg-amber-800 hover:bg-amber-900 text-white rounded-xl text-sm font-bold transition-colors shadow-md flex items-center justify-center gap-2 whitespace-nowrap">
                    <i class="fa-solid fa-filter"></i>
                    <span class="hidden sm:inline">Terapkan</span>
                </button>
            </div>
        </div>

        {{-- Chip filter aktif --}}
        @if(request('search') || request('kategori'))
        <div class="flex flex-wrap items-center gap-2 mt-4">
            <span class="text-xs text-amber-800/60 font-medium">Filter aktif:</span>

            @if(request('search'))
            <span class="inline-flex items-center gap-1.5 bg-amber-100 text-amber-900 text-xs font-semibold px-3 py-1.5 rounded-full">
                "{{ request('search') }}"
                <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="hover:text-rose-600">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            </span>
            @endif

            @if(request('kategori'))
            <span class="inline-flex items-center gap-1.5 bg-amber-100 text-amber-900 text-xs font-semibold px-3 py-1.5 rounded-full">
                {{ optional($categories->firstWhere('id', request('kategori')))->name ?? 'Kategori' }}
                <a href="{{ request()->fullUrlWithQuery(['kategori' => null]) }}" class="hover:text-rose-600">
                    <i class="fa-solid fa-xmark"></i>
                </a>
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
    <div class="bg-white rounded-3xl p-8 sm:p-12 text-center border border-amber-100 shadow-sm max-w-md mx-auto my-12">
        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-amber-50 rounded-full flex items-center justify-center text-amber-600 mx-auto mb-6">
            <i class="fa-solid fa-box-open text-2xl sm:text-3xl"></i>
        </div>
        @if(request('search') || request('kategori'))
            <h3 class="text-lg font-bold text-amber-950 mb-2">Produk tidak ditemukan</h3>
            <p class="text-amber-700/70 text-sm mb-6">Coba kata kunci lain atau reset filter pencarian.</p>
            <a href="/katalog" class="inline-flex items-center gap-2 text-sm font-semibold text-amber-800 hover:text-amber-950">
                <i class="fa-solid fa-arrow-rotate-left"></i> Reset Filter
            </a>
        @else
            <h3 class="text-lg font-bold text-amber-950 mb-2">Belum ada produk</h3>
            <p class="text-amber-700/70 text-sm">Etalase masih kosong, silakan cek lagi nanti.</p>
        @endif
    </div>
    @else
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
        @foreach($products as $product)
        <div class="bg-white rounded-xl sm:rounded-2xl border border-amber-100 overflow-hidden shadow-sm hover:shadow-xl hover:border-amber-200 transition-all duration-500 flex flex-col group">

            <a href="/katalog/{{ $product->id }}" class="relative aspect-square w-full bg-amber-50/50 overflow-hidden border-b border-amber-50 block">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-amber-300">
                        <i class="fa-solid fa-mug-hot text-4xl sm:text-5xl mb-2"></i>
                        <span class="text-[10px] sm:text-xs font-medium uppercase tracking-wider">No Image</span>
                    </div>
                @endif
                <span class="absolute top-2.5 left-2.5 sm:top-4 sm:left-4 bg-white/90 backdrop-blur-sm text-amber-900 text-[9px] sm:text-[10px] font-bold tracking-widest uppercase px-2 sm:px-3 py-1 sm:py-1.5 rounded-md shadow-sm border border-amber-100">
                    {{ $product->category->name ?? 'Kopi' }}
                </span>
            </a>

            <div class="p-3.5 sm:p-6 flex flex-col flex-grow">
                <a href="/katalog/{{ $product->id }}">
                    <h3 class="text-sm sm:text-lg font-bold text-amber-950 line-clamp-1 mb-1 group-hover:text-amber-600 transition-colors">
                        {{ $product->name }}
                    </h3>
                </a>
                <p class="text-[11px] sm:text-xs text-gray-500 mb-3 sm:mb-4 line-clamp-2 min-h-[28px] sm:min-h-[32px]">
                    {{ $product->description ?? 'Deskripsi produk belum tersedia.' }}
                </p>

                <div class="mt-auto pt-3 sm:pt-4 border-t border-amber-50 flex items-center justify-between mb-4 sm:mb-5 gap-2">
                    <div class="min-w-0">
                        <span class="text-[9px] sm:text-[10px] text-gray-400 block uppercase tracking-wider font-semibold">Harga</span>
                        <span class="text-sm sm:text-base font-extrabold text-amber-900 truncate block">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="text-[9px] sm:text-[10px] text-gray-400 block uppercase tracking-wider font-semibold">Stok</span>
                        <span class="inline-flex items-center text-[10px] sm:text-xs font-semibold {{ $product->stock > 0 ? 'text-emerald-700 bg-emerald-50' : 'text-rose-700 bg-rose-50' }} px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-md">
                            {{ $product->stock > 0 ? $product->stock . ' Pcs' : 'Habis' }}
                        </span>
                    </div>
                </div>

                <form action="/cart/add" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="w-full py-2 sm:py-2.5 bg-amber-800 hover:bg-amber-900 text-white rounded-lg sm:rounded-xl text-[11px] sm:text-xs font-bold transition-all shadow-md flex items-center justify-center gap-2 disabled:opacity-50 disabled:pointer-events-none" {{ $product->stock < 1 ? 'disabled' : '' }}>
                        <i class="fa-solid fa-cart-plus"></i> Beli
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination bernomor 1, 2, 3, dst --}}
    <div class="mt-10 sm:mt-12 bg-white border border-amber-100 rounded-2xl shadow-sm px-4 sm:px-6 py-4">
        {{ $products->links('pagination.tepikopi') }}
    </div>
    @endif
</div>
@endsection