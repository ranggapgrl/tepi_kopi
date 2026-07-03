<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tepi Kopi - @yield('title', 'Premium Coffee')</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-white text-gray-900 antialiased min-h-screen flex flex-col" x-data="{ mobileMenuOpen: false }">

    {{-- NAVBAR --}}
    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="/" class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-amber-800 rounded-lg flex items-center justify-center text-white">
                    <i class="fa-solid fa-coffee text-sm"></i>
                </div>
                <span class="text-xl font-bold tracking-tight text-gray-900">Tepi<span class="text-amber-700">Kopi.</span></span>
            </a>

            <div class="hidden md:flex items-center space-x-8">
                <a href="/" class="text-sm font-medium text-gray-600 hover:text-amber-700 transition">Beranda</a>
                <a href="/katalog" class="text-sm font-medium text-gray-600 hover:text-amber-700 transition">Katalog</a>
                <a href="/about" class="text-sm font-medium text-gray-600 hover:text-amber-700 transition">Tentang</a>
                <a href="/contact" class="text-sm font-medium text-gray-600 hover:text-amber-700 transition">Kontak</a>
                <a href="/cart" class="relative text-sm font-medium text-gray-600 hover:text-amber-700 transition">
                    <i class="fa-solid fa-cart-shopping"></i>
                    @if(($cartCount ?? 0) > 0)
                    <span class="absolute -top-2 -right-3 bg-amber-700 text-white text-[10px] font-bold rounded-full h-4 w-4 flex items-center justify-center">
                        {{ $cartCount }}
                    </span>
                    @endif
                </a>

                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="text-sm font-medium text-gray-600 hover:text-amber-700 flex items-center gap-2">
                            {{-- BAGIAN YANG DIUBAH: Cek dan Tampilkan Avatar --}}
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" class="w-7 h-7 rounded-full object-cover">
                            @else
                                <i class="fa-solid fa-circle-user"></i>
                            @endif
                            {{-- END BAGIAN YANG DIUBAH --}}
                            
                            {{ explode(' ', auth()->user()->name)[0] }}
                        </button>
                        <div x-show="open" @click.outside="open = false" class="absolute right-0 mt-2 w-44 bg-white rounded-lg shadow-lg border py-2 z-50">
                            <a href="/profile" class="block px-4 py-2 text-sm hover:bg-gray-50">Profil</a>
                            <a href="/orders" class="block px-4 py-2 text-sm hover:bg-gray-50">Pesanan</a>
                            <form method="POST" action="/logout">@csrf<button class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Keluar</button></form>
                        </div>
                    </div>
                @else
                    <a href="/login" class="px-5 py-2 bg-amber-800 hover:bg-amber-900 text-white text-sm font-bold rounded-lg transition">Login</a>
                @endauth
            </div>

            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-gray-900">
                <i class="fa-solid" :class="mobileMenuOpen ? 'fa-xmark' : 'fa-bars'"></i>
            </button>
        </div>

        <div x-show="mobileMenuOpen" x-transition class="md:hidden bg-white border-t px-4 py-4 space-y-3">
            <a href="/" class="block text-gray-600">Beranda</a>
            <a href="/katalog" class="block text-gray-600">Katalog</a>
            <a href="/about" class="block text-gray-600">Tentang</a>
            <a href="/contact" class="block text-gray-600">Kontak</a>
            <a href="/cart" class="block text-gray-600">Keranjang</a>
            @auth
                <a href="/profile" class="block text-gray-600">Profil</a>
                <form method="POST" action="/logout">@csrf<button class="text-red-600">Keluar</button></form>
            @else
                <a href="/login" class="inline-block px-5 py-2 bg-amber-800 text-white rounded-lg">Login</a>
            @endauth
        </div>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex justify-between">
                <span><i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}</span>
                <button @click="show = false"><i class="fa-solid fa-xmark"></i></button>
            </div>
        </div>
    @endif

    <main class="flex-grow">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="bg-stone-800 text-stone-300 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-2 md:grid-cols-4 gap-8">
            <div>
                <h4 class="text-white font-bold mb-3 text-sm uppercase tracking-wide">TepiKopi.</h4>
                <p class="text-sm leading-relaxed text-stone-400">
                    Kopi premium langsung dari petani lokal, diproses dengan standar tertinggi.
                </p>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-3 text-sm">Navigasi</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="/" class="text-stone-400 hover:text-amber-400 transition">Beranda</a></li>
                    <li><a href="/katalog" class="text-stone-400 hover:text-amber-400 transition">Katalog</a></li>
                    <li><a href="/about" class="text-stone-400 hover:text-amber-400 transition">Tentang</a></li>
                    <li><a href="/contact" class="text-stone-400 hover:text-amber-400 transition">Kontak</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-3 text-sm">Kategori</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="/katalog?kategori=1" class="text-stone-400 hover:text-amber-400 transition">Biji Kopi</a></li>
                    <li><a href="/katalog?kategori=2" class="text-stone-400 hover:text-amber-400 transition">Alat Kopi</a></li>
                    <li><a href="/katalog?kategori=3" class="text-stone-400 hover:text-amber-400 transition">Aksesoris</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-3 text-sm">Kontak</h4>
                <p class="text-sm text-stone-400">📍 Bandung, Indonesia</p>
                <p class="text-sm mt-1 text-stone-400">✉️ halo@tepikopi.com</p>
            </div>
        </div>
        <div class="border-t border-stone-800 mt-10 pt-6 text-center text-sm text-stone-500">
            &copy; {{ date('Y') }} TepiKopi. All rights reserved.
        </div>
    </footer>
</body>
</html>