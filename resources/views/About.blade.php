@extends('layouts.app')

@section('title', 'Tentang Kami')

@section('content')

{{-- HERO --}}
<section class="relative bg-gray-900 py-24 sm:py-32 lg:py-40 overflow-hidden">
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=1600&q=80"
             class="w-full h-full object-cover opacity-30" alt="Alat seduh kopi">
    </div>
    <div class="absolute inset-0 bg-gradient-to-b from-gray-900/60 via-gray-900/80 to-gray-900"></div>
    <div class="relative max-w-4xl mx-auto px-5 text-center text-white">
        <span class="inline-block mb-5 px-4 py-2 rounded-full text-xs uppercase tracking-widest text-amber-300 border border-amber-400/40 bg-white/5 backdrop-blur-sm">
            Cerita Kami
        </span>
        <h1 class="text-4xl sm:text-6xl lg:text-7xl font-extrabold leading-tight mb-6 tracking-tight">
            Tentang <span class="text-amber-400">TepiKopi.</span>
        </h1>
        <p class="text-base sm:text-lg text-white/70 max-w-2xl mx-auto leading-relaxed">
            Kami menghadirkan mesin kopi, alat seduh, dan aksesoris pilihan bagi siapa pun yang
            ingin menyeduh secangkir kopi terbaik — dari dapur rumah hingga kedai profesional.
        </p>
    </div>
</section>

{{-- STATISTIK --}}
<section class="bg-amber-800 py-10 sm:py-12">
    <div class="max-w-6xl mx-auto px-5 grid grid-cols-2 sm:grid-cols-4 gap-6 sm:gap-8 text-center text-white">
        @foreach([
            ['angka' => '6+', 'label' => 'Tahun Berkarya'],
            ['angka' => '40+', 'label' => 'Brand Alat Kopi'],
            ['angka' => '10rb+', 'label' => 'Alat Terkirim'],
            ['angka' => '4.9/5', 'label' => 'Rating Pelanggan'],
        ] as $stat)
            <div>
                <p class="text-3xl sm:text-4xl font-extrabold mb-1">{{ $stat['angka'] }}</p>
                <p class="text-xs sm:text-sm text-amber-100/80 uppercase tracking-wider">{{ $stat['label'] }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- CERITA / MISI --}}
<section class="py-16 sm:py-24 bg-white">
    <div class="max-w-6xl mx-auto px-5 grid md:grid-cols-2 gap-10 lg:gap-16 items-center">
        <div class="relative order-2 md:order-1">
            <div class="absolute -inset-2 sm:-inset-4 bg-amber-700/10 rounded-3xl -rotate-2"></div>
            <img src="https://images.unsplash.com/photo-1442512595331-e89e73853f31?auto=format&fit=crop&w=800&q=80"
                 class="relative z-10 w-full h-[280px] sm:h-[380px] lg:h-[460px] object-cover rounded-3xl shadow-xl"
                 alt="Koleksi alat seduh kopi">
            <div class="hidden sm:flex absolute -bottom-6 -right-6 z-20 bg-white rounded-2xl shadow-xl p-5 items-center gap-4 max-w-[220px]">
                <div class="w-11 h-11 shrink-0 rounded-full bg-amber-700 text-white flex items-center justify-center">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <p class="text-xs text-gray-600 leading-snug">Garansi resmi untuk setiap mesin & alat yang kami jual</p>
            </div>
        </div>
        <div class="order-1 md:order-2">
            <span class="text-amber-700 font-bold text-sm uppercase tracking-widest">Sejak 2019</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mt-3 mb-6">Kenapa TepiKopi.?</h2>
            <p class="text-gray-700 leading-relaxed mb-4">
                TepiKopi. berawal dari keresahan sederhana: sulitnya menemukan toko alat kopi
                yang benar-benar paham kebutuhan penyeduh — mulai dari pemula yang baru belajar
                manual brew, sampai kedai kopi yang butuh mesin espresso andal untuk operasional harian.
            </p>
            <p class="text-gray-700 leading-relaxed mb-6">
                Setiap produk yang kami jual — mesin espresso, grinder, dripper, hingga aksesoris —
                sudah melalui proses kurasi dan uji coba tim kami sendiri, memastikan kualitas dan
                daya tahannya sebelum sampai ke tanganmu.
            </p>
            <a href="/katalog" class="inline-flex items-center gap-2 text-amber-700 font-bold hover:text-amber-900 transition-colors group">
                Jelajahi katalog alat kopi
                <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>
</section>

{{-- KATEGORI PRODUK UNGGULAN --}}
<section class="py-16 sm:py-24 bg-gray-900 text-white">
    <div class="max-w-6xl mx-auto px-5">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="text-amber-400 font-bold text-sm uppercase tracking-widest">Yang Kami Sediakan</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold mt-3">Perlengkapan Lengkap untuk Setiap Kebutuhan</h2>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
            @foreach([
                ['icon' => 'fa-mug-hot', 'title' => 'Mesin Kopi', 'desc' => 'Mesin espresso rumahan hingga kelas komersial untuk kedai.'],
                ['icon' => 'fa-gear', 'title' => 'Grinder', 'desc' => 'Penggiling manual & elektrik dengan hasil gilingan konsisten.'],
                ['icon' => 'fa-flask', 'title' => 'Alat Seduh Manual', 'desc' => 'V60, Chemex, French press, dan aeropress pilihan.'],
                ['icon' => 'fa-mug-saucer', 'title' => 'Aksesoris', 'desc' => 'Cangkir, timbangan, kettle angsa, hingga tamper.'],
            ] as $kat)
                <div class="relative bg-white/5 border border-white/10 rounded-2xl p-6 lg:p-7 hover:bg-white/10 transition-colors">
                    <div class="w-12 h-12 mb-5 rounded-xl bg-amber-600 text-white flex items-center justify-center text-lg">
                        <i class="fa-solid {{ $kat['icon'] }}"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">{{ $kat['title'] }}</h3>
                    <p class="text-white/60 text-sm leading-relaxed">{{ $kat['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- NILAI-NILAI --}}
<section class="py-16 sm:py-24 bg-gray-50">
    <div class="max-w-6xl mx-auto px-5">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="text-amber-700 font-bold text-sm uppercase tracking-widest">Prinsip Kami</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mt-3">Yang Kami Pegang Teguh</h2>
        </div>
        <div class="grid sm:grid-cols-3 gap-6 lg:gap-8">
            @foreach([
                ['icon' => 'fa-magnifying-glass', 'title' => 'Kurasi Ketat', 'desc' => 'Setiap alat diuji coba tim kami sebelum masuk katalog, bukan sekadar ikut tren.'],
                ['icon' => 'fa-truck-fast', 'title' => 'Pengiriman Aman', 'desc' => 'Packing khusus untuk barang mudah pecah seperti kaca dan keramik.'],
                ['icon' => 'fa-headset', 'title' => 'Konsultasi Gratis', 'desc' => 'Bingung pilih alat yang cocok? Tim kami siap bantu rekomendasi sesuai kebutuhanmu.'],
            ] as $nilai)
                <div class="bg-white p-8 rounded-2xl border border-gray-200 text-center shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all">
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

{{-- TESTIMONI --}}
<section class="py-16 sm:py-24 bg-white">
    <div class="max-w-6xl mx-auto px-5">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="text-amber-700 font-bold text-sm uppercase tracking-widest">Kata Mereka</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mt-3">Dipercaya Penyeduh Kopi di Seluruh Indonesia</h2>
        </div>
        <div class="grid sm:grid-cols-3 gap-6 lg:gap-8">
            @foreach([
                ['nama' => 'Dinda R.', 'peran' => 'Home Brewer', 'isi' => 'Beli V60 dan grinder di sini, kualitasnya jauh lebih baik dari yang saya kira. Pengemasan juga rapi banget.'],
                ['nama' => 'Bagus P.', 'peran' => 'Pemilik Coffee Shop', 'isi' => 'Mesin espresso yang saya beli sudah dipakai operasional harian hampir setahun, awet dan after-sales-nya responsif.'],
                ['nama' => 'Sarah W.', 'peran' => 'Barista Freelance', 'isi' => 'Tim TepiKopi. bantu saya pilih alat sesuai budget waktu itu, sangat membantu buat yang masih awam soal alat kopi.'],
            ] as $t)
                <div class="bg-gray-50 rounded-2xl p-7 border border-gray-100">
                    <div class="flex gap-1 text-amber-500 mb-4 text-sm">
                        @for($i = 0; $i < 5; $i++)
                            <i class="fa-solid fa-star"></i>
                        @endfor
                    </div>
                    <p class="text-gray-700 text-sm leading-relaxed mb-6">&ldquo;{{ $t['isi'] }}&rdquo;</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-amber-700 text-white flex items-center justify-center font-bold text-sm">
                            {{ substr($t['nama'], 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold text-gray-900 text-sm">{{ $t['nama'] }}</p>
                            <p class="text-gray-500 text-xs">{{ $t['peran'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-16 sm:py-20" style="background-color: #e1dcc9;">
    <div class="max-w-4xl mx-auto px-5 text-center" style="color: #412D15;">
        <h2 class="text-2xl sm:text-3xl font-extrabold mb-4">Siap upgrade alat seduh kopimu?</h2>
        <p class="mb-8" style="color: rgba(65, 45, 21, 0.8);">Jelajahi katalog dan temukan mesin, grinder, atau alat seduh favoritmu.</p>
        <a href="/katalog" class="inline-flex items-center gap-2 px-8 py-4 bg-amber-700 hover:bg-amber-600 text-white font-bold rounded-full transition">
            Lihat Katalog <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
</section>
@endsection