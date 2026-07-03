@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10">

    <div class="mb-6 sm:mb-8 text-xs sm:text-sm text-gray-500 overflow-x-auto whitespace-nowrap">
        <a href="/" class="hover:text-amber-700">Beranda</a>
        <span class="mx-2">/</span>
        <a href="/katalog" class="hover:text-amber-700">Katalog</a>
        @if($product->category)
        <span class="mx-2">/</span>
        <a href="/katalog?kategori={{ $product->category->id }}" class="hover:text-amber-700">{{ $product->category->name }}</a>
        @endif
        <span class="mx-2">/</span>
        <span class="text-gray-900 font-medium">{{ $product->name }}</span>
    </div>

    <div class="grid md:grid-cols-2 gap-8 lg:gap-16"
         x-data="{
            images: {{ Js::from($galleryImages) }},
            activeImage: {{ Js::from($galleryImages[0] ?? null) }},
            variants: {{ Js::from($product->variants->map(fn($v) => ['id' => $v->id, 'name' => $v->name, 'price' => (float) $v->price, 'stock' => $v->stock])) }},
            selectedVariant: null,
            qty: 1,
            init() { if (this.variants.length) { this.selectedVariant = this.variants[0]; } },
            get currentPrice() { return this.selectedVariant ? this.selectedVariant.price : {{ (float) $product->price }}; },
            get currentStock() { return this.selectedVariant ? this.selectedVariant.stock : {{ (int) $product->stock }}; }
         }">

        <div class="md:sticky md:top-24 md:self-start">
            <div class="relative aspect-square w-full bg-gray-50 rounded-2xl sm:rounded-3xl overflow-hidden border border-gray-200">
                <template x-if="activeImage">
                    <img :src="activeImage" :alt="'{{ $product->name }}'" class="w-full h-full object-cover">
                </template>
                <template x-if="!activeImage">
                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                        <i class="fa-solid fa-mug-hot text-5xl sm:text-6xl mb-3"></i>
                        <span class="text-xs sm:text-sm font-medium uppercase tracking-wider">No Image</span>
                    </div>
                </template>
                <span class="absolute top-4 left-4 sm:top-5 sm:left-5 bg-white/90 backdrop-blur-sm text-gray-900 text-[11px] sm:text-xs font-bold tracking-widest uppercase px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-md shadow-sm border border-gray-200">
                    {{ $product->category->name ?? 'Kopi' }}
                </span>
                <template x-if="currentStock < 1">
                    <span class="absolute top-4 right-4 sm:top-5 sm:right-5 bg-rose-600 text-white text-[11px] sm:text-xs font-bold tracking-widest uppercase px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-md shadow-sm">Habis</span>
                </template>
                <template x-if="currentStock >= 1 && currentStock <= 5">
                    <span class="absolute top-4 right-4 sm:top-5 sm:right-5 bg-amber-500 text-white text-[11px] sm:text-xs font-bold tracking-widest uppercase px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-md shadow-sm">Hampir Habis</span>
                </template>
            </div>
            <template x-if="images.length > 1">
                <div class="flex gap-3 mt-4 overflow-x-auto pb-1">
                    <template x-for="(img, idx) in images" :key="idx">
                        <button type="button" @click="activeImage = img"
                                class="w-16 h-16 sm:w-20 sm:h-20 flex-shrink-0 rounded-xl overflow-hidden border-2 transition-colors"
                                :class="activeImage === img ? 'border-amber-700' : 'border-gray-200 hover:border-amber-300'">
                            <img :src="img" class="w-full h-full object-cover">
                        </button>
                    </template>
                </div>
            </template>
        </div>

        <div class="flex flex-col">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-gray-900 mb-2 leading-tight">{{ $product->name }}</h1>

            <div class="flex items-center gap-2 mb-4">
                <div class="flex text-amber-500 text-sm">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fa-{{ $i <= round($averageRating) ? 'solid' : 'regular' }} fa-star"></i>
                    @endfor
                </div>
                <span class="text-xs text-gray-500">
                    @if($reviewsCount > 0) {{ number_format($averageRating, 1) }} ({{ $reviewsCount }} ulasan) @else Belum ada ulasan @endif
                </span>
            </div>

            <div class="flex flex-wrap items-center gap-3 mb-6">
                <span class="text-xl sm:text-2xl font-extrabold text-amber-700" x-text="'Rp ' + currentPrice.toLocaleString('id-ID')"></span>
                <span class="inline-flex items-center text-xs font-semibold px-2.5 py-1 rounded-md"
                      :class="currentStock > 0 ? 'text-emerald-700 bg-emerald-50' : 'text-rose-700 bg-rose-50'">
                    <i class="fa-solid mr-1.5" :class="currentStock > 0 ? 'fa-check-circle' : 'fa-circle-xmark'"></i>
                    <span x-text="currentStock > 0 ? currentStock + ' Pcs tersedia' : 'Stok habis'"></span>
                </span>
            </div>

            <p class="text-gray-700 leading-relaxed mb-6 text-sm sm:text-base">{{ $product->description ?? 'Deskripsi produk belum tersedia.' }}</p>

            @if($product->weight || $product->roast_level || $product->origin)
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-8">
                @if($product->weight)
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-center">
                    <i class="fa-solid fa-weight-hanging text-amber-700 mb-1"></i>
                    <p class="text-xs font-semibold text-gray-900">{{ $product->weight }}</p>
                    <p class="text-[10px] uppercase tracking-wide text-gray-500">Berat</p>
                </div>
                @endif
                @if($product->roast_level)
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-center">
                    <i class="fa-solid fa-fire text-amber-700 mb-1"></i>
                    <p class="text-xs font-semibold text-gray-900">{{ $product->roast_level }}</p>
                    <p class="text-[10px] uppercase tracking-wide text-gray-500">Roast</p>
                </div>
                @endif
                @if($product->origin)
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-center">
                    <i class="fa-solid fa-earth-asia text-amber-700 mb-1"></i>
                    <p class="text-xs font-semibold text-gray-900">{{ $product->origin }}</p>
                    <p class="text-[10px] uppercase tracking-wide text-gray-500">Asal</p>
                </div>
                @endif
            </div>
            @endif

            @if($product->story)
            <div class="bg-gray-50 border border-gray-200 rounded-2xl p-5 mb-6">
                <h3 class="font-bold text-gray-900 flex items-center gap-2 text-sm uppercase tracking-wider"><i class="fa-solid fa-seedling text-amber-700"></i> Cerita Kopi Ini</h3>
                <p class="text-sm text-gray-700 mt-2 leading-relaxed">{{ $product->story }}</p>
            </div>
            @endif

            @if($product->variants->isNotEmpty())
            <div class="mb-6" x-show="variants.length">
                <span class="text-xs font-bold tracking-wide text-gray-900 uppercase block mb-2">Pilih Varian</span>
                <div class="flex flex-wrap gap-2">
                    <template x-for="variant in variants" :key="variant.id">
                        <button type="button" @click="selectedVariant = variant"
                                class="px-4 py-2.5 rounded-xl border text-sm font-semibold transition-colors"
                                :class="selectedVariant && selectedVariant.id === variant.id ? 'bg-gray-900 border-gray-900 text-white' : 'bg-white border-gray-200 text-gray-700 hover:bg-gray-50'"
                                :disabled="variant.stock < 1"
                                :style="variant.stock < 1 ? 'opacity:.4; cursor:not-allowed;' : ''">
                            <span x-text="variant.name"></span>
                        </button>
                    </template>
                </div>
            </div>
            @endif

            @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
            </div>
            @endif

            <form id="add-to-cart-form" action="/cart/add" method="POST" class="mt-auto">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="variant_id" :value="selectedVariant ? selectedVariant.id : ''">

                <div class="flex flex-wrap items-center gap-4 mb-6">
                    <span class="text-xs font-bold tracking-wide text-gray-900 uppercase">Jumlah</span>
                    <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden">
                        <button type="button" @click="qty = Math.max(1, qty - 1)"
                                class="w-10 h-10 flex items-center justify-center text-gray-700 hover:bg-gray-50 transition">-</button>
                        <input type="number" name="quantity" x-model="qty" min="1" :max="currentStock"
                               class="w-14 h-10 text-center border-x border-gray-200 outline-none text-sm font-semibold">
                        <button type="button" @click="qty = Math.min(currentStock, qty + 1)"
                                class="w-10 h-10 flex items-center justify-center text-gray-700 hover:bg-gray-50 transition">+</button>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="submit"
                            class="w-full sm:w-auto flex-1 sm:flex-none px-10 py-4 bg-amber-700 hover:bg-amber-800 text-white font-bold rounded-xl uppercase tracking-widest text-sm shadow-md transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2 disabled:opacity-50 disabled:pointer-events-none"
                            :disabled="currentStock < 1">
                        <i class="fa-solid fa-cart-plus"></i>
                        <span x-text="currentStock < 1 ? 'Stok Habis' : 'Tambah ke Keranjang'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Ulasan Produk --}}
    <section class="mt-16 sm:mt-20 pt-10 sm:pt-12 border-t border-gray-200">
        <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900 mb-6">Ulasan Produk</h2>
        <div class="grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-1">
                <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6 text-center">
                    <p class="text-4xl font-extrabold text-gray-900">{{ number_format($averageRating, 1) }}</p>
                    <div class="flex justify-center text-amber-500 text-sm my-2">
                        @for($i = 1; $i <= 5; $i++) <i class="fa-{{ $i <= round($averageRating) ? 'solid' : 'regular' }} fa-star"></i> @endfor
                    </div>
                    <p class="text-xs text-gray-600">Berdasarkan {{ $reviewsCount }} ulasan</p>
                </div>
                @if($canReview)
                <div class="mt-6 bg-white border border-gray-200 rounded-2xl p-6" x-data="{ selectedRating: 0 }">
                    <h3 class="font-bold text-gray-900 mb-4 text-sm">Tulis Ulasanmu</h3>
                    <form action="{{ route('reviews.store', $product) }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="flex gap-1">
                            <template x-for="i in 5" :key="i">
                                <button type="button" @click="selectedRating = i" class="text-xl">
                                    <i class="fa-star" :class="i <= selectedRating ? 'fa-solid text-amber-500' : 'fa-regular text-gray-300'"></i>
                                </button>
                            </template>
                        </div>
                        <input type="hidden" name="rating" :value="selectedRating">
                        <textarea name="comment" rows="3" placeholder="Ceritakan pengalamanmu (opsional)"
                                  class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-sm text-gray-900 outline-none focus:ring-2 focus:ring-amber-300 resize-none"></textarea>
                        <button type="submit" :disabled="selectedRating < 1"
                                class="w-full px-4 py-2.5 bg-amber-700 hover:bg-amber-800 text-white font-bold rounded-xl text-sm shadow-md transition-colors disabled:opacity-40 disabled:pointer-events-none">Kirim Ulasan</button>
                    </form>
                </div>
                @endif
            </div>
            <div class="lg:col-span-2 space-y-4">
                @forelse($product->reviews->sortByDesc('created_at') as $review)
                <div class="border-b border-gray-100 pb-4">
                    <div class="flex items-center justify-between mb-1">
                        <p class="font-bold text-gray-900 text-sm">{{ $review->user->name ?? 'Pengguna' }}</p>
                        <span class="text-[11px] text-gray-400">{{ $review->created_at->translatedFormat('d M Y') }}</span>
                    </div>
                    <div class="flex text-amber-500 text-xs mb-2">
                        @for($i = 1; $i <= 5; $i++) <i class="fa-{{ $i <= $review->rating ? 'solid' : 'regular' }} fa-star"></i> @endfor
                    </div>
                    @if($review->comment) <p class="text-sm text-gray-700">{{ $review->comment }}</p> @endif
                </div>
                @empty
                <p class="text-sm text-gray-400">Belum ada ulasan untuk produk ini.</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Produk Lainnya --}}
    @if($related->isNotEmpty())
    <section class="mt-16 sm:mt-20 pt-10 sm:pt-12 border-t border-gray-200">
        <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900 mb-6">Produk Lainnya</h2>
        <div class="flex gap-4 overflow-x-auto pb-4 -mx-4 px-4 snap-x snap-mandatory scrollbar-hide lg:grid lg:grid-cols-4 lg:gap-6 lg:overflow-visible lg:px-0 lg:snap-none">
            @foreach($related as $item)
            <a href="/katalog/{{ $item->id }}" class="group flex-shrink-0 w-[70vw] sm:w-[45vw] lg:w-auto snap-start bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow">
                <div class="aspect-[4/3] bg-gray-50 overflow-hidden">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400"><i class="fa-solid fa-mug-hot text-3xl"></i></div>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-gray-900 text-sm line-clamp-1">{{ $item->name }}</h3>
                    <p class="text-amber-700 font-extrabold text-sm mt-1">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                    <span class="text-xs text-gray-500 font-medium mt-2 inline-block">{{ $item->category->name ?? 'Kopi' }}</span>
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif

    <div x-data="{ show: false }" x-intersect:leave="show = true" x-intersect:enter="show = false"
         class="fixed bottom-0 inset-x-0 z-40 bg-white border-t border-gray-200 p-4 shadow-2xl md:hidden transition-transform duration-300"
         :class="show ? 'translate-y-0' : 'translate-y-full'">
        <div class="flex items-center gap-4">
            <div class="flex-1">
                <p class="text-sm font-bold text-gray-900 truncate">{{ $product->name }}</p>
                <p class="text-lg font-extrabold text-amber-700">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            </div>
            <button type="submit" onclick="document.querySelector('#add-to-cart-form').requestSubmit()"
                    class="px-6 py-3 bg-amber-700 hover:bg-amber-800 text-white rounded-full font-bold text-sm">
                <i class="fa-solid fa-cart-plus mr-2"></i> Keranjang
            </button>
        </div>
    </div>
</div>
@endsection

<style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>