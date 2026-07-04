@extends('layouts.app')

@section('title', 'Kontak Kami')

@section('content')
<style>[x-cloak]{display:none!important;}</style>

{{-- HERO --}}
<section class="pt-16 sm:pt-20 pb-8 sm:pb-10 bg-white">
    <div class="max-w-6xl mx-auto px-5">
        <span class="text-xs font-bold uppercase tracking-[0.2em]" style="color:#412D15;">Hubungi Kami</span>
        <h1 class="font-display text-3xl sm:text-5xl font-semibold text-[#1F150C] mt-3 max-w-xl">
            Ada yang bisa kami bantu?
        </h1>
    </div>
</section>

{{-- MAP + CONTACT CARD --}}
<section class="bg-white pb-16 sm:pb-24">
    <div class="max-w-6xl mx-auto px-5">
        <div class="relative rounded-3xl overflow-hidden h-[360px] sm:h-[420px]">
            <iframe
                class="w-full h-full grayscale-[30%]"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                src="https://maps.google.com/maps?q=Bandung%2C%20Jawa%20Barat&t=&z=13&ie=UTF8&iwloc=&output=embed">
            </iframe>

            <div class="absolute bottom-4 left-4 right-4 sm:left-6 sm:right-auto sm:bottom-6 sm:w-96 bg-white rounded-2xl shadow-2xl p-5 sm:p-6">
                <div class="flex items-start gap-3 mb-4">
                    <div class="w-10 h-10 shrink-0 rounded-xl text-white flex items-center justify-center" style="background:#412D15;">
                        <i class="fa-solid fa-location-dot text-sm"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-[#1F150C] text-sm">TepiKopi. Store</h3>
                        <p class="text-xs text-[#1F150C]/55 mt-0.5">Jl. Kopi Tepi No. 12, Bandung, Jawa Barat</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3 pt-4 border-t border-black/5 text-xs">
                    <div>
                        <p class="text-[#1F150C]/40 uppercase tracking-wider text-[10px] font-semibold mb-1">Jam Buka</p>
                        <p class="text-[#1F150C] font-medium">Sen-Sab 09.00-18.00</p>
                    </div>
                    <div>
                        <p class="text-[#1F150C]/40 uppercase tracking-wider text-[10px] font-semibold mb-1">Telepon</p>
                        <p class="text-[#1F150C] font-medium">+62 812-3456-7890</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CONTACTS --}}
<section class="pb-16 sm:pb-24 bg-white">
    <div class="max-w-6xl mx-auto px-5 grid sm:grid-cols-3 gap-4">
        <a href="mailto:halo@tepikopi.com" class="flex items-center gap-4 p-5 rounded-2xl border border-black/10 hover:border-[#412D15]/30 hover:shadow-md transition">
            <div class="w-11 h-11 shrink-0 rounded-full flex items-center justify-center" style="background:#E1DCC9; color:#412D15;"><i class="fa-solid fa-envelope"></i></div>
            <div>
                <p class="text-xs text-[#1F150C]/45 uppercase tracking-wider font-semibold">Email</p>
                <p class="font-bold text-[#1F150C] text-sm">halo@tepikopi.com</p>
            </div>
        </a>
        <a href="https://wa.me/" target="_blank" class="flex items-center gap-4 p-5 rounded-2xl border border-black/10 hover:border-[#412D15]/30 hover:shadow-md transition">
            <div class="w-11 h-11 shrink-0 rounded-full flex items-center justify-center" style="background:#E1DCC9; color:#412D15;"><i class="fa-brands fa-whatsapp"></i></div>
            <div>
                <p class="text-xs text-[#1F150C]/45 uppercase tracking-wider font-semibold">WhatsApp</p>
                <p class="font-bold text-[#1F150C] text-sm">+62 812-3456-7890</p>
            </div>
        </a>
        <a href="https://instagram.com" target="_blank" class="flex items-center gap-4 p-5 rounded-2xl border border-black/10 hover:border-[#412D15]/30 hover:shadow-md transition">
            <div class="w-11 h-11 shrink-0 rounded-full flex items-center justify-center" style="background:#E1DCC9; color:#412D15;"><i class="fa-brands fa-instagram"></i></div>
            <div>
                <p class="text-xs text-[#1F150C]/45 uppercase tracking-wider font-semibold">Instagram</p>
                <p class="font-bold text-[#1F150C] text-sm">@tepikopi.id</p>
            </div>
        </a>
    </div>
</section>

{{-- FORM --}}
<section class="py-16 sm:py-24" style="background:#E1DCC9;">
    <div class="max-w-2xl mx-auto px-5">
        <div class="text-center mb-10">
            <h2 class="font-display text-2xl sm:text-3xl font-semibold text-[#1F150C]">Kirim Pesan Langsung</h2>
            <p class="text-[#1F150C]/55 text-sm mt-2">Kami akan membalas dalam 1x24 jam kerja.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 px-5 py-4 rounded-xl text-sm font-medium" style="background:#f3f8f1; border:1px solid #cfe6c9; color:#2f5e29;">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="/contact" class="bg-white rounded-2xl p-6 sm:p-8 space-y-5 shadow-sm">
            @csrf
            <div class="grid sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-bold text-[#1F150C] mb-2">Nama</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-3 rounded-lg border border-black/10 focus:outline-none focus:ring-2 focus:ring-[#412D15]/20 focus:border-[#412D15]/40 bg-black/[0.02] text-sm">
                    @error('name') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-[#1F150C] mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 rounded-lg border border-black/10 focus:outline-none focus:ring-2 focus:ring-[#412D15]/20 focus:border-[#412D15]/40 bg-black/[0.02] text-sm">
                    @error('email') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-[#1F150C] mb-2">Subjek</label>
                <input type="text" name="subject" value="{{ old('subject') }}" required
                       class="w-full px-4 py-3 rounded-lg border border-black/10 focus:outline-none focus:ring-2 focus:ring-[#412D15]/20 focus:border-[#412D15]/40 bg-black/[0.02] text-sm">
                @error('subject') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-[#1F150C] mb-2">Pesan</label>
                <textarea name="message" rows="5" required
                          class="w-full px-4 py-3 rounded-lg border border-black/10 focus:outline-none focus:ring-2 focus:ring-[#412D15]/20 focus:border-[#412D15]/40 bg-black/[0.02] text-sm resize-none">{{ old('message') }}</textarea>
                @error('message') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <button type="submit"
                    class="w-full py-3.5 btn-primary font-bold rounded-lg uppercase tracking-widest transition">
                Kirim Pesan
            </button>
        </form>
    </div>
</section>

{{-- FAQ ACCORDION --}}
<section class="py-16 sm:py-24 bg-white">
    <div class="max-w-2xl mx-auto px-5">
        <div class="text-center mb-10">
            <span class="text-xs font-bold uppercase tracking-[0.2em]" style="color:#412D15;">FAQ</span>
            <h2 class="font-display text-2xl sm:text-3xl font-semibold text-[#1F150C] mt-3">Pertanyaan Umum</h2>
        </div>
        <div class="space-y-3" x-data="{ open: 0 }">
            @foreach([
                ['q' => 'Berapa lama waktu pengiriman?', 'a' => 'Pengiriman umumnya memakan waktu 2-4 hari kerja tergantung lokasi tujuan.'],
                ['q' => 'Apakah semua mesin bergaransi?', 'a' => 'Ya, seluruh mesin dan alat elektronik yang kami jual sudah termasuk garansi resmi dari brand terkait.'],
                ['q' => 'Bisakah konsultasi sebelum membeli?', 'a' => 'Tentu, hubungi kami lewat WhatsApp atau form di atas dan tim kami akan bantu rekomendasi sesuai kebutuhan.'],
                ['q' => 'Apakah bisa retur barang?', 'a' => 'Barang bisa diretur dalam 7 hari sejak diterima selama kondisi masih baru dan sesuai syarat & ketentuan.'],
            ] as $i => $faq)
            <div class="border border-black/10 rounded-xl overflow-hidden">
                <button @click="open = open === {{ $i }} ? null : {{ $i }}" class="w-full flex items-center justify-between gap-4 px-5 py-4 text-left">
                    <span class="font-semibold text-[#1F150C] text-sm">{{ $faq['q'] }}</span>
                    <i class="fa-solid fa-plus text-xs text-[#412D15] transition-transform shrink-0" :class="open === {{ $i }} ? 'rotate-45' : ''"></i>
                </button>
                <div x-show="open === {{ $i }}" x-cloak x-transition class="px-5 pb-4 text-sm text-[#1F150C]/60 leading-relaxed">
                    {{ $faq['a'] }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection