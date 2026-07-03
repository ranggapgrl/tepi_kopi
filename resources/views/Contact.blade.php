@extends('layouts.app')

@section('title', 'Kontak Kami')

@section('content')

{{-- HERO --}}
<section class="relative bg-gray-900 py-24 sm:py-32 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-black/50 to-transparent"></div>
    <div class="relative max-w-4xl mx-auto px-5 text-center text-white">
        <span class="inline-block mb-5 px-4 py-2 rounded-full text-xs uppercase tracking-widest text-amber-300 border border-amber-400/40 bg-white/5">
            Hubungi Kami
        </span>
        <h1 class="text-4xl sm:text-6xl font-extrabold leading-tight mb-6">Kontak</h1>
        <p class="text-lg text-white/70 max-w-2xl mx-auto">
            Ada pertanyaan soal produk, pesanan, atau kerja sama? Tim kami siap membantu.
        </p>
    </div>
</section>

{{-- INFO + FORM --}}
<section class="py-16 sm:py-24 bg-white">
    <div class="max-w-6xl mx-auto px-5 grid md:grid-cols-5 gap-12">

        <div class="md:col-span-2 space-y-8">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 shrink-0 rounded-xl bg-amber-700 text-white flex items-center justify-center">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 mb-1">Alamat</h3>
                    <p class="text-gray-600 text-sm">Jl. Kopi Tepi No. 12, Bandung, Jawa Barat, Indonesia</p>
                </div>
            </div>
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 shrink-0 rounded-xl bg-amber-700 text-white flex items-center justify-center">
                    <i class="fa-solid fa-phone"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 mb-1">Telepon / WhatsApp</h3>
                    <p class="text-gray-600 text-sm">+62 812-3456-7890</p>
                </div>
            </div>
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 shrink-0 rounded-xl bg-amber-700 text-white flex items-center justify-center">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 mb-1">Email</h3>
                    <p class="text-gray-600 text-sm">halo@tepikopi.com</p>
                </div>
            </div>
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 shrink-0 rounded-xl bg-amber-700 text-white flex items-center justify-center">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 mb-1">Jam Operasional</h3>
                    <p class="text-gray-600 text-sm">Senin - Sabtu, 09.00 - 18.00 WIB</p>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <a href="https://instagram.com" target="_blank" class="w-10 h-10 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-700 hover:bg-amber-700 hover:text-white transition">
                    <i class="fa-brands fa-instagram"></i>
                </a>
                <a href="https://wa.me/" target="_blank" class="w-10 h-10 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-700 hover:bg-amber-700 hover:text-white transition">
                    <i class="fa-brands fa-whatsapp"></i>
                </a>
                <a href="https://www.tiktok.com" target="_blank" class="w-10 h-10 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-700 hover:bg-amber-700 hover:text-white transition">
                    <i class="fa-brands fa-tiktok"></i>
                </a>
            </div>
        </div>

        <div class="md:col-span-3">
            @if(session('success'))
                <div class="mb-6 px-5 py-4 rounded-xl bg-green-50 border border-green-200 text-green-800 text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="/contact" class="bg-white border border-gray-200 rounded-2xl p-6 sm:p-8 space-y-5 shadow-sm">
                @csrf
                <div class="grid sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Nama</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-500 bg-gray-50 text-sm">
                        @error('name') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-500 bg-gray-50 text-sm">
                        @error('email') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Subjek</label>
                    <input type="text" name="subject" value="{{ old('subject') }}" required
                           class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-500 bg-gray-50 text-sm">
                    @error('subject') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Pesan</label>
                    <textarea name="message" rows="5" required
                              class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-500 bg-gray-50 text-sm resize-none">{{ old('message') }}</textarea>
                    @error('message') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <button type="submit"
                        class="px-8 py-3.5 bg-amber-700 hover:bg-amber-800 text-white font-bold rounded-lg uppercase tracking-widest transition">
                    Kirim Pesan
                </button>
            </form>
        </div>
    </div>
</section>

{{-- MAP --}}
<section class="pb-16 sm:pb-24 bg-white">
    <div class="max-w-6xl mx-auto px-5">
        <div class="rounded-2xl overflow-hidden border border-gray-200 h-[320px]">
            <iframe
                class="w-full h-full"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                src="https://maps.google.com/maps?q=Bandung%2C%20Jawa%20Barat&t=&z=13&ie=UTF8&iwloc=&output=embed">
            </iframe>
        </div>
    </div>
</section>
@endsection