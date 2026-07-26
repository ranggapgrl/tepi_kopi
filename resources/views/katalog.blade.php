@extends('layouts.app')

@section('title', 'Katalog Produk Tepi Kopi')

@section('content')
<style>[x-cloak]{display:none!important;}</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12" x-data="{ filterOpen: false }">

    {{-- Breadcrumb + heading --}}
    <div class="mb-8">
        <p class="text-xs text-[#1F150C]/40 mb-2"><a href="/" class="hover:text-[#412D15]">Beranda</a> <i class="fa-solid fa-chevron-right text-[8px] mx-1.5"></i> Katalog</p>
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
            <h1 class="font-display text-3xl sm:text-4xl font-semibold text-[#1F150C]">Katalog Kopi Terbaik</h1>
            <p class="text-sm text-[#1F150C]/50"><span class="font-bold text-[#1F150C]">{{ $products->total() }}</span> produk ditemukan</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-[260px_1fr] gap-8 items-start">

        {{-- ============ DESKTOP FILTER SIDEBAR ============ --}}
        <aside class="hidden lg:block sticky top-24">
            <form action="/katalog" method="GET" class="bg-white border border-black/10 rounded-2xl p-5">
                @if(request('sort'))<input type="hidden" name="sort" value="{{ request('sort') }}">@endif
                <div class="flex items-center justify-between mb-5">
                    <h3 class="font-bold text-[#1F150C] text-sm uppercase tracking-wider">Filter</h3>
                    @if(request('search') || request('kategori'))
                        <a href="/katalog" class="text-xs font-semibold" style="color:#412D15;">Reset</a>
                    @endif
                </div>

                <label class="block text-xs font-bold text-[#1F150C]/60 uppercase tracking-wider mb-2">Cari</label>
                <div class="relative mb-6">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-[#1F150C]/30 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama produk..."
                           class="w-full pl-9 pr-3 py-2.5 rounded-lg border border-black/10 bg-black/[0.02] text-sm outline-none focus:ring-2 focus:ring-[#412D15]/20 focus:border-[#412D15]/40 transition">
                </div>

                <label class="block text-xs font-bold text-[#1F150C]/60 uppercase tracking-wider mb-3">Kategori</label>
                <div class="space-y-1 mb-6">
                    <label class="flex items-center gap-2.5 py-1.5 cursor-pointer group">
                        <input type="radio" name="kategori" value="" {{ !request('kategori') ? 'checked' : '' }} class="accent-[#412D15]">
                        <span class="text-sm text-[#1F150C]/75 group-hover:text-[#1F150C]">Semua Kategori</span>
                    </label>
                    @foreach($categories as $category)
                    <label class="flex items-center gap-2.5 py-1.5 cursor-pointer group">
                        <input type="radio" name="kategori" value="{{ $category->id }}" {{ (string) request('kategori') === (string) $category->id ? 'checked' : '' }} class="accent-[#412D15]">
                        <span class="text-sm text-[#1F150C]/75 group-hover:text-[#1F150C]">{{ $category->name }}</span>
                    </label>
                    @endforeach
                </div>

                <button type="submit" class="w-full py-3 btn-primary rounded-lg text-sm font-bold transition">Terapkan Filter</button>
            </form>
        </aside>

        {{-- ============ MOBILE FILTER SIDEBAR ============ --}}
        <div x-show="filterOpen" x-cloak class="lg:hidden fixed inset-0 z-50">
            <div @click="filterOpen=false" x-show="filterOpen" x-transition.opacity class="absolute inset-0 bg-black/40"></div>
            <div x-show="filterOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                 class="absolute right-0 top-0 h-full w-[85%] max-w-xs bg-white shadow-2xl p-5 overflow-y-auto">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-bold text-[#1F150C]">Filter Produk</h3>
                    <button @click="filterOpen=false" class="w-8 h-8 rounded-full bg-black/5 flex items-center justify-center"><i class="fa-solid fa-xmark text-sm"></i></button>
                </div>
                <form action="/katalog" method="GET">
                    @if(request('sort'))<input type="hidden" name="sort" value="{{ request('sort') }}">@endif
                    <label class="block text-xs font-bold text-[#1F150C]/60 uppercase tracking-wider mb-2">Cari</label>
                    <div class="relative mb-6">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-[#1F150C]/30 text-xs"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama produk..."
                               class="w-full pl-9 pr-3 py-2.5 rounded-lg border border-black/10 bg-black/[0.02] text-sm outline-none focus:ring-2 focus:ring-[#412D15]/20">
                    </div>
                    <label class="block text-xs font-bold text-[#1F150C]/60 uppercase tracking-wider mb-3">Kategori</label>
                    <div class="space-y-1 mb-8">
                        <label class="flex items-center gap-2.5 py-1.5">
                            <input type="radio" name="kategori" value="" {{ !request('kategori') ? 'checked' : '' }} class="accent-[#412D15]">
                            <span class="text-sm text-[#1F150C]/75">Semua Kategori</span>
                        </label>
                        @foreach($categories as $category)
                        <label class="flex items-center gap-2.5 py-1.5">
                            <input type="radio" name="kategori" value="{{ $category->id }}" {{ (string) request('kategori') === (string) $category->id ? 'checked' : '' }} class="accent-[#412D15]">
                            <span class="text-sm text-[#1F150C]/75">{{ $category->name }}</span>
                        </label>
                        @endforeach
                    </div>
                    <button type="submit" class="w-full py-3 btn-primary rounded-lg text-sm font-bold">Terapkan Filter</button>
                </form>
            </div>
        </div>

        {{-- ============ MAIN CONTENT ============ --}}
        <div>
            {{-- SIDEBAR --}}
            <div class="flex items-center justify-between gap-3 mb-6 flex-wrap">
                <div class="flex items-center gap-3 flex-wrap">
                    <button @click="filterOpen=true" class="lg:hidden flex items-center gap-2 px-4 py-2.5 bg-white border border-black/10 rounded-lg text-sm font-semibold text-[#1F150C]">
                        <i class="fa-solid fa-sliders"></i> Filter
                    </button>

                    @if(request('search') || request('kategori'))
                    <div class="flex flex-wrap items-center gap-2">
                        @if(request('search'))
                        <span class="inline-flex items-center gap-1.5 bg-[#E1DCC9] text-[#1F150C] text-xs font-semibold px-3 py-1.5 rounded-full">
                            "{{ request('search') }}"
                            <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="hover:text-rose-600"><i class="fa-solid fa-xmark"></i></a>
                        </span>
                        @endif
                        @if(request('kategori'))
                        <span class="inline-flex items-center gap-1.5 bg-[#E1DCC9] text-[#1F150C] text-xs font-semibold px-3 py-1.5 rounded-full">
                            {{ optional($categories->firstWhere('id', request('kategori')))->name ?? 'Kategori' }}
                            <a href="{{ request()->fullUrlWithQuery(['kategori' => null]) }}" class="hover:text-rose-600"><i class="fa-solid fa-xmark"></i></a>
                        </span>
                        @endif
                    </div>
                    @endif
                </div>

                <form action="/katalog" method="GET" class="flex items-center gap-2">
                    @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
                    @if(request('kategori'))<input type="hidden" name="kategori" value="{{ request('kategori') }}">@endif
                    <label class="text-xs font-semibold text-[#1F150C]/50 hidden sm:block">Urutkan</label>
                    <select name="sort" onchange="this.form.submit()"
                            class="text-sm border border-black/10 rounded-lg px-3 py-2.5 bg-white outline-none focus:border-[#412D15]/40 focus:ring-2 focus:ring-[#412D15]/20 transition">
                        <option value="" {{ !request('sort') ? 'selected' : '' }}>Terbaru</option>
                        <option value="harga_termurah" {{ request('sort') === 'harga_termurah' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="harga_termahal" {{ request('sort') === 'harga_termahal' ? 'selected' : '' }}>Harga Tertinggi</option>
                        <option value="rating_tertinggi" {{ request('sort') === 'rating_tertinggi' ? 'selected' : '' }}>Rating Tertinggi</option>
                        <option value="terlaris" {{ request('sort') === 'terlaris' ? 'selected' : '' }}>Terlaris</option>
                    </select>
                </form>
            </div>

            @if(session('success'))
            <div class="mb-6 px-4 py-3 rounded-xl flex items-center gap-3" style="background:#f3f8f1; border:1px solid #cfe6c9; color:#2f5e29;">
                <i class="fa-solid fa-circle-check"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
            @endif

            @if($products->isEmpty())
            <div class="bg-white rounded-3xl p-8 sm:p-12 text-center border border-black/10 shadow-sm">
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full flex items-center justify-center mx-auto mb-6" style="background:#E1DCC9; color:#412D15;">
                    <i class="fa-solid fa-box-open text-2xl sm:text-3xl"></i>
                </div>
                @if(request('search') || request('kategori'))
                    <h3 class="text-lg font-bold text-[#1F150C] mb-2">Produk tidak ditemukan</h3>
                    <p class="text-[#1F150C]/50 text-sm mb-6">Coba kata kunci lain atau reset filter pencarian.</p>
                    <a href="/katalog" class="inline-flex items-center gap-2 text-sm font-semibold" style="color:#412D15;">
                        <i class="fa-solid fa-arrow-rotate-left"></i> Reset Filter
                    </a>
                @else
                    <h3 class="text-lg font-bold text-[#1F150C] mb-2">Belum ada produk</h3>
                    <p class="text-[#1F150C]/50 text-sm">Etalase masih kosong, silakan cek lagi nanti.</p>
                @endif
            </div>
            @else
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
            @foreach($products as $product)
                <x-product-card :product="$product" :wishlisted="in_array($product->id, $wishlistedProductIds ?? [])" />
            @endforeach
            </div>
            <div class="mt-10 bg-white border border-black/10 rounded-2xl shadow-sm px-4 sm:px-6 py-4">
                {{ $products->links('pagination.tepikopi') }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection