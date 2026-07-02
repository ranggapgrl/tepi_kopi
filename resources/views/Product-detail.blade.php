@extends('layouts.app')

@section('title', $product->name . ' - Tepi Kopi')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10">

    {{-- Breadcrumb --}}
    <div class="mb-6 sm:mb-8 text-xs sm:text-sm text-amber-800/70 overflow-x-auto whitespace-nowrap">
        <a href="/" class="hover:text-amber-700">Beranda</a>
        <span class="mx-2">/</span>
        <a href="/katalog" class="hover:text-amber-700">Katalog</a>
        @if($product->category)
        <span class="mx-2">/</span>
        <a href="/katalog?kategori={{ $product->category->id }}" class="hover:text-amber-700">{{ $product->category->name }}</a>
        @endif
        <span class="mx-2">/</span>
        <span class="text-amber-950 font-medium">{{ $product->name }}</span>
    </div>

    <div class="grid md:grid-cols-2 gap-8 lg:gap-16">

        {{-- Gambar Utama --}}
        <div class="md:sticky md:top-24 md:self-start">
            <div class="relative aspect-square w-full bg-amber-50/50 rounded-2xl sm:rounded-3xl overflow-hidden border border-amber-100">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-amber-300">
                        <i class="fa-solid fa-mug-hot text-5xl sm:text-6xl mb-3"></i>
                        <span class="text-xs sm:text-sm font-medium uppercase tracking-wider">No Image</span>
                    </div>
                @endif

                <span class="absolute top-4 left-4 sm:top-5 sm:left-5 bg-white/90 backdrop-blur-sm text-amber-900 text-[11px] sm:text-xs font-bold tracking-widest uppercase px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-md shadow-sm border border-amber-100">
                    {{ $product->category->name ?? 'Kopi' }}
                </span>

                @if($product->stock < 1)
                <span class="absolute top-4 right-4 sm:top-5 sm:right-5 bg-rose-600 text-white text-[11px] sm:text-xs font-bold tracking-widest uppercase px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-md shadow-sm">
                    Habis
                </span>
                @elseif($product->stock <= 5)
                <span class="absolute top-4 right-4 sm:top-5 sm:right-5 bg-amber-500 text-white text-[11px] sm:text-xs font-bold tracking-widest uppercase px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-md shadow-sm">
                    Hampir Habis
                </span>
                @endif
            </div>
        </div>

        {{-- Info Produk --}}
        <div class="flex flex-col">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-amber-950 mb-2 leading-tight">
                {{ $product->name }}
            </h1>

            <div class="flex flex-wrap items-center gap-3 mb-6">
                <span class="text-xl sm:text-2xl font-extrabold text-amber-800">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </span>
                <span class="inline-flex items-center text-xs font-semibold {{ $product->stock > 0 ? 'text-emerald-700 bg-emerald-50' : 'text-rose-700 bg-rose-50' }} px-2.5 py-1 rounded-md">
                    <i class="fa-solid {{ $product->stock > 0 ? 'fa-check-circle' : 'fa-circle-xmark' }} mr-1.5"></i>
                    {{ $product->stock > 0 ? $product->stock . ' Pcs tersedia' : 'Stok habis' }}
                </span>
            </div>

            <p class="text-amber-900/80 leading-relaxed mb-6 text-sm sm:text-base">
                {{ $product->description ?? 'Deskripsi produk belum tersedia.' }}
            </p>

            {{-- Spesifikasi ringkas --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-8">
                <div class="bg-amber-50/60 border border-amber-100 rounded-xl p-4 text-center">
                    <i class="fa-solid fa-weight-hanging text-amber-700 mb-1"></i>
                    <p class="text-xs font-semibold text-amber-950">250g</p>
                    <p class="text-[10px] uppercase tracking-wide text-amber-800/60">Berat</p>
                </div>
                <div class="bg-amber-50/60 border border-amber-100 rounded-xl p-4 text-center">
                    <i class="fa-solid fa-fire text-amber-700 mb-1"></i>
                    <p class="text-xs font-semibold text-amber-950">Medium</p>
                    <p class="text-[10px] uppercase tracking-wide text-amber-800/60">Roast</p>
                </div>
                <div class="bg-amber-50/60 border border-amber-100 rounded-xl p-4 text-center">
                    <i class="fa-solid fa-earth-asia text-amber-700 mb-1"></i>
                    <p class="text-xs font-semibold text-amber-950">Gayo, Aceh</p>
                    <p class="text-[10px] uppercase tracking-wide text-amber-800/60">Asal</p>
                </div>
            </div>

            {{-- Cerita Produk --}}
            <div class="bg-amber-50/60 border border-amber-100 rounded-2xl p-5 mb-6">
                <h3 class="font-bold text-amber-950 flex items-center gap-2 text-sm uppercase tracking-wider">
                    <i class="fa-solid fa-seedling"></i> Cerita Kopi Ini
                </h3>
                <p class="text-sm text-amber-900/80 mt-2 leading-relaxed">
                    Dipetik langsung dari petani di dataran tinggi, diproses secara alami, dan disangrai dalam batch kecil untuk menjaga kesegaran.
                </p>
            </div>

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

            <form action="/cart/add" method="POST" class="mt-auto" x-data="{ qty: 1 }">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <div class="flex flex-wrap items-center gap-4 mb-6">
                    <span class="text-xs font-bold tracking-wide text-amber-900 uppercase">Jumlah</span>
                    <div class="flex items-center border border-amber-200 rounded-xl overflow-hidden">
                        <button type="button" @click="qty = Math.max(1, qty - 1)"
                                class="w-10 h-10 flex items-center justify-center text-amber-800 hover:bg-amber-50 active:bg-amber-100 transition-colors">-</button>
                        <input type="number" name="quantity" x-model="qty" min="1" max="{{ $product->stock }}"
                               class="w-14 h-10 text-center border-x border-amber-200 outline-none text-sm font-semibold">
                        <button type="button" @click="qty = Math.min({{ max($product->stock, 1) }}, qty + 1)"
                                class="w-10 h-10 flex items-center justify-center text-amber-800 hover:bg-amber-50 active:bg-amber-100 transition-colors">+</button>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="submit"
                            class="w-full sm:w-auto flex-1 sm:flex-none px-10 py-4 bg-amber-800 hover:bg-amber-900 text-white font-bold rounded-xl uppercase tracking-widest text-sm shadow-md transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2 disabled:opacity-50 disabled:pointer-events-none disabled:hover:translate-y-0"
                            {{ $product->stock < 1 ? 'disabled' : '' }}>
                        <i class="fa-solid fa-cart-plus"></i>
                        {{ $product->stock < 1 ? 'Stok Habis' : 'Tambah ke Keranjang' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- You May Also Like (Carousel) --}}
    @if($related->isNotEmpty())
    <section class="mt-16 sm:mt-20 pt-10 sm:pt-12 border-t border-amber-100">
        <h2 class="text-xl sm:text-2xl font-black text-amber-950 mb-6">You May Also Like</h2>

        <div class="flex gap-4 overflow-x-auto pb-4 -mx-4 px-4 snap-x snap-mandatory scrollbar-hide
                    lg:grid lg:grid-cols-4 lg:gap-6 lg:overflow-visible lg:px-0 lg:snap-none">
            @foreach($related as $item)
            <a href="/katalog/{{ $item->id }}"
               class="group flex-shrink-0 w-[70vw] sm:w-[45vw] lg:w-auto snap-start
                      bg-white rounded-2xl border border-amber-100 overflow-hidden hover:shadow-lg transition-shadow">
                <div class="aspect-[4/3] bg-amber-50 overflow-hidden">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}"
                             class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-amber-300">
                            <i class="fa-solid fa-mug-hot text-3xl"></i>
                        </div>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-amber-950 text-sm line-clamp-1">{{ $item->name }}</h3>
                    <p class="text-amber-700 font-extrabold text-sm mt-1">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                    <span class="text-xs text-amber-800/70 font-medium mt-2 inline-block">
                        {{ $item->category->name ?? 'Kopi' }} · 250g
                    </span>
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Sticky Add to Cart (Mobile) --}}
    <div x-data="{ show: false }" x-intersect:leave="show = true" x-intersect:enter="show = false"
         class="fixed bottom-0 inset-x-0 z-40 bg-white border-t border-amber-200 p-4 shadow-2xl md:hidden transition-transform duration-300"
         :class="show ? 'translate-y-0' : 'translate-y-full'">
        <div class="flex items-center gap-4">
            <div class="flex-1">
                <p class="text-sm font-bold text-amber-950 truncate">{{ $product->name }}</p>
                <p class="text-lg font-extrabold text-amber-800">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            </div>
            <button type="submit" onclick="document.querySelector('#add-to-cart-form').submit()"
                    class="px-6 py-3 bg-amber-800 text-white rounded-full font-bold text-sm">
                <i class="fa-solid fa-cart-plus mr-2"></i> Keranjang
            </button>
        </div>
    </div>
</div>
@endsection

{{-- CSS untuk menyembunyikan scrollbar --}}
<style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>