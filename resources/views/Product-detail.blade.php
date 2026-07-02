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

        {{-- Gambar Produk dengan Galeri (Menggunakan Alpine.js) --}}
        @php
            $mainImage = $product->image ? asset('storage/' . $product->image) : '';
        @endphp
        
        <div class="md:sticky md:top-24 md:self-start" x-data="{ activeImage: '{{ $mainImage }}' }">
            {{-- Gambar Utama --}}
            <div class="relative aspect-square w-full bg-amber-50/50 rounded-2xl sm:rounded-3xl overflow-hidden border border-amber-100">
                @if($product->image)
                    <img :src="activeImage" alt="{{ $product->name }}"
                         class="w-full h-full object-cover transition-all duration-300">
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

            {{-- Thumbnail Galeri --}}
            @if($product->image)
            <div class="flex gap-3 mt-4 overflow-x-auto pb-2 scrollbar-hide">
                {{-- Thumbnail Gambar Utama --}}
                <button type="button" 
                        @click="activeImage = '{{ asset('storage/' . $product->image) }}'"
                        class="relative flex-shrink-0 w-20 h-20 rounded-xl overflow-hidden border-2 transition-colors"
                        :class="activeImage === '{{ asset('storage/' . $product->image) }}' ? 'border-amber-800' : 'border-transparent opacity-70 hover:opacity-100'">
                    <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover">
                </button>

                {{-- Thumbnail Gambar Tambahan (Asumsi relasi bernama 'images' atau 'galleries') --}}
                {{-- Ubah $product->images sesuai dengan nama relasi di Model Anda --}}
                @if(isset($product->images) && $product->images->count() > 0)
                    @foreach($product->images as $img)
                        <button type="button" 
                                @click="activeImage = '{{ asset('storage/' . $img->path) }}'"
                                class="relative flex-shrink-0 w-20 h-20 rounded-xl overflow-hidden border-2 transition-colors"
                                :class="activeImage === '{{ asset('storage/' . $img->path) }}' ? 'border-amber-800' : 'border-transparent opacity-70 hover:opacity-100'">
                            <img src="{{ asset('storage/' . $img->path) }}" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                @endif
            </div>
            @endif

            {{-- Spesifikasi ringkas (desktop di bawah gambar) --}}
            @if($product->weight || $product->roast_level || $product->origin)
            <div class="hidden md:grid grid-cols-3 gap-3 mt-6">
                @if($product->weight)
                <div class="bg-amber-50/60 border border-amber-100 rounded-xl p-3 text-center">
                    <i class="fa-solid fa-weight-hanging text-amber-700 mb-1"></i>
                    <p class="text-[11px] uppercase tracking-wide text-amber-800/60 font-semibold">Berat</p>
                    <p class="text-sm font-bold text-amber-950">{{ $product->weight }}</p>
                </div>
                @endif
                @if($product->roast_level)
                <div class="bg-amber-50/60 border border-amber-100 rounded-xl p-3 text-center">
                    <i class="fa-solid fa-fire text-amber-700 mb-1"></i>
                    <p class="text-[11px] uppercase tracking-wide text-amber-800/60 font-semibold">Roast</p>
                    <p class="text-sm font-bold text-amber-950">{{ $product->roast_level }}</p>
                </div>
                @endif
                @if($product->origin)
                <div class="bg-amber-50/60 border border-amber-100 rounded-xl p-3 text-center">
                    <i class="fa-solid fa-earth-asia text-amber-700 mb-1"></i>
                    <p class="text-[11px] uppercase tracking-wide text-amber-800/60 font-semibold">Asal</p>
                    <p class="text-sm font-bold text-amber-950">{{ $product->origin }}</p>
                </div>
                @endif
            </div>
            @endif
        </div>

        {{-- Info Produk --}}
        <div class="flex flex-col">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-amber-950 mb-2 leading-tight">
                {{ $product->name }}
            </h1>

            {{-- Rating Sistem --}}
            @php
                $rating = $product->rating ?? 0;
                $reviewsCount = $product->reviews_count ?? 0;
            @endphp
            <div class="flex items-center gap-2 mb-4">
                <div class="flex text-amber-500 text-sm">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fa-{{ $i <= round($rating) ? 'solid' : 'regular' }} fa-star"></i>
                    @endfor
                </div>
                <span class="text-xs sm:text-sm font-medium text-amber-800/60">
                    {{ $rating > 0 ? number_format($rating, 1) : 'Belum ada rating' }}
                    @if($reviewsCount > 0) 
                        <span class="mx-1">&bull;</span> ({{ $reviewsCount }} ulasan) 
                    @endif
                </span>
            </div>

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

            {{-- Spesifikasi ringkas (mobile, sebagai daftar) --}}
            @if($product->weight || $product->roast_level || $product->origin)
            <dl class="md:hidden grid grid-cols-1 gap-y-2 mb-6 text-sm border-y border-amber-100 py-4">
                @if($product->weight)
                <div class="flex justify-between">
                    <dt class="text-amber-800/60 font-medium">Berat</dt>
                    <dd class="text-amber-950 font-semibold">{{ $product->weight }}</dd>
                </div>
                @endif
                @if($product->roast_level)
                <div class="flex justify-between">
                    <dt class="text-amber-800/60 font-medium">Tingkat Roast</dt>
                    <dd class="text-amber-950 font-semibold">{{ $product->roast_level }}</dd>
                </div>
                @endif
                @if($product->origin)
                <div class="flex justify-between">
                    <dt class="text-amber-800/60 font-medium">Asal Biji</dt>
                    <dd class="text-amber-950 font-semibold">{{ $product->origin }}</dd>
                </div>
                @endif
            </dl>
            @endif

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
                    <span class="text-xs text-amber-800/50" x-show="qty >= {{ max($product->stock, 1) }}" style="display: none;">
                        Maks. stok tercapai
                    </span>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="submit"
                            class="w-full sm:w-auto flex-1 sm:flex-none px-10 py-4 bg-amber-800 hover:bg-amber-900 text-white font-bold rounded-xl uppercase tracking-widest text-sm shadow-md transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2 disabled:opacity-50 disabled:pointer-events-none disabled:hover:translate-y-0"
                            {{ $product->stock < 1 ? 'disabled' : '' }}>
                        <i class="fa-solid fa-cart-plus"></i>
                        {{ $product->stock < 1 ? 'Stok Habis' : 'Tambah ke Keranjang' }}
                    </button>

                    <button type="button"
                            onclick="navigator.share ? navigator.share({title: '{{ $product->name }}', url: window.location.href}) : navigator.clipboard.writeText(window.location.href)"
                            class="w-full sm:w-auto px-6 py-4 border border-amber-200 hover:bg-amber-50 text-amber-800 font-bold rounded-xl uppercase tracking-widest text-sm transition-colors flex items-center justify-center gap-2">
                        <i class="fa-solid fa-share-nodes"></i>
                        <span class="sm:hidden">Bagikan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Produk Lain --}}
    @if($related->isNotEmpty())
    <div class="mt-16 sm:mt-20 pt-10 sm:pt-12 border-t border-amber-100">
        <h2 class="text-xl sm:text-2xl font-black text-amber-950 mb-6 sm:mb-8">Produk Lainnya</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach($related as $item)
                <a href="/katalog/{{ $item->id }}" class="group bg-white rounded-xl sm:rounded-2xl border border-amber-100 overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="aspect-square bg-amber-50/50 overflow-hidden">
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-110" alt="{{ $item->name }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-amber-300">
                                <i class="fa-solid fa-mug-hot text-2xl sm:text-3xl"></i>
                            </div>
                        @endif
                    </div>
                    <div class="p-3 sm:p-4">
                        <p class="font-bold text-amber-950 text-xs sm:text-sm line-clamp-1">{{ $item->name }}</p>
                        <p class="text-amber-700 font-extrabold text-xs sm:text-sm mt-1">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection