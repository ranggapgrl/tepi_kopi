@extends('layouts.app')

@section('title', 'Crafting Your Daily Brew')

@section('content')
<!-- CSS Khusus Halaman Utama -->
<style>
    .giant-text {
        font-size: 26vw;
        line-height: 0.8;
        letter-spacing: -0.06em;
    }
</style>

<!-- Hero Section Carousel (Menggunakan Alpine.js) -->
<div x-data="{
        activeSlide: 0,
        slides: [
            {
                bgText: 'TEPI',
                topText: 'Coffee\nThat Moves\nWith You.',
                bottomText: 'Signature\nCollection\n2026',
                img: 'https://images.unsplash.com/photo-1497935586351-b67a49e012bf?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
            },
            {
                bgText: 'KOPI',
                topText: 'Master\nThe Art of\nPour Over.',
                bottomText: 'Manual Brew\nExperience\nDaily',
                img: 'https://images.unsplash.com/photo-1495474472207-464a8d910d65?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
            },
            {
                bgText: 'BEAN',
                topText: 'Fresh\nFrom The\nRoastery.',
                bottomText: 'Single Origin\nArabica\nBeans',
                img: 'https://images.unsplash.com/photo-1559525839-b184a4d698c7?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
            }
        ],
        next() {
            this.activeSlide = this.activeSlide === this.slides.length - 1 ? 0 : this.activeSlide + 1;
        },
        prev() {
            this.activeSlide = this.activeSlide === 0 ? this.slides.length - 1 : this.activeSlide - 1;
        }
    }" 
    x-init="setInterval(() => next(), 5000)" 
    class="relative w-full min-h-[calc(100vh-4rem)] overflow-hidden bg-[#faf8f5] flex flex-col items-center justify-center">

    <!-- Container Slides -->
    <div class="relative w-full h-full flex-grow flex items-center justify-center">
        <template x-for="(slide, index) in slides" :key="index">
            
            <div x-show="activeSlide === index"
                 x-transition:enter="transition ease-out duration-1000 transform"
                 x-transition:enter-start="opacity-0 scale-95 translate-x-12"
                 x-transition:enter-end="opacity-100 scale-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-700 transform absolute inset-0"
                 x-transition:leave-start="opacity-100 scale-100 translate-x-0"
                 x-transition:leave-end="opacity-0 scale-105 -translate-x-12"
                 class="absolute inset-0 flex flex-col items-center justify-center w-full h-full">

                <!-- Giant Typography Background -->
                <div class="absolute inset-0 flex items-center justify-center z-0 pointer-events-none mt-10">
                    <h1 class="giant-text font-black text-amber-900/[0.04] select-none" x-text="slide.bgText"></h1>
                </div>

                <!-- Teks Kiri Atas (Tampil di Desktop) -->
                <div class="absolute left-6 top-16 md:top-24 z-20 w-48 hidden sm:block">
                    <p class="text-[10px] font-bold tracking-[0.2em] text-amber-900 uppercase leading-relaxed whitespace-pre-line" x-text="slide.topText"></p>
                </div>

                <!-- Center Image (Fokus Utama) -->
                <div class="relative z-10 w-full max-w-2xl mx-auto h-[55vh] md:h-[65vh] flex items-end justify-center mt-10 md:mt-0">
                    <img :src="slide.img" alt="Tepi Kopi Hero" class="w-[85%] sm:w-[75%] h-full object-cover rounded-t-full shadow-2xl border-4 border-white/50">
                </div>

                <!-- Teks Kanan Bawah (Tampil di Desktop) -->
                <div class="absolute right-6 bottom-24 z-20 hidden md:block text-right">
                    <p class="text-[10px] font-bold tracking-[0.2em] text-amber-900 uppercase leading-relaxed whitespace-pre-line" x-text="slide.bottomText"></p>
                </div>
            </div>
        </template>
    </div>

    <!-- Navigasi Carousel (Dots & Arrows) -->
    <div class="absolute right-6 top-1/2 -translate-y-1/2 z-30 hidden md:flex flex-col gap-4">
        <button @click="prev()" class="w-10 h-10 rounded-full bg-white/50 hover:bg-white text-amber-950 backdrop-blur-sm transition shadow flex items-center justify-center">
            <i class="fa-solid fa-chevron-up text-xs"></i>
        </button>
        <button @click="next()" class="w-10 h-10 rounded-full bg-white/50 hover:bg-white text-amber-950 backdrop-blur-sm transition shadow flex items-center justify-center">
            <i class="fa-solid fa-chevron-down text-xs"></i>
        </button>
    </div>

    <!-- Indikator Dots -->
    <div class="absolute bottom-32 z-30 flex items-center gap-2">
        <template x-for="(slide, index) in slides" :key="index">
            <button @click="activeSlide = index" 
                    class="h-1.5 rounded-full transition-all duration-500"
                    :class="activeSlide === index ? 'w-8 bg-amber-900' : 'w-2 bg-amber-900/30'"></button>
        </template>
    </div>

    <!-- Action Buttons (Statis di Bawah) -->
    <div class="absolute bottom-8 z-30 flex flex-col sm:flex-row items-center gap-6 sm:left-24 w-full sm:w-auto px-6 sm:px-0">
        <a href="/products" class="w-full sm:w-auto text-center bg-amber-800 text-white px-8 py-3.5 text-xs font-bold uppercase tracking-widest hover:bg-amber-900 transition-all rounded-xl shadow-lg hover:-translate-y-0.5">
            Mulai Belanja
        </a>
        <a href="/products" class="text-xs font-bold uppercase tracking-widest border-b-2 border-amber-800 text-amber-950 pb-1 hover:text-amber-600 transition-colors">
            Lihat Katalog
        </a>
    </div>
</div>

<!-- Kategori Bar Hitam/Kopi Pekat -->
<section class="bg-amber-950 text-white py-12 px-6">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Kategori 1 -->
        <div class="flex items-center gap-6 group cursor-pointer" onclick="window.location.href='/products'">
            <div class="w-24 h-32 overflow-hidden rounded-lg">
                <img src="https://images.unsplash.com/photo-1511920170033-f8396924c348?auto=format&fit=crop&w=150&q=80" alt="Espresso" class="w-full h-full object-cover grayscale group-hover:grayscale-0 group-hover:scale-110 transition duration-700">
            </div>
            <div>
                <h3 class="text-lg font-bold tracking-widest uppercase mb-2">Espresso</h3>
                <p class="text-[10px] text-amber-200/60 mb-4 w-3/4 leading-relaxed">Cita rasa kuat dan intens untuk memulai hari Anda.</p>
                <span class="text-[9px] uppercase tracking-widest font-bold border-b border-amber-700 pb-1 group-hover:border-white transition text-amber-400 group-hover:text-white">Lihat Menu &rarr;</span>
            </div>
        </div>
        <!-- Kategori 2 -->
        <div class="flex items-center gap-6 group cursor-pointer" onclick="window.location.href='/products'">
            <div class="w-24 h-32 overflow-hidden rounded-lg">
                <img src="https://images.unsplash.com/photo-1495474472207-464a8d910d65?auto=format&fit=crop&w=150&q=80" alt="Manual Brew" class="w-full h-full object-cover grayscale group-hover:grayscale-0 group-hover:scale-110 transition duration-700">
            </div>
            <div>
                <h3 class="text-lg font-bold tracking-widest uppercase mb-2">Manual</h3>
                <p class="text-[10px] text-amber-200/60 mb-4 w-3/4 leading-relaxed">Kejernihan di setiap tetes seduhan manual brew kami.</p>
                <span class="text-[9px] uppercase tracking-widest font-bold border-b border-amber-700 pb-1 group-hover:border-white transition text-amber-400 group-hover:text-white">Lihat Menu &rarr;</span>
            </div>
        </div>
        <!-- Kategori 3 -->
        <div class="flex items-center gap-6 group cursor-pointer" onclick="window.location.href='/products'">
            <div class="w-24 h-32 overflow-hidden rounded-lg">
                <img src="https://images.unsplash.com/photo-1559525839-b184a4d698c7?auto=format&fit=crop&w=150&q=80" alt="Beans" class="w-full h-full object-cover grayscale group-hover:grayscale-0 group-hover:scale-110 transition duration-700">
            </div>
            <div>
                <h3 class="text-lg font-bold tracking-widest uppercase mb-2">Beans</h3>
                <p class="text-[10px] text-amber-200/60 mb-4 w-3/4 leading-relaxed">Biji kopi segar pilihan langsung dari petani lokal.</p>
                <span class="text-[9px] uppercase tracking-widest font-bold border-b border-amber-700 pb-1 group-hover:border-white transition text-amber-400 group-hover:text-white">Lihat Menu &rarr;</span>
            </div>
        </div>
    </div>
</section>

<!-- Fitur Layanan -->
<section class="border-t border-amber-100 py-10 bg-white mt-auto">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
        <div class="flex flex-col items-center justify-center gap-3">
            <div class="w-10 h-10 bg-amber-50 rounded-full flex items-center justify-center text-amber-800">
                <i class="fa-solid fa-truck-fast text-lg"></i>
            </div>
            <div>
                <h5 class="text-[10px] font-bold text-amber-950 uppercase tracking-widest">Pengiriman Cepat</h5>
                <p class="text-[9px] text-gray-500 mt-1">Aman sampai ke tujuan</p>
            </div>
        </div>
        <div class="flex flex-col items-center justify-center gap-3">
            <div class="w-10 h-10 bg-amber-50 rounded-full flex items-center justify-center text-amber-800">
                <i class="fa-solid fa-mug-hot text-lg"></i>
            </div>
            <div>
                <h5 class="text-[10px] font-bold text-amber-950 uppercase tracking-widest">Kopi Selalu Segar</h5>
                <p class="text-[9px] text-gray-500 mt-1">Diseduh langsung dari biji baru</p>
            </div>
        </div>
        <div class="flex flex-col items-center justify-center gap-3">
            <div class="w-10 h-10 bg-amber-50 rounded-full flex items-center justify-center text-amber-800">
                <i class="fa-solid fa-certificate text-lg"></i>
            </div>
            <div>
                <h5 class="text-[10px] font-bold text-amber-950 uppercase tracking-widest">Kualitas Terjamin</h5>
                <p class="text-[9px] text-gray-500 mt-1">Kurasi ahli kopi terbaik</p>
            </div>
        </div>
        <div class="flex flex-col items-center justify-center gap-3">
            <div class="w-10 h-10 bg-amber-50 rounded-full flex items-center justify-center text-amber-800">
                <i class="fa-solid fa-lock text-lg"></i>
            </div>
            <div>
                <h5 class="text-[10px] font-bold text-amber-950 uppercase tracking-widest">Pembayaran Aman</h5>
                <p class="text-[9px] text-gray-500 mt-1">100% privasi terjaga</p>
            </div>
        </div>
    </div>
</section>
@endsection