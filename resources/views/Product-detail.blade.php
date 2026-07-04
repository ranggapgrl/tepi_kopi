@extends('layouts.app')

@section('title', $product->name)

@section('content')
<style>
    [x-cloak]{ display:none !important; }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10">

    {{-- Breadcrumb --}}
    <div class="mb-6 sm:mb-8 text-xs sm:text-sm text-[#1F150C]/45 overflow-x-auto whitespace-nowrap">
        <a href="/" class="hover:text-[#412D15]">Beranda</a>
        <span class="mx-2">/</span>
        <a href="/katalog" class="hover:text-[#412D15]">Katalog</a>
        @if($product->category)
        <span class="mx-2">/</span>
        <a href="/katalog?kategori={{ $product->category->id }}" class="hover:text-[#412D15]">{{ $product->category->name }}</a>
        @endif
        <span class="mx-2">/</span>
        <span class="text-[#1F150C] font-medium">{{ $product->name }}</span>
    </div>

    <div class="grid lg:grid-cols-[1.1fr_0.9fr] gap-8 lg:gap-14"
         x-data="{
            images: {{ Js::from($galleryImages) }},
            activeImage: {{ Js::from($galleryImages[0] ?? null) }},
            variants: {{ Js::from($product->variants->map(fn($v) => ['id' => $v->id, 'name' => $v->name, 'price' => (float) $v->price, 'stock' => $v->stock])) }},
            selectedVariant: null,
            qty: 1,
            lightboxOpen: false,
            infoTab: 'deskripsi',
            init() { if (this.variants.length) { this.selectedVariant = this.variants[0]; } },
            get currentPrice() { return this.selectedVariant ? this.selectedVariant.price : {{ (float) $product->price }}; },
            get currentStock() { return this.selectedVariant ? this.selectedVariant.stock : {{ (int) $product->stock }}; },
            nextImage() { let i = this.images.indexOf(this.activeImage); this.activeImage = this.images[(i + 1) % this.images.length]; },
            prevImage() { let i = this.images.indexOf(this.activeImage); this.activeImage = this.images[(i - 1 + this.images.length) % this.images.length]; }
         }">

        {{-- ============ GALLERY: main image + vertical thumbnail rail ============ --}}
        <div class="md:sticky md:top-24 md:self-start flex flex-col md:flex-row gap-3 sm:gap-4">

            {{-- THUMBNAIL RAIL --}}
            <template x-if="images.length > 1">
                <div class="order-2 md:order-1 flex md:flex-col gap-2.5 sm:gap-3 overflow-x-auto md:overflow-x-visible md:overflow-y-auto md:max-h-[560px] scrollbar-hide md:w-[72px] shrink-0">
                    <template x-for="(img, idx) in images" :key="idx">
                        <button type="button" @click="activeImage = img"
                                class="w-16 h-16 sm:w-[72px] sm:h-[72px] flex-shrink-0 rounded-xl overflow-hidden border-2 transition-colors"
                                :class="activeImage === img ? '' : 'border-black/10 hover:border-[#412D15]/40'"
                                :style="activeImage === img ? 'border-color:#412D15' : ''">
                            <img :src="img" class="w-full h-full object-cover">
                        </button>
                    </template>
                </div>
            </template>

            {{-- Main image --}}
            <div class="order-1 md:order-2 flex-1 min-w-0">
                <div class="relative aspect-square w-full rounded-2xl sm:rounded-3xl overflow-hidden border border-black/10 group" style="background:#E1DCC9;">
                    <template x-if="activeImage">
                        <img :src="activeImage" :alt="'{{ $product->name }}'"
                             @click="lightboxOpen = true"
                             class="w-full h-full object-cover cursor-zoom-in transition-transform duration-300 group-hover:scale-[1.02]">
                    </template>
                    <template x-if="!activeImage">
                        <div class="w-full h-full flex flex-col items-center justify-center" style="color:#412D15;">
                            <i class="fa-solid fa-mug-hot text-5xl sm:text-6xl mb-3 opacity-50"></i>
                            <span class="text-xs sm:text-sm font-medium uppercase tracking-wider opacity-50">No Image</span>
                        </div>
                    </template>

                    <template x-if="activeImage">
                        <button type="button" @click="lightboxOpen = true"
                                class="absolute bottom-4 right-4 w-9 h-9 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center text-[#1F150C] shadow-sm border border-black/10 hover:bg-white transition-colors">
                            <i class="fa-solid fa-magnifying-glass-plus text-xs"></i>
                        </button>
                    </template>

                    <span class="absolute top-4 left-4 sm:top-5 sm:left-5 bg-white/90 backdrop-blur-sm text-[#1F150C] text-[11px] sm:text-xs font-bold tracking-widest uppercase px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-md shadow-sm border border-black/10">
                        {{ $product->category->name ?? 'Kopi' }}
                    </span>
                    <template x-if="currentStock < 1">
                        <span class="absolute top-4 right-4 sm:top-5 sm:right-5 bg-rose-600 text-white text-[11px] sm:text-xs font-bold tracking-widest uppercase px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-md shadow-sm">Habis</span>
                    </template>
                    <template x-if="currentStock >= 1 && currentStock <= 5">
                        <span class="absolute top-4 right-4 sm:top-5 sm:right-5 text-white text-[11px] sm:text-xs font-bold tracking-widest uppercase px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-md shadow-sm" style="background:#412D15;">Hampir Habis</span>
                    </template>
                </div>
            </div>
        </div>

        {{-- ============ BUY BOX / DETAILS CARD  ============ --}}
        <div class="flex flex-col gap-6">

            @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
            </div>
            @endif

            {{-- BUY BOX --}}
            <div class="bg-white border border-black/10 rounded-2xl sm:rounded-3xl p-6 sm:p-7 shadow-sm">
                <h1 class="text-2xl sm:text-3xl font-black text-[#1F150C] mb-2 leading-tight">{{ $product->name }}</h1>

                <a href="#ulasan" class="inline-flex items-center gap-2 mb-5 group">
                    <div class="flex text-sm" style="color:#412D15;">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fa-{{ $i <= round($averageRating) ? 'solid' : 'regular' }} fa-star"></i>
                        @endfor
                    </div>
                    <span class="text-xs text-[#1F150C]/45 group-hover:text-[#412D15] transition-colors underline decoration-dotted">
                        @if($reviewsCount > 0) {{ number_format($averageRating, 1) }} ({{ $reviewsCount }} ulasan) @else Belum ada ulasan @endif
                    </span>
                </a>

                <div class="flex items-center justify-between gap-3 rounded-xl p-4 mb-6" style="background:#E1DCC9;">
                    <span class="text-xl sm:text-2xl font-black" style="color:#1F150C;" x-text="'Rp ' + currentPrice.toLocaleString('id-ID')"></span>
                    <span class="inline-flex items-center text-xs font-bold px-2.5 py-1.5 rounded-lg bg-white"
                          :class="currentStock > 0 ? 'text-emerald-700' : 'text-rose-700'">
                        <i class="fa-solid mr-1.5" :class="currentStock > 0 ? 'fa-check-circle' : 'fa-circle-xmark'"></i>
                        <span x-text="currentStock > 0 ? currentStock + ' Pcs tersedia' : 'Stok habis'"></span>
                    </span>
                </div>

                @if($product->variants->isNotEmpty())
                <div class="mb-6" x-show="variants.length">
                    <span class="text-xs font-bold tracking-wide text-[#1F150C]/60 uppercase block mb-2.5">Pilih Varian</span>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="variant in variants" :key="variant.id">
                            <button type="button" @click="selectedVariant = variant"
                                    class="px-4 py-2.5 rounded-xl border text-sm font-semibold transition-colors"
                                    :class="selectedVariant && selectedVariant.id === variant.id ? 'text-white' : 'bg-white border-black/10 text-[#1F150C]/70 hover:bg-black/[0.02]'"
                                    :style="selectedVariant && selectedVariant.id === variant.id ? 'background:#1F150C; border-color:#1F150C;' : ''"
                                    :disabled="variant.stock < 1"
                                    :class="variant.stock < 1 ? 'opacity-40 cursor-not-allowed' : ''">
                                <span x-text="variant.name"></span>
                            </button>
                        </template>
                    </div>
                </div>
                @endif

                <form id="add-to-cart-form" action="/cart/add" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="variant_id" :value="selectedVariant ? selectedVariant.id : ''">

                    <div class="flex items-center justify-between gap-4 mb-6">
                        <span class="text-xs font-bold tracking-wide text-[#1F150C]/60 uppercase">Jumlah</span>
                        <div class="flex items-center border border-black/10 rounded-xl overflow-hidden">
                            <button type="button" @click="qty = Math.max(1, qty - 1)"
                                    class="w-10 h-10 flex items-center justify-center text-[#1F150C]/70 hover:bg-black/[0.03] transition">-</button>
                            <input type="number" name="quantity" x-model="qty" min="1" :max="currentStock"
                                   class="w-14 h-10 text-center border-x border-black/10 outline-none text-sm font-semibold">
                            <button type="button" @click="qty = Math.min(currentStock, qty + 1)"
                                    class="w-10 h-10 flex items-center justify-center text-[#1F150C]/70 hover:bg-black/[0.03] transition">+</button>
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full px-10 py-4 btn-primary font-bold rounded-xl uppercase tracking-widest text-sm shadow-md transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2 disabled:opacity-50 disabled:pointer-events-none"
                            :disabled="currentStock < 1">
                        <i class="fa-solid fa-cart-plus"></i>
                        <span x-text="currentStock < 1 ? 'Stok Habis' : 'Tambah ke Keranjang'"></span>
                    </button>
                </form>
            </div>

            {{-- DETAILS --}}
            <div class="bg-white border border-black/10 rounded-2xl sm:rounded-3xl overflow-hidden">
                <div class="flex border-b border-black/10 overflow-x-auto scrollbar-hide">
                    <button @click="infoTab = 'deskripsi'" :class="infoTab==='deskripsi' ? 'border-b-2' : 'text-[#1F150C]/45'" :style="infoTab==='deskripsi' ? 'border-color:#412D15; color:#412D15;' : ''"
                            class="px-5 py-4 text-sm font-bold whitespace-nowrap transition-colors">Deskripsi</button>
                    @if($product->weight || $product->roast_level || $product->origin)
                    <button @click="infoTab = 'spesifikasi'" :class="infoTab==='spesifikasi' ? 'border-b-2' : 'text-[#1F150C]/45'" :style="infoTab==='spesifikasi' ? 'border-color:#412D15; color:#412D15;' : ''"
                            class="px-5 py-4 text-sm font-bold whitespace-nowrap transition-colors">Spesifikasi</button>
                    @endif
                    @if($product->story)
                    <button @click="infoTab = 'cerita'" :class="infoTab==='cerita' ? 'border-b-2' : 'text-[#1F150C]/45'" :style="infoTab==='cerita' ? 'border-color:#412D15; color:#412D15;' : ''"
                            class="px-5 py-4 text-sm font-bold whitespace-nowrap transition-colors">Cerita Kopi</button>
                    @endif
                </div>

                <div class="p-6 sm:p-7">
                    <div x-show="infoTab === 'deskripsi'" x-cloak>
                        <p class="text-[#1F150C]/70 leading-relaxed text-sm sm:text-base">{{ $product->description ?? 'Deskripsi produk belum tersedia.' }}</p>
                    </div>

                    @if($product->weight || $product->roast_level || $product->origin)
                    <div x-show="infoTab === 'spesifikasi'" x-cloak>
                        <div class="grid grid-cols-3 gap-4">
                            @if($product->weight)
                            <div class="text-center">
                                <i class="fa-solid fa-weight-hanging mb-1.5" style="color:#412D15;"></i>
                                <p class="text-sm font-bold text-[#1F150C]">{{ $product->weight }}</p>
                                <p class="text-[10px] uppercase tracking-wide text-[#1F150C]/40">Berat</p>
                            </div>
                            @endif
                            @if($product->roast_level)
                            <div class="text-center">
                                <i class="fa-solid fa-fire mb-1.5" style="color:#412D15;"></i>
                                <p class="text-sm font-bold text-[#1F150C]">{{ $product->roast_level }}</p>
                                <p class="text-[10px] uppercase tracking-wide text-[#1F150C]/40">Roast</p>
                            </div>
                            @endif
                            @if($product->origin)
                            <div class="text-center">
                                <i class="fa-solid fa-earth-asia mb-1.5" style="color:#412D15;"></i>
                                <p class="text-sm font-bold text-[#1F150C]">{{ $product->origin }}</p>
                                <p class="text-[10px] uppercase tracking-wide text-[#1F150C]/40">Asal</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($product->story)
                    <div x-show="infoTab === 'cerita'" x-cloak>
                        <h3 class="font-bold text-[#1F150C] flex items-center gap-2 text-sm uppercase tracking-wider mb-2.5">
                            <i class="fa-solid fa-seedling" style="color:#412D15;"></i> Cerita Kopi Ini
                        </h3>
                        <p class="text-sm text-[#1F150C]/70 leading-relaxed">{{ $product->story }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ================= LIGHTBOX / ZOOM GAMBAR ================= --}}
        <div x-show="lightboxOpen" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @keydown.window.escape="lightboxOpen = false"
             @keydown.window.arrow-right="lightboxOpen && nextImage()"
             @keydown.window.arrow-left="lightboxOpen && prevImage()"
             class="fixed inset-0 z-[100] bg-black/90 flex items-center justify-center p-4 sm:p-8">

            <div class="absolute inset-0" @click="lightboxOpen = false"></div>

            <button type="button" @click="lightboxOpen = false"
                    class="absolute top-4 right-4 sm:top-6 sm:right-6 w-11 h-11 bg-white/10 hover:bg-white/20 text-white rounded-full flex items-center justify-center z-10 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <template x-if="images.length > 1">
                <button type="button" @click.stop="prevImage()"
                        class="hidden sm:flex absolute left-4 sm:left-8 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/10 hover:bg-white/20 text-white rounded-full items-center justify-center z-10 transition-colors">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
            </template>
            <template x-if="images.length > 1">
                <button type="button" @click.stop="nextImage()"
                        class="hidden sm:flex absolute right-4 sm:right-8 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/10 hover:bg-white/20 text-white rounded-full items-center justify-center z-10 transition-colors">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </template>

            <img :src="activeImage" @click.stop
                 class="relative z-0 max-w-full max-h-[85vh] object-contain rounded-lg shadow-2xl">

            <template x-if="images.length > 1">
                <div class="absolute bottom-4 sm:bottom-6 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                    <template x-for="(img, idx) in images" :key="idx">
                        <button type="button" @click.stop="activeImage = img"
                                class="h-1.5 rounded-full transition-all"
                                :class="activeImage === img ? 'w-6 bg-white' : 'w-1.5 bg-white/40 hover:bg-white/60'">
                        </button>
                    </template>
                </div>
            </template>
        </div>
    </div>

    {{-- ================= ULASAN PRODUK ================= --}}
    <section id="ulasan" class="mt-16 sm:mt-20 pt-10 sm:pt-12 border-t border-black/10">
        <h2 class="font-display text-xl sm:text-2xl font-semibold text-[#1F150C] mb-8">Ulasan Produk</h2>

        {{-- RATING SUMMARY --}}
        <div class="flex flex-col sm:flex-row items-center gap-6 sm:gap-10 rounded-2xl p-6 sm:p-8 mb-8" style="background:#1F150C;">
            <div class="text-center shrink-0">
                <p class="font-display text-5xl font-semibold text-white">{{ number_format($averageRating, 1) }}</p>
                <div class="flex justify-center text-sm my-2" style="color:#E1DCC9;">
                    @for($i = 1; $i <= 5; $i++) <i class="fa-{{ $i <= round($averageRating) ? 'solid' : 'regular' }} fa-star"></i> @endfor
                </div>
                <p class="text-xs text-white/50">{{ $reviewsCount }} ulasan</p>
            </div>
            <div class="hidden sm:block w-px h-16 bg-white/10"></div>
            <p class="text-white/60 text-sm text-center sm:text-left leading-relaxed max-w-sm">
                Ulasan jujur dari pelanggan yang sudah membeli dan mencoba produk ini secara langsung.
            </p>
            @if($canReview)
            <a href="#tulis-ulasan" class="sm:ml-auto shrink-0 inline-flex items-center gap-2 px-6 py-3 rounded-full font-bold text-sm transition" style="background:#E1DCC9; color:#1F150C;">
                <i class="fa-solid fa-pen"></i> Tulis Ulasan
            </a>
            @endif
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            {{-- Reviews grid --}}
            <div class="lg:col-span-2 grid sm:grid-cols-2 gap-4 content-start">
                @forelse($product->reviews->sortByDesc('created_at') as $review)
                <div class="border border-black/10 rounded-2xl p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-9 h-9 shrink-0 rounded-full text-white flex items-center justify-center font-bold text-xs" style="background:#412D15;">
                            {{ strtoupper(substr($review->user->name ?? 'P', 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="font-bold text-[#1F150C] text-sm truncate">{{ $review->user->name ?? 'Pengguna' }}</p>
                            <span class="text-[11px] text-[#1F150C]/40">{{ $review->created_at->translatedFormat('d M Y') }}</span>
                        </div>
                    </div>
                    <div class="flex text-xs mb-2" style="color:#412D15;">
                        @for($i = 1; $i <= 5; $i++) <i class="fa-{{ $i <= $review->rating ? 'solid' : 'regular' }} fa-star"></i> @endfor
                    </div>
                    @if($review->comment) <p class="text-sm text-[#1F150C]/65 leading-relaxed">{{ $review->comment }}</p> @endif
                </div>
                @empty
                <p class="text-sm text-[#1F150C]/40 sm:col-span-2">Belum ada ulasan untuk produk ini.</p>
                @endforelse
            </div>

            {{-- Write review form --}}
            @if($canReview)
            <div id="tulis-ulasan" class="lg:col-span-1" x-data="{ selectedRating: 0 }">
                <div class="bg-white border border-black/10 rounded-2xl p-6 lg:sticky lg:top-24">
                    <h3 class="font-bold text-[#1F150C] mb-4 text-sm">Tulis Ulasanmu</h3>
                    <form action="{{ route('reviews.store', $product) }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="flex gap-1">
                            <template x-for="i in 5" :key="i">
                                <button type="button" @click="selectedRating = i" class="text-xl">
                                    <i class="fa-star" :class="i <= selectedRating ? 'fa-solid' : 'fa-regular text-black/20'" :style="i <= selectedRating ? 'color:#412D15' : ''"></i>
                                </button>
                            </template>
                        </div>
                        <input type="hidden" name="rating" :value="selectedRating">
                        <textarea name="comment" rows="3" placeholder="Ceritakan pengalamanmu (opsional)"
                                  class="w-full px-3 py-2.5 rounded-xl border border-black/10 bg-black/[0.02] text-sm text-[#1F150C] outline-none focus:ring-2 focus:ring-[#412D15]/20 resize-none"></textarea>
                        <button type="submit" :disabled="selectedRating < 1"
                                class="w-full px-4 py-2.5 btn-primary font-bold rounded-xl text-sm shadow-md transition-colors disabled:opacity-40 disabled:pointer-events-none">Kirim Ulasan</button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </section>

    {{-- ================= PRODUK LAINNYA ================= --}}
    @if($related->isNotEmpty())
    <section class="mt-16 sm:mt-20 pt-10 sm:pt-12 border-t border-black/10">
        <h2 class="font-display text-xl sm:text-2xl font-semibold text-[#1F150C] mb-8">Produk Lainnya</h2>
        <div class="flex gap-4 overflow-x-auto pb-4 -mx-4 px-4 snap-x snap-mandatory scrollbar-hide lg:grid lg:grid-cols-4 lg:gap-6 lg:overflow-visible lg:px-0 lg:snap-none">
            @foreach($related as $item)
            <a href="/katalog/{{ $item->id }}" class="group flex-shrink-0 w-[70vw] sm:w-[45vw] lg:w-auto snap-start bg-white rounded-2xl border border-black/10 overflow-hidden hover:shadow-lg transition-shadow">
                <div class="aspect-[4/3] overflow-hidden" style="background:#E1DCC9;">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                    @else
                        <div class="w-full h-full flex items-center justify-center" style="color:#412D15;"><i class="fa-solid fa-mug-hot text-3xl opacity-50"></i></div>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-[#1F150C] text-sm line-clamp-1">{{ $item->name }}</h3>
                    <p class="font-extrabold text-sm mt-1" style="color:#412D15;">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                    <span class="text-xs text-[#1F150C]/45 font-medium mt-2 inline-block">{{ $item->category->name ?? 'Kopi' }}</span>
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Sticky mobile buy bar --}}
    <div x-data="{ show: false }" x-intersect:leave="show = true" x-intersect:enter="show = false"
         class="fixed bottom-0 inset-x-0 z-40 bg-white border-t border-black/10 p-4 shadow-2xl md:hidden transition-transform duration-300"
         :class="show ? 'translate-y-0' : 'translate-y-full'">
        <div class="flex items-center gap-4">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-[#1F150C] truncate">{{ $product->name }}</p>
                <p class="text-lg font-extrabold" style="color:#412D15;">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            </div>
            <button type="submit" onclick="document.querySelector('#add-to-cart-form').requestSubmit()"
                    class="px-6 py-3 btn-primary rounded-full font-bold text-sm shrink-0">
                <i class="fa-solid fa-cart-plus mr-2"></i> Keranjang
            </button>
        </div>
    </div>
</div>
@endsection