@extends('layouts.app')

@section('title', $product->name . ' - Tepi Kopi')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Breadcrumb --}}
    <div class="mb-8 text-sm text-amber-800/70">
        <a href="/" class="hover:text-amber-700">Beranda</a>
        <span class="mx-2">/</span>
        <a href="/katalog" class="hover:text-amber-700">Katalog</a>
        <span class="mx-2">/</span>
        <span class="text-amber-950 font-medium">{{ $product->name }}</span>
    </div>

    <div class="grid md:grid-cols-2 gap-10 lg:gap-16">

        {{-- Gambar --}}
        <div class="relative aspect-square w-full bg-amber-50/50 rounded-3xl overflow-hidden border border-amber-100">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex flex-col items-center justify-center text-amber-300">
                    <i class="fa-solid fa-mug-hot text-6xl mb-3"></i>
                    <span class="text-sm font-medium uppercase tracking-wider">No Image</span>
                </div>
            @endif
            <span class="absolute top-5 left-5 bg-white/90 backdrop-blur-sm text-amber-900 text-xs font-bold tracking-widest uppercase px-3 py-1.5 rounded-md shadow-sm border border-amber-100">
                {{ $product->category->name ?? 'Kopi' }}
            </span>
        </div>

        {{-- Info --}}
        <div class="flex flex-col">
            <h1 class="text-3xl sm:text-4xl font-black text-amber-950 mb-3">{{ $product->name }}</h1>

            <div class="flex items-center gap-3 mb-6">
                <span class="text-2xl font-extrabold text-amber-800">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                <span class="inline-flex items-center text-xs font-semibold {{ $product->stock > 0 ? 'text-emerald-700 bg-emerald-50' : 'text-rose-700 bg-rose-50' }} px-2.5 py-1 rounded-md">
                    {{ $product->stock > 0 ? $product->stock . ' Pcs tersedia' : 'Stok habis' }}
                </span>
            </div>

            <p class="text-amber-900/80 leading-relaxed mb-8">
                {{ $product->description ?? 'Deskripsi produk belum tersedia.' }}
            </p>

            @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium">
                {{ session('success') }}
            </div>
            @endif

            <form action="/cart/add" method="POST" class="mt-auto" x-data="{ qty: 1 }">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <div class="flex items-center gap-4 mb-6">
                    <span class="text-xs font-bold tracking-wide text-amber-900 uppercase">Jumlah</span>
                    <div class="flex items-center border border-amber-200 rounded-xl overflow-hidden">
                        <button type="button" @click="qty = Math.max(1, qty - 1)" class="w-10 h-10 flex items-center justify-center text-amber-800 hover:bg-amber-50 transition-colors">-</button>
                        <input type="number" name="quantity" x-model="qty" min="1" max="{{ $product->stock }}"
                               class="w-14 h-10 text-center border-x border-amber-200 outline-none text-sm font-semibold">
                        <button type="button" @click="qty = Math.min({{ max($product->stock, 1) }}, qty + 1)" class="w-10 h-10 flex items-center justify-center text-amber-800 hover:bg-amber-50 transition-colors">+</button>
                    </div>
                </div>

                <button type="submit"
                        class="w-full sm:w-auto px-10 py-4 bg-amber-800 hover:bg-amber-900 text-white font-bold rounded-xl uppercase tracking-widest text-sm shadow-md transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2"
                        {{ $product->stock < 1 ? 'disabled' : '' }}>
                    <i class="fa-solid fa-cart-plus"></i>
                    {{ $product->stock < 1 ? 'Stok Habis' : 'Tambah ke Keranjang' }}
                </button>
            </form>
        </div>
    </div>

    {{-- Produk Lain --}}
    @if($related->isNotEmpty())
    <div class="mt-20 pt-12 border-t border-amber-100">
        <h2 class="text-2xl font-black text-amber-950 mb-8">Produk Lainnya</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($related as $item)
                <a href="/katalog/{{ $item->id }}" class="group bg-white rounded-2xl border border-amber-100 overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="aspect-square bg-amber-50/50 overflow-hidden">
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-110" alt="{{ $item->name }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-amber-300">
                                <i class="fa-solid fa-mug-hot text-3xl"></i>
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <p class="font-bold text-amber-950 text-sm line-clamp-1">{{ $item->name }}</p>
                        <p class="text-amber-700 font-extrabold text-sm mt-1">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection