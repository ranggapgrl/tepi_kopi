@extends('layouts.app')

@section('title', 'Tentang Kami')

@section('content')

{{-- HERO --}}
<section class="relative pt-16 sm:pt-24 pb-28 sm:pb-36 overflow-hidden" style="background:#1F150C;">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 grid lg:grid-cols-2 gap-12 items-center">
        <div class="text-white">
            <span class="inline-block mb-5 px-4 py-2 rounded-full text-xs uppercase tracking-[0.2em] border" style="color:#E1DCC9; border-color:rgba(225,220,201,0.35); background:rgba(255,255,255,0.05);">
                Sejak 2019
            </span>
            <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-semibold leading-tight mb-6">
                Kopi yang<br>bicara <span style="color:#E1DCC9;">jujur.</span>
            </h1>
            <p class="text-white/60 text-base sm:text-lg leading-relaxed max-w-md">
                TepiKopi. lahir dari keresahan sederhana: sulitnya menemukan toko alat kopi
                yang benar-benar paham kebutuhan penyeduh — dari pemula sampai kedai profesional.
            </p>
        </div>
        <div class="grid grid-cols-2 gap-4 h-72 sm:h-96">
            <img src="https://images.unsplash.com/photo-1442512595331-e89e73853f31?auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover rounded-2xl">
            <div class="flex flex-col gap-4">
                <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=600&q=80" class="w-full h-1/2 object-cover rounded-2xl">
                <img src="https://images.unsplash.com/photo-1511920170033-f8396924c348?auto=format&fit=crop&w=600&q=80" class="w-full h-1/2 object-cover rounded-2xl">
            </div>
        </div>
    </div>

    {{-- stat strip --}}
    <div class="max-w-5xl mx-auto px-5 relative -mb-24 sm:-mb-28 mt-16 sm:mt-20">
        <div class="bg-white rounded-3xl shadow-2xl grid grid-cols-2 sm:grid-cols-4 divide-x divide-black/5">
            @foreach([
                ['angka' => '6+', 'label' => 'Tahun Berkarya'],
                ['angka' => '40+', 'label' => 'Brand Alat Kopi'],
                ['angka' => '10rb+', 'label' => 'Alat Terkirim'],
                ['angka' => '4.9/5', 'label' => 'Rating Pelanggan'],
            ] as $stat)
                <div class="text-center py-7 sm:py-9 px-2">
                    <p class="font-display text-2xl sm:text-3xl font-semibold text-[#1F150C]">{{ $stat['angka'] }}</p>
                    <p class="text-[10px] sm:text-xs text-[#1F150C]/50 uppercase tracking-wider mt-1">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- spacer --}}
<div class="h-24 sm:h-28 bg-white"></div>

{{-- FEATURE ROWS --}}
<section class="pb-16 sm:pb-24 bg-white">
    <div class="max-w-6xl mx-auto px-5">
        <div class="text-center max-w-xl mx-auto mb-16">
            <span class="text-xs font-bold uppercase tracking-[0.2em]" style="color:#412D15;">Yang Kami Sediakan</span>
            <h2 class="font-display text-3xl sm:text-4xl font-semibold text-[#1F150C] mt-3">Perlengkapan Lengkap untuk Setiap Kebutuhan</h2>
        </div>

        <div class="space-y-16 sm:space-y-24">
            @foreach([
                ['num' => '01', 'title' => 'Mesin Kopi', 'desc' => 'Mesin espresso rumahan hingga kelas komersial untuk kedai, sudah diuji tim kami sebelum dijual.', 'img' => 'https://images.unsplash.com/photo-1511920170033-f8396924c348?auto=format&fit=crop&w=700&q=80', 'icon' => 'fa-mug-hot'],
                ['num' => '02', 'title' => 'Grinder', 'desc' => 'Penggiling manual & elektrik dengan hasil gilingan konsisten, cocok untuk semua metode seduh.', 'img' => 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?auto=format&fit=crop&w=700&q=80', 'icon' => 'fa-gear'],
                ['num' => '03', 'title' => 'Alat Seduh Manual', 'desc' => 'V60, Chemex, French press, dan aeropress pilihan untuk penyeduh rumahan maupun profesional.', 'img' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=700&q=80', 'icon' => 'fa-flask'],
            ] as $i => $item)
            <div class="grid md:grid-cols-2 gap-8 lg:gap-14 items-center {{ $i % 2 === 1 ? 'md:[&>*:first-child]:order-2' : '' }}">
                <div class="rounded-3xl overflow-hidden h-64 sm:h-80">
                    <img src="{{ $item['img'] }}" class="w-full h-full object-cover">
                </div>
                <div>
                    <span class="font-display text-4xl sm:text-5xl font-semibold" style="color:#E1DCC9; -webkit-text-stroke:1.5px #412D15;">{{ $item['num'] }}</span>
                    <h3 class="font-display text-2xl sm:text-3xl font-semibold text-[#1F150C] mt-3 mb-3">{{ $item['title'] }}</h3>
                    <p class="text-[#1F150C]/60 leading-relaxed max-w-md">{{ $item['desc'] }}</p>
                    <a href="/katalog" class="inline-flex items-center gap-2 font-bold mt-5 group" style="color:#412D15;">
                        Lihat produk <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- VALUES --}}
<section class="py-16 sm:py-24" style="background:#1F150C;">
    <div class="max-w-5xl mx-auto px-5">
        <div class="mb-14">
            <span class="text-xs font-bold uppercase tracking-[0.2em]" style="color:#E1DCC9;">Prinsip Kami</span>
            <h2 class="font-display text-3xl sm:text-4xl font-semibold text-white mt-3">Yang Kami Pegang Teguh</h2>
        </div>
        <div class="divide-y divide-white/10">
            @foreach([
                ['icon' => 'fa-magnifying-glass', 'title' => 'Kurasi Ketat', 'desc' => 'Setiap alat diuji coba tim kami sebelum masuk katalog, bukan sekadar ikut tren.'],
                ['icon' => 'fa-truck-fast', 'title' => 'Pengiriman Aman', 'desc' => 'Packing khusus untuk barang mudah pecah seperti kaca dan keramik.'],
                ['icon' => 'fa-headset', 'title' => 'Konsultasi Gratis', 'desc' => 'Bingung pilih alat yang cocok? Tim kami siap bantu rekomendasi sesuai kebutuhanmu.'],
            ] as $i => $nilai)
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-8 py-7 sm:py-9 group">
                    <span class="font-display text-2xl text-white/25 w-10 shrink-0">0{{ $i+1 }}</span>
                    <div class="w-12 h-12 shrink-0 rounded-xl text-white flex items-center justify-center text-lg" style="background:#412D15;">
                        <i class="fa-solid {{ $nilai['icon'] }}"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-white text-lg">{{ $nilai['title'] }}</h3>
                        <p class="text-white/50 text-sm mt-1 max-w-lg">{{ $nilai['desc'] }}</p>
                    </div>
                    <i class="fa-solid fa-arrow-right text-white/20 group-hover:text-white/50 group-hover:translate-x-1 transition-all hidden sm:block"></i>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- TESTIMONIALS --}}
<section class="py-16 sm:py-24 bg-white" x-data="{ i: 0, items: [
        {nama:'Dinda R.', peran:'Home Brewer', isi:'Beli V60 dan grinder di sini, kualitasnya jauh lebih baik dari yang saya kira. Pengemasan juga rapi banget.'},
        {nama:'Bagus P.', peran:'Pemilik Coffee Shop', isi:'Mesin espresso yang saya beli sudah dipakai operasional harian hampir setahun, awet dan after-sales-nya responsif.'},
        {nama:'Sarah W.', peran:'Barista Freelance', isi:'Tim TepiKopi. bantu saya pilih alat sesuai budget waktu itu, sangat membantu buat yang masih awam soal alat kopi.'}
    ] }">
    <div class="max-w-3xl mx-auto px-5 text-center">
        <span class="text-xs font-bold uppercase tracking-[0.2em]" style="color:#412D15;">Kata Mereka</span>
        <div class="mt-8 min-h-[180px] flex flex-col items-center justify-center">
            <i class="fa-solid fa-quote-left text-2xl mb-6" style="color:#E1DCC9;"></i>
            <p class="font-display text-xl sm:text-2xl text-[#1F150C] leading-relaxed" x-text="items[i].isi"></p>
            <p class="font-bold text-[#1F150C] mt-6" x-text="items[i].nama"></p>
            <p class="text-sm text-[#1F150C]/50" x-text="items[i].peran"></p>
        </div>
        <div class="flex justify-center gap-2 mt-8">
            <template x-for="(item, idx) in items" :key="idx">
                <button @click="i = idx" class="h-2 rounded-full transition-all" :class="i===idx ? 'w-8' : 'w-2 bg-black/10'" :style="i===idx ? 'background:#412D15' : ''"></button>
            </template>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-16 sm:py-20" style="background:#E1DCC9;">
    <div class="max-w-4xl mx-auto px-5 text-center" style="color:#1F150C;">
        <h2 class="font-display text-2xl sm:text-3xl font-semibold mb-4">Siap upgrade alat seduh kopimu?</h2>
        <p class="mb-8 text-[#1F150C]/60">Jelajahi katalog dan temukan mesin, grinder, atau alat seduh favoritmu.</p>
        <a href="/katalog" class="inline-flex items-center gap-2 px-8 py-4 font-bold rounded-full transition text-white" style="background:#412D15;">
            Lihat Katalog <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
</section>
@endsection