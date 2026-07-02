@extends('layouts.app')

@section('title', 'Tepi Kopi - Premium Coffee Experience')

@section('content')

{{-- =========================================================================
    HERO CAROUSEL SECTION
========================================================================== --}}
<section 
    x-data="{
        activeSlide: 0,
        slides: [
            { title: 'Biji Kopi\nTerbaik', img: '{{ asset('assets/images/corousel2.jpg') }}' },
            { title: 'Alat Kopi\nSempurna', img: '{{ asset('assets/images/corousel1.jpg') }}' },
            { title: 'Aroma\nSempurna', img: '{{ asset('assets/images/corousel3.jpg') }}' }
        ],
        init() {
            setInterval(() => {
                this.activeSlide = (this.activeSlide + 1) % this.slides.length
            }, 5000)
        }
    }"
    x-init="init()"
    class="relative min-h-screen bg-[#1a1512] overflow-hidden">

    {{-- Background Effect --}}
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-amber-900/40 via-[#1a1512] to-[#1a1512]"></div>

    <template x-for="(slide, index) in slides" :key="index">
        <div x-show="activeSlide === index"
            x-transition:enter="transition-all duration-1000"
            x-transition:enter-start="opacity-0 scale-105"
            x-transition:enter-end="opacity-100 scale-100"
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
                    <img :src="slide.img" class="relative z-10 w-full h-[280px] sm:h-[400px] md:h-[500px] object-cover rounded-3xl shadow-2xl" alt="Coffee Slide">
                </div>
            </div>
        </div>
    </template>
</section>

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
                    ['nama' => 'Aksesoris', 'img' => 'https://images.unsplash.com/photo-1507133750040-4a8f57021571?auto=format&fit=crop&w=500&q=80', 'link' => 'aksesori']
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

{{-- =========================================================================
    FOOTER SECTION
========================================================================== --}}
<footer class="bg-amber-950 text-amber-500 py-14">
    <div class="max-w-7xl mx-auto px-5 grid sm:grid-cols-2 lg:grid-cols-4 gap-10">
        <div>
            <h3 class="text-white text-xl font-bold mb-4">TepiKopi.</h3>
            <p class="text-sm">Menyajikan kualitas kopi terbaik dari petani lokal.</p>
        </div>
        <div>
            <h4 class="text-white font-bold mb-4">Navigasi</h4>
            <a href="/" class="block mb-2 hover:text-white">Beranda</a>
            <a href="/katalog" class="hover:text-white">Produk</a>
        </div>
        <div>
            <h4 class="text-white font-bold mb-4">Kategori</h4>
            <a href="/katalog?kategori=biji" class="block mb-2 hover:text-white">Biji Kopi</a>
            <a href="/katalog?kategori=alat" class="hover:text-white">Alat Seduh</a>
        </div>
        <div>
            <h4 class="text-white font-bold mb-4">Kontak</h4>
            <p class="text-white">Bandung, Indonesia</p>
        </div>
    </div>
</footer>

@endsection