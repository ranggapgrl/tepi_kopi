@extends('layouts.app')

@section('title', 'Tepi Kopi - Premium Coffee Experience')

@section('content')

<style>
    [x-cloak] { display: none !important; }
</style>

{{-- =========================================================================
    HERO CAROUSEL SECTION
========================================================================== --}}
<section
    x-data="{
        activeSlide: 0,
        paused: false,
        timer: null,
        slides: [
            { title: 'Biji Kopi\nTerbaik', img: '{{ asset('assets/images/corousel2.jpg') }}' },
            { title: 'Alat Kopi\nSempurna', img: '{{ asset('assets/images/corousel1.jpg') }}' },
            { title: 'Aroma\nSempurna', img: '{{ asset('assets/images/corousel3.jpg') }}' }
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
    class="relative min-h-[calc(100vh-4rem)] bg-[#1a1512] overflow-hidden">

    {{-- Background Effect --}}
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-amber-900/40 via-[#1a1512] to-[#1a1512]"></div>

    {{-- Slides: crossfade, both enter & leave animate together so there's no blank/overlap flash --}}
    <template x-for="(slide, index) in slides" :key="index">
        <div x-show="activeSlide === index"
            x-transition:enter="transition-opacity ease-out duration-700"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-700"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="absolute inset-0 flex items-center">

            <div class="max-w-7xl mx-auto px-5 sm:px-8 grid md:grid-cols-2 gap-10 items-center w-full py-20">
                <div class="text-white z-10">
                    <span class="inline-block mb-5 px-4 py-2 rounded-full text-xs uppercase tracking-widest text-amber-500 border border-amber-600/50 bg-amber-950/30">
                        Premium Selection
                    </span>
                    <h1 x-text="slide.title" class="text-4xl sm:text-5xl md:text-7xl lg:text-8xl font-black leading-tight whitespace-pre-line mb-8"></h1>
                    <a href="/katalog" class="inline-block px-8 py-4 bg-amber-700 rounded-lg text-white font-bold uppercase tracking-widest hover:bg-amber-600 transition">
                        Belanja Sekarang
                    </a>
                </div>

                <div class="relative">
                    <div class="absolute inset-0 bg-amber-700/20 rounded-3xl rotate-3"></div>
                    <img :src="slide.img"
                         class="relative z-10 w-full h-[280px] sm:h-[400px] md:h-[500px] object-cover rounded-3xl shadow-2xl scale-100 transition-transform duration-[6000ms] ease-out"
                         :class="activeSlide === index ? 'scale-110' : 'scale-100'"
                         alt="Coffee Slide">
                </div>
            </div>
        </div>
    </template>

    {{-- Prev / Next Arrows --}}
    <button @click="prev()"
        class="hidden sm:flex absolute left-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 items-center justify-center rounded-full bg-white/10 hover:bg-amber-700 text-white backdrop-blur-md transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
    </button>
    <button @click="next()"
        class="hidden sm:flex absolute right-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 items-center justify-center rounded-full bg-white/10 hover:bg-amber-700 text-white backdrop-blur-md transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
        </svg>
    </button>

    {{-- Dot Indicators --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 flex gap-3">
        <template x-for="(slide, index) in slides" :key="index">
            <button @click="go(index)"
                class="h-2 rounded-full transition-all duration-300"
                :class="activeSlide === index ? 'w-8 bg-amber-500' : 'w-2 bg-white/40 hover:bg-white/70'">
            </button>
        </template>
    </div>
</section>

{{-- Spacer so page content isn't hidden behind the fixed navbar on non-hero pages --}}

{{-- =========================================================================
    KATEGORI SECTION
========================================================================== --}}
<section class="py-16 sm:py-24 bg-amber-50">
    <div class="max-w-7xl mx-auto px-5">
        <h2 class="text-center text-3xl sm:text-4xl font-black text-amber-950 mb-12">Jelajahi Koleksi Kami</h2>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
                $kategori = [
                    ['nama' => 'Biji Kopi', 'img' => 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?auto=format&fit=crop&w=500&q=80', 'link' => 'biji'],
                    ['nama' => 'Alat Kopi', 'img' => 'https://i.pinimg.com/736x/bd/ec/53/bdec533fd391ae00622fd6de2e9559b5.jpg', 'link' => 'alat'],
                    ['nama' => 'Aksesoris', 'img' => 'https://i.pinimg.com/1200x/e0/cd/20/e0cd20f36782f5833c9e08c077551c1a.jpg', 'link' => 'aksesori']
                ];
            @endphp

            @foreach($kategori as $item)
                <div class="group relative overflow-hidden rounded-3xl h-[320px] sm:h-[400px]">
                    <img src="{{ $item['img'] }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-110" alt="{{ $item['nama'] }}">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex flex-col justify-end p-8 text-white">
                        <h3 class="text-2xl font-bold">{{ $item['nama'] }}</h3>
                        <a href="/katalog?kategori={{ $item['link'] }}" class="text-amber-400 font-bold hover:text-amber-300">Lihat Semua →</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- =========================================================================
    PRODUK PILIHAN SECTION
========================================================================== --}}
<section class="py-16 sm:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-5">
        <div class="flex items-end justify-between mb-12">
            <div>
                <h2 class="text-3xl sm:text-4xl font-black text-amber-950">Produk Pilihan</h2>
                <p class="text-amber-800/70 mt-2">Favorit para penikmat kopi minggu ini</p>
            </div>
            <a href="/katalog" class="hidden sm:inline-block text-amber-700 font-bold hover:text-amber-900 transition">Lihat Semua →</a>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Perhatikan penambahan collect(...)->take(4) di bawah ini --}}
            @forelse(collect($products ?? [])->take(4) as $product)
                <div class="group bg-amber-50 rounded-2xl overflow-hidden border border-amber-100 hover:shadow-xl transition-shadow">
                    <a href="/katalog/{{ $product->slug ?? $product->id }}" class="block relative h-56 overflow-hidden">
                        <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=500&q=80' }}"
                             class="w-full h-full object-cover transition duration-700 group-hover:scale-110"
                             alt="{{ $product->name }}">
                        @if(isset($product->stock) && $product->stock <= 0)
                            <span class="absolute top-3 left-3 bg-rose-700 text-white text-xs font-bold px-3 py-1 rounded-full">Stok Habis</span>
                        @endif
                    </a>
                    <div class="p-5">
                        <h3 class="font-bold text-amber-950 truncate">{{ $product->name }}</h3>
                        <p class="text-amber-700 font-black mt-1">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                        <a href="/katalog/{{ $product->slug ?? $product->id }}"
                           class="mt-4 block text-center px-4 py-2.5 bg-amber-800 hover:bg-amber-900 text-white text-sm font-bold rounded-lg transition">
                            Lihat Produk
                        </a>
                    </div>
                </div>
            @empty
                @foreach([
                    ['nama' => 'Arabika Gayo', 'harga' => 85000, 'img' => 'https://images.unsplash.com/photo-1587734195503-904fca47e0d9?auto=format&fit=crop&w=500&q=80'],
                    ['nama' => 'Robusta Lampung', 'harga' => 65000, 'img' => 'https://images.unsplash.com/photo-1497935586351-b67a49e012bf?auto=format&fit=crop&w=500&q=80'],
                    ['nama' => 'V60 Dripper', 'harga' => 150000, 'img' => 'https://images.unsplash.com/photo-1544787219-7f47ccb76574?auto=format&fit=crop&w=500&q=80'],
                    ['nama' => 'French Press', 'harga' => 220000, 'img' => 'https://images.unsplash.com/photo-1544145945-f90425340c7e?auto=format&fit=crop&w=500&q=80'],
                ] as $item)
                    <div class="group bg-amber-50 rounded-2xl overflow-hidden border border-amber-100 hover:shadow-xl transition-shadow">
                        <div class="block relative h-56 overflow-hidden">
                            <img src="{{ $item['img'] }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-110" alt="{{ $item['nama'] }}">
                        </div>
                        <div class="p-5">
                            <h3 class="font-bold text-amber-950 truncate">{{ $item['nama'] }}</h3>
                            <p class="text-amber-700 font-black mt-1">Rp{{ number_format($item['harga'], 0, ',', '.') }}</p>
                            <a href="/katalog" class="mt-4 block text-center px-4 py-2.5 bg-amber-800 hover:bg-amber-900 text-white text-sm font-bold rounded-lg transition">
                                Lihat Produk
                            </a>
                        </div>
                    </div>
                @endforeach
            @endforelse
        </div>

        <a href="/katalog" class="sm:hidden mt-8 block text-center text-amber-700 font-bold hover:text-amber-900 transition">Lihat Semua Produk →</a>
    </div>
</section>

{{-- =========================================================================
    TESTIMONI SECTION
========================================================================== --}}
<section class="py-16 sm:py-24 bg-white text-amber-950">
    <div class="max-w-7xl mx-auto px-5 text-center">
        <h2 class="text-3xl sm:text-4xl font-black mb-12">Apa Kata Penikmat Kopi?</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach(['Budi', 'Siti', 'Rangga'] as $nama)
                <div class="p-8 rounded-2xl bg-amber-50 border border-amber-100">
                    <p class="italic">"Kualitas kopi di TepiKopi benar-benar premium!"</p>
                    <h4 class="mt-6 font-bold text-amber-900">- {{ $nama }}</h4>
                </div>
            @endforeach
        </div>
    </div>
</section>

@endsection