@extends('layouts.app')

@section('title', 'Tentang Kami')

@section('content')

{{-- HERO --}}
<section class="relative bg-gray-900 py-24 sm:py-32 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-black/50 to-transparent"></div>
    <div class="relative max-w-4xl mx-auto px-5 text-center text-white">
        <span class="inline-block mb-5 px-4 py-2 rounded-full text-xs uppercase tracking-widest text-amber-300 border border-amber-400/40 bg-white/5">
            Cerita Kami
        </span>
        <h1 class="text-4xl sm:text-6xl font-extrabold leading-tight mb-6">Tentang TepiKopi.</h1>
        <p class="text-lg text-white/70 max-w-2xl mx-auto">
            Kami percaya secangkir kopi yang baik dimulai dari biji yang jujur, proses yang telaten,
            dan tangan-tangan petani lokal yang merawatnya sejak awal.
        </p>
    </div>
</section>

{{-- CERITA / MISI --}}
<section class="py-16 sm:py-24 bg-white">
    <div class="max-w-6xl mx-auto px-5 grid md:grid-cols-2 gap-12 items-center">
        <div class="relative">
            <div class="absolute inset-0 bg-amber-700/10 rounded-3xl -rotate-3"></div>
            <img src="https://images.unsplash.com/photo-1442512595331-e89e73853f31?auto=format&fit=crop&w=800&q=80"
                 class="relative z-10 w-full h-[320px] sm:h-[420px] object-cover rounded-3xl shadow-xl"
                 alt="Petani kopi memetik biji kopi">
        </div>
        <div>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-6">Perjalanan Kami</h2>
            <p class="text-gray-700 leading-relaxed mb-4">
                TepiKopi. lahir dari kecintaan sederhana pada secangkir kopi yang jujur rasanya.
                Berawal dari Bandung, kami mulai dengan menyusuri kebun-kebun kopi di dataran tinggi
                Indonesia, memilih langsung biji terbaik dari petani yang kami percaya.
            </p>
            <p class="text-gray-700 leading-relaxed">
                Hari ini, kami tumbuh menjadi tempat bagi para penikmat kopi untuk menemukan biji,
                alat seduh, dan aksesoris pilihan — semuanya dikurasi dengan standar yang sama sejak awal.
            </p>
        </div>
    </div>
</section>

{{-- NILAI-NILAI --}}
<section class="py-16 sm:py-24 bg-gray-50">
    <div class="max-w-6xl mx-auto px-5">
        <h2 class="text-center text-3xl sm:text-4xl font-extrabold text-gray-900 mb-12">Yang Kami Pegang Teguh</h2>
        <div class="grid sm:grid-cols-3 gap-8">
            @foreach([
                ['icon' => 'fa-seedling', 'title' => 'Sumber Terpercaya', 'desc' => 'Bekerja langsung dengan petani lokal untuk memastikan kualitas dan harga yang adil.'],
                ['icon' => 'fa-fire', 'title' => 'Sangrai Segar', 'desc' => 'Setiap batch disangrai dalam jumlah kecil agar aroma dan rasa tetap optimal.'],
                ['icon' => 'fa-heart', 'title' => 'Dibuat dengan Hati', 'desc' => 'Dari pemilihan biji sampai pengemasan, semua dikerjakan dengan penuh perhatian.'],
            ] as $nilai)
                <div class="bg-white p-8 rounded-2xl border border-gray-200 text-center shadow-sm">
                    <div class="w-14 h-14 mx-auto mb-5 rounded-xl bg-amber-700 text-white flex items-center justify-center text-xl">
                        <i class="fa-solid {{ $nilai['icon'] }}"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 text-lg mb-2">{{ $nilai['title'] }}</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">{{ $nilai['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<<<<<<< HEAD
{{-- CTA --}}
<section class="py-16 sm:py-20 bg-gray-900">
=======
{{-- =========================================================================
    CTA
========================================================================== --}}
<section class="py-16 sm:py-20 bg-amber-950">
>>>>>>> f49c3c8c1e4ea3e0021772047566c151cebf9953
    <div class="max-w-4xl mx-auto px-5 text-center">
        <h2 class="text-2xl sm:text-3xl font-extrabold text-white mb-4">Siap mencicipi kopi pilihan kami?</h2>
        <p class="text-amber-200/70 mb-8">Jelajahi katalog dan temukan kopi favoritmu hari ini.</p>
        <a href="/katalog" class="inline-flex items-center gap-2 px-8 py-4 bg-amber-700 hover:bg-amber-600 text-white font-bold rounded-full transition">
            Lihat Katalog <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
</section>
@endsection