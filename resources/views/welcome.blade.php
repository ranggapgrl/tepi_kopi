@extends('layouts.app')

@section('title', 'Premium Coffee Roastery')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

{{-- =========================================================================
    HERO CAROUSEL
========================================================================== --}}
<section
    x-data="{
        activeSlide: 0,
        paused: false,
        timer: null,
        slides: [
            { img: 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=1600&q=80', title: 'Kopi Berkualitas', subtitle: 'Dari petani langsung ke cangkir Anda' },
            { img: 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=1600&q=80', title: 'Sangrai Segar', subtitle: 'Dipanggang setiap hari, dikirim dalam 24 jam' },
            { img: 'https://images.unsplash.com/photo-1511920170033-f8396924c348?auto=format&fit=crop&w=1600&q=80', title: 'Racikan Sempurna', subtitle: 'Temukan alat seduh dan aksesoris terbaik' }
        ],
        next() { this.activeSlide = (this.activeSlide + 1) % this.slides.length },
        prev() { this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length },
        go(i) { this.activeSlide = i },
        start() {
            this.timer = setInterval(() => { if (!this.paused) this.next() }, 5000)
        }
    }"
    x-init="start()"
    @mouseenter="paused = true"
    @mouseleave="paused = false"
    class="relative min-h-screen flex items-center overflow-hidden bg-gray-900">

    {{-- Background Images --}}
    <template x-for="(slide, index) in slides" :key="index">
        <div
            x-show="activeSlide === index"
            x-transition:enter="transition-opacity ease-out duration-700"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-700"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="absolute inset-0 bg-cover bg-center"
            :style="'background-image: url(' + slide.img + ');'">
        </div>
    </template>

    <div class="absolute inset-0 bg-black/40 z-10"></div>

    <div class="relative z-20 max-w-7xl mx-auto px-5 sm:px-8 w-full">
        <div class="max-w-2xl text-white">
            <span class="inline-block mb-4 px-4 py-1.5 border border-amber-300/50 text-amber-300 text-xs font-semibold uppercase tracking-widest rounded-full">
                Specialty Coffee Roaster
            </span>
            <h1 class="text-4xl sm:text-6xl lg:text-7xl font-extrabold leading-tight mb-6"
                x-text="slides[activeSlide].title">
            </h1>
            <p class="text-lg sm:text-xl text-white/80 mb-8 max-w-xl"
               x-text="slides[activeSlide].subtitle">
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="/katalog" class="px-8 py-4 bg-amber-700 hover:bg-amber-800 text-white font-bold rounded-lg transition shadow-lg">
                    Belanja Sekarang
                </a>
                <a href="/about" class="px-8 py-4 border border-white/50 text-white hover:bg-white/10 font-bold rounded-lg transition">
                    Cerita Kami
                </a>
            </div>
        </div>
    </div>

    {{-- Navigasi --}}
    <button @click="prev()"
        class="hidden sm:flex absolute left-4 top-1/2 -translate-y-1/2 z-30 w-12 h-12 items-center justify-center rounded-full bg-white/10 hover:bg-amber-700 text-white backdrop-blur-md transition">
        <i class="fa-solid fa-chevron-left"></i>
    </button>
    <button @click="next()"
        class="hidden sm:flex absolute right-4 top-1/2 -translate-y-1/2 z-30 w-12 h-12 items-center justify-center rounded-full bg-white/10 hover:bg-amber-700 text-white backdrop-blur-md transition">
        <i class="fa-solid fa-chevron-right"></i>
    </button>

    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-30 flex gap-3">
        <template x-for="(slide, index) in slides" :key="index">
            <button @click="go(index)"
                class="h-2 rounded-full transition-all duration-300"
                :class="activeSlide === index ? 'w-8 bg-amber-500' : 'w-2 bg-white/50 hover:bg-white/80'">
            </button>
        </template>
    </div>
</section>

{{-- =========================================================================
    KATEGORI
========================================================================== --}}
<section class="py-16 sm:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-5">
        <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900">Jelajahi Koleksi Kami</h2>
            <p class="mt-3 text-gray-500 max-w-2xl mx-auto">Pilih dari biji kopi segar, alat seduh profesional, hingga aksesoris pelengkap.</p>
        </div>
        <div class="grid sm:grid-cols-3 gap-6">
            @foreach([
                ['title' => 'Biji Kopi', 'img' => 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?auto=format&fit=crop&w=600&q=80', 'link' => '1'],
                ['title' => 'Alat Kopi', 'img' => 'https://i.pinimg.com/736x/bd/ec/53/bdec533fd391ae00622fd6de2e9559b5.jpg', 'link' => '2'],
                ['title' => 'Aksesoris', 'img' => 'https://images.unsplash.com/photo-1507133750040-4a8f57021571?auto=format&fit=crop&w=600&q=80', 'link' => '3']
            ] as $cat)
            <a href="/katalog?kategori={{ $cat['link'] }}" class="group relative rounded-2xl overflow-hidden h-80 shadow-sm hover:shadow-xl transition">
                <img src="{{ $cat['img'] }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent p-6 flex flex-col justify-end">
                    <h3 class="text-2xl font-bold text-white">{{ $cat['title'] }}</h3>
                    <span class="text-amber-400 font-semibold mt-1">Lihat Semua →</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- =========================================================================
    PRODUK PILIHAN
========================================================================== --}}
<section class="py-16 sm:py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-5">
        <div class="flex items-end justify-between mb-12">
            <div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900">Produk Pilihan</h2>
                <p class="mt-2 text-gray-500">Favorit pelanggan minggu ini</p>
            </div>
            <a href="/katalog" class="hidden sm:inline-flex items-center gap-2 text-amber-700 font-semibold hover:text-amber-800">Lihat Semua <i class="fa-solid fa-arrow-right text-xs"></i></a>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse(collect($products ?? [])->take(4) as $product)
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow group">
                    <a href="/katalog/{{ $product->id }}" class="block relative h-56 overflow-hidden">
                        <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=500&q=80' }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="{{ $product->name }}">
                        @if($product->stock <= 0)
                            <span class="absolute top-3 left-3 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded">Habis</span>
                        @endif
                    </a>
                    <div class="p-5">
                        <h3 class="font-bold text-gray-900 truncate">{{ $product->name }}</h3>
                        <p class="text-amber-800 font-extrabold mt-1">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        <a href="/katalog/{{ $product->id }}" class="mt-4 inline-flex items-center justify-center w-full py-2.5 border border-gray-200 text-gray-700 hover:border-amber-300 hover:text-amber-700 font-semibold rounded-lg text-sm transition">
                            Detail Produk
                        </a>
                    </div>
                </div>
            @empty
                @foreach([['name' => 'Arabika Gayo', 'price' => 85000], ['name' => 'Robusta Lampung', 'price' => 65000], ['name' => 'V60 Dripper', 'price' => 150000], ['name' => 'French Press', 'price' => 220000]] as $item)
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="h-56 bg-gray-100 flex items-center justify-center text-gray-400">No Image</div>
                    <div class="p-5">
                        <h3 class="font-bold text-gray-900 truncate">{{ $item['name'] }}</h3>
                        <p class="text-amber-800 font-extrabold mt-1">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                        <a href="/katalog" class="mt-4 inline-flex items-center justify-center w-full py-2.5 border border-gray-200 text-gray-700 hover:border-amber-300 hover:text-amber-700 font-semibold rounded-lg text-sm transition">
                            Lihat Produk
                        </a>
                    </div>
                </div>
                @endforeach
            @endforelse
        </div>
    </div>
</section>

{{-- =========================================================================
    TESTIMONI
========================================================================== --}}
<section class="py-16 sm:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-5 text-center">
        <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900">Dipercaya oleh Pecinta Kopi</h2>
        <div class="mt-12 grid sm:grid-cols-3 gap-8">
            @foreach(['Andi', 'Bella', 'Chandra'] as $name)
                <div class="p-8 rounded-2xl border border-gray-100 hover:shadow-md transition">
                    <div class="text-amber-500 mb-4">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="text-gray-600 italic">"Kualitas kopinya luar biasa, pengiriman cepat, dan pelayanan ramah. Langganan tetap!"</p>
                    <p class="mt-6 font-bold text-gray-900">{{ $name }}</p>
                    <p class="text-sm text-gray-500">Pelanggan Setia</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- =========================================================================
    CTA
========================================================================== --}}
<section class="py-16 sm:py-20 bg-gray-900">
    <div class="max-w-4xl mx-auto px-5 text-center text-white">
        <h2 class="text-3xl font-extrabold">Siap Menikmati Kopi Terbaik?</h2>
        <p class="text-amber-200/70 mt-4">Jelajahi katalog kami dan dapatkan pengalaman kopi yang berbeda.</p>
        <a href="/katalog" class="mt-8 inline-flex items-center gap-2 px-8 py-4 bg-amber-700 hover:bg-amber-800 text-white font-bold rounded-full transition">
            Mulai Belanja <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
</section>

@endsection