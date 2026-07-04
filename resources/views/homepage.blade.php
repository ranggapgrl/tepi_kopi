@extends('layouts.app')

@section('title', 'Premium Coffee Roastery')

@section('content')
<style>[x-cloak]{display:none!important;} @keyframes marquee{from{transform:translateX(0);}to{transform:translateX(-50%);}} .marquee-track{animation:marquee 28s linear infinite;}</style>

{{-- =========================================================================
    HERO
========================================================================== --}}
<section class="relative overflow-hidden" style="background:#1F150C;">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 grid lg:grid-cols-2 gap-10 items-center pt-14 pb-20 sm:pt-20 sm:pb-28 lg:min-h-[88vh]">

        <div class="text-white relative z-10 order-2 lg:order-1">
            <span class="inline-flex items-center gap-2 mb-6 px-4 py-1.5 border text-xs font-semibold uppercase tracking-[0.2em] rounded-full" style="border-color:rgba(225,220,201,0.35); color:#E1DCC9;">
                <span class="w-1.5 h-1.5 rounded-full" style="background:#E1DCC9;"></span> Sangrai Segar Setiap Hari
            </span>
            <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-semibold leading-[1.08] mb-6">
                Racik cangkir<br>terbaikmu, di<br><span style="color:#E1DCC9;">rumah sendiri.</span>
            </h1>
            <p class="text-white/60 text-base sm:text-lg mb-9 max-w-md leading-relaxed">
                Biji kopi pilihan, mesin espresso andal, dan alat seduh manual — semua yang kamu
                butuhkan untuk secangkir kopi kelas kedai.
            </p>
            <div class="flex flex-wrap items-center gap-5">
                <a href="/katalog" class="px-8 py-4 btn-primary font-bold rounded-full transition shadow-lg">Belanja Sekarang</a>
                <a href="/about" class="inline-flex items-center gap-2 text-white/80 hover:text-white font-semibold text-sm group">
                    Cerita Kami
                    <span class="w-8 h-8 rounded-full border border-white/25 flex items-center justify-center group-hover:bg-white/10 transition"><i class="fa-solid fa-arrow-right text-xs"></i></span>
                </a>
            </div>

            <div class="flex gap-8 mt-12 pt-8 border-t border-white/10 max-w-md">
                <div><p class="font-display text-2xl font-semibold">6+</p><p class="text-[11px] text-white/45 uppercase tracking-wider mt-0.5">Tahun</p></div>
                <div><p class="font-display text-2xl font-semibold">10rb+</p><p class="text-[11px] text-white/45 uppercase tracking-wider mt-0.5">Terkirim</p></div>
                <div><p class="font-display text-2xl font-semibold">4.9<i class="fa-solid fa-star text-xs ml-1" style="color:#E1DCC9;"></i></p><p class="text-[11px] text-white/45 uppercase tracking-wider mt-0.5">Rating</p></div>
            </div>
        </div>

        <div class="relative order-1 lg:order-2 h-[340px] sm:h-[440px] lg:h-[560px]">
            <div class="absolute top-0 right-0 w-[78%] h-[62%] rounded-[28px] overflow-hidden shadow-2xl">
                <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=900&q=80" class="w-full h-full object-cover">
            </div>
            <div class="absolute bottom-0 left-0 w-[58%] h-[48%] rounded-[24px] overflow-hidden shadow-2xl border-4" style="border-color:#1F150C;">
                <img src="https://images.unsplash.com/photo-1511920170033-f8396924c348?auto=format&fit=crop&w=700&q=80" class="w-full h-full object-cover">
            </div>
            <div class="absolute bottom-6 right-0 sm:right-4 w-44 sm:w-52 bg-white rounded-2xl shadow-2xl p-4 flex items-center gap-3">
                <div class="w-11 h-11 shrink-0 rounded-xl flex items-center justify-center text-white" style="background:#412D15;">
                    <i class="fa-solid fa-mug-hot"></i>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-wider text-[#1F150C]/45 font-semibold">Terlaris</p>
                    <p class="text-sm font-bold text-[#1F150C] leading-tight">V60 Dripper Set</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- =========================================================================
    BENTO — trust/value grid
========================================================================== --}}
<section class="py-14 sm:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-5 grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <div class="col-span-2 lg:col-span-2 rounded-2xl p-7 sm:p-9 flex flex-col justify-between min-h-[180px]" style="background:#E1DCC9;">
            <i class="fa-solid fa-truck-fast text-2xl" style="color:#412D15;"></i>
            <div>
                <h3 class="font-display text-xl sm:text-2xl font-semibold text-[#1F150C] mt-6">Pengiriman cepat & aman</h3>
                <p class="text-sm text-[#1F150C]/60 mt-1.5">Packing khusus untuk barang pecah belah, tiba dalam kondisi prima.</p>
            </div>
        </div>
        <div class="rounded-2xl p-6 sm:p-7 flex flex-col justify-between min-h-[180px] border border-black/5">
            <i class="fa-solid fa-shield-halved text-2xl" style="color:#412D15;"></i>
            <div>
                <h3 class="font-bold text-[#1F150C] mt-6">Garansi Resmi</h3>
                <p class="text-xs text-[#1F150C]/55 mt-1">Setiap mesin bergaransi.</p>
            </div>
        </div>
        <div class="rounded-2xl p-6 sm:p-7 flex flex-col justify-between min-h-[180px] text-white" style="background:#1F150C;">
            <i class="fa-solid fa-headset text-2xl" style="color:#E1DCC9;"></i>
            <div>
                <h3 class="font-bold mt-6">Konsultasi Gratis</h3>
                <p class="text-xs text-white/50 mt-1">Tim siap bantu 24/7.</p>
            </div>
        </div>
    </div>
</section>

{{-- =========================================================================
    CATEGORIES
========================================================================== --}}
<section class="pb-14 sm:pb-20 bg-white">
    <div class="max-w-7xl mx-auto px-5">
        <div class="flex items-end justify-between mb-8">
            <div>
                <span class="text-xs font-bold uppercase tracking-[0.2em]" style="color:#412D15;">Koleksi</span>
                <h2 class="font-display text-2xl sm:text-3xl font-semibold text-[#1F150C] mt-2">Jelajahi Kategori</h2>
            </div>
        </div>
        <div class="flex gap-5 overflow-x-auto scrollbar-none snap-x snap-mandatory pb-2 -mx-5 px-5">
            @foreach([
                ['title' => 'Biji Kopi', 'img' => 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?auto=format&fit=crop&w=600&q=80', 'link' => '1'],
                ['title' => 'Alat Kopi', 'img' => 'https://i.pinimg.com/736x/bd/ec/53/bdec533fd391ae00622fd6de2e9559b5.jpg', 'link' => '2'],
                ['title' => 'Aksesoris', 'img' => 'https://images.unsplash.com/photo-1507133750040-4a8f57021571?auto=format&fit=crop&w=600&q=80', 'link' => '3']
            ] as $cat)
            <a href="/katalog?kategori={{ $cat['link'] }}" class="group relative shrink-0 snap-start w-64 sm:w-72 h-80 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300">
                <img src="{{ $cat['img'] }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-700">
                <div class="absolute inset-0" style="background:linear-gradient(to top, rgba(0,0,0,0.75), transparent 55%);"></div>
                <div class="absolute inset-0 p-6 flex flex-col justify-end">
                    <h3 class="font-display text-2xl font-semibold text-white">{{ $cat['title'] }}</h3>
                    <span class="font-semibold mt-1 inline-flex items-center gap-1.5 text-sm" style="color:#E1DCC9;">
                        Lihat Semua <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                    </span>
                </div>
            </a>
            @endforeach
            <div class="shrink-0 w-1"></div>
        </div>
    </div>
</section>

{{-- =========================================================================
    PRODUCTS
========================================================================== --}}
<section class="py-14 sm:py-20" style="background:#E1DCC9;" x-data="{ tab: 'pilihan' }">
    <div class="max-w-7xl mx-auto px-5">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-5 mb-10">
            <div>
                <span class="text-xs font-bold uppercase tracking-[0.2em]" style="color:#412D15;">Belanja</span>
                <h2 class="font-display text-2xl sm:text-3xl font-semibold text-[#1F150C] mt-2">Produk Untukmu</h2>
            </div>
            <div class="flex gap-1 bg-white rounded-full p-1 w-fit shadow-sm">
                <button @click="tab='pilihan'" :class="tab==='pilihan' ? 'text-white' : 'text-[#1F150C]/60'" :style="tab==='pilihan' ? 'background:#412D15' : ''" class="px-4 sm:px-5 py-2 rounded-full text-xs sm:text-sm font-bold transition-colors">Pilihan</button>
                <button @click="tab='terlaris'" :class="tab==='terlaris' ? 'text-white' : 'text-[#1F150C]/60'" :style="tab==='terlaris' ? 'background:#412D15' : ''" class="px-4 sm:px-5 py-2 rounded-full text-xs sm:text-sm font-bold transition-colors">Terlaris</button>
            </div>
        </div>

        @if(!empty($products) && count($products) > 0)
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach(collect($products)->take(4) as $i => $product)
                <a href="/katalog/{{ $product->id }}"
                   class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300">
                    <div class="relative h-52 overflow-hidden">
                        <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=500&q=80' }}"
                             class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                             alt="{{ $product->name }}">
                        @if($i === 0)
                            <span class="absolute top-3 left-3 text-white text-[10px] font-bold px-2.5 py-1 rounded-full z-10" style="background:#412D15;">
                                <i class="fa-solid fa-fire mr-1"></i>Terlaris
                            </span>
                        @endif
                        @if($product->stock <= 0)
                            <span class="absolute top-3 right-3 bg-red-600 text-white text-[10px] font-bold px-2.5 py-1 rounded-full z-10 shadow">Habis</span>
                        @endif
                    </div>
                    <div class="p-5">
                        <div class="flex text-[10px] mb-1.5" style="color:#412D15;">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i>
                        </div>
                        <h3 class="text-base font-bold text-[#1F150C] leading-tight line-clamp-1">{{ $product->name }}</h3>
                        <div class="flex items-center justify-between mt-3">
                            <p class="font-extrabold" style="color:#412D15;">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            <span class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm transition-transform group-hover:scale-110" style="background:#1F150C;">
                                <i class="fa-solid fa-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        @else
        <div class="text-center py-12 text-[#1F150C]/50 bg-white rounded-2xl">
            <i class="fa-solid fa-coffee text-4xl mb-3 opacity-30"></i>
            <p>Belum ada produk pilihan saat ini.</p>
            <a href="/katalog" class="mt-3 inline-block font-medium text-sm hover:opacity-70" style="color:#412D15;">Lihat katalog kami →</a>
        </div>
        @endif
    </div>
</section>

{{-- =========================================================================
    TESTIMONIALS
========================================================================== --}}
<section class="py-14 sm:py-20 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-5 text-center mb-10">
        <span class="text-xs font-bold uppercase tracking-[0.2em]" style="color:#412D15;">Kata Mereka</span>
        <h2 class="font-display text-2xl sm:text-3xl font-semibold text-[#1F150C] mt-2">Dipercaya Pecinta Kopi</h2>
    </div>
    <div class="relative">
        <div class="flex gap-5 marquee-track w-max">
            @foreach(array_merge(
                [['name'=>'Andi','text'=>'Kualitas kopinya luar biasa, pengiriman cepat dan pelayanan ramah.'],
                 ['name'=>'Bella','text'=>'Grinder yang saya beli hasil gilingannya konsisten, cocok untuk manual brew.'],
                 ['name'=>'Chandra','text'=>'Mesin espressonya awet dipakai operasional kedai tiap hari.'],
                 ['name'=>'Dinda','text'=>'Tim TepiKopi. bantu saya pilih alat sesuai budget, sangat membantu.']],
                [['name'=>'Andi','text'=>'Kualitas kopinya luar biasa, pengiriman cepat dan pelayanan ramah.'],
                 ['name'=>'Bella','text'=>'Grinder yang saya beli hasil gilingannya konsisten, cocok untuk manual brew.'],
                 ['name'=>'Chandra','text'=>'Mesin espressonya awet dipakai operasional kedai tiap hari.'],
                 ['name'=>'Dinda','text'=>'Tim TepiKopi. bantu saya pilih alat sesuai budget, sangat membantu.']]
            ) as $t)
                <div class="w-80 sm:w-96 shrink-0 p-7 rounded-2xl border border-black/5" style="background:#E1DCC9;">
                    <div class="mb-4" style="color:#412D15;"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                    <p class="text-[#1F150C]/70 text-sm leading-relaxed mb-6">"{{ $t['text'] }}"</p>
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-xs text-white" style="background:#412D15;">{{ substr($t['name'],0,1) }}</div>
                        <p class="font-bold text-[#1F150C] text-sm">{{ $t['name'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- =========================================================================
    CTA
========================================================================== --}}
<section style="background:#1F150C;">
    <div class="max-w-7xl mx-auto grid lg:grid-cols-2 items-center">
        <div class="px-5 sm:px-10 py-16 sm:py-20 text-white">
            <h2 class="font-display text-3xl sm:text-4xl font-semibold leading-tight">Siap menikmati<br>kopi terbaik?</h2>
            <p class="mt-4 text-white/55 max-w-sm">Jelajahi katalog kami dan dapatkan pengalaman kopi yang berbeda, langsung diantar ke depan pintu.</p>
            <a href="/katalog" class="mt-8 inline-flex items-center gap-2 px-8 py-4 rounded-full font-bold transition" style="background:#E1DCC9; color:#1F150C;">
                Mulai Belanja <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
        <div class="hidden lg:block h-full min-h-[360px]">
            <img src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=1000&q=80" class="w-full h-full object-cover">
        </div>
    </div>
</section>

@endsection