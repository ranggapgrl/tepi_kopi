<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tepi Kopi - @yield('title', 'E-Commerce')</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-[#faf8f5] text-[#3e2723] min-h-screen flex flex-col antialiased" x-data="{ mobileMenuOpen: false }">

    <nav class="bg-white/90 backdrop-blur-md sticky top-0 z-50 border-b border-amber-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="/" class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-amber-800 rounded-lg flex items-center justify-center text-white shadow-md">
                    <i class="fa-solid fa-coffee text-sm"></i>
                </div>
                <span class="text-xl font-bold tracking-tight text-amber-900">Tepi<span class="text-amber-600">Kopi.</span></span>
            </a>

            <div class="hidden md:flex items-center space-x-8">
                <a href="/" class="text-sm font-medium text-amber-900 hover:text-amber-600 transition-colors">Beranda</a>
                <a href="/katalog" class="text-sm font-medium text-amber-900 hover:text-amber-600 transition-colors">Katalog</a>
                <a href="/about" class="text-sm font-medium text-amber-900 hover:text-amber-600 transition-colors">Tentang</a>
                <a href="/contact" class="text-sm font-medium text-amber-900 hover:text-amber-600 transition-colors">Kontak</a>
                <a href="/cart" class="relative text-sm font-medium text-amber-900 hover:text-amber-600 transition-colors">
                    <i class="fa-solid fa-cart-shopping mr-1"></i> Keranjang
                    @if(($cartCount ?? 0) > 0)
                        <span class="absolute -top-2 -right-3 flex items-center justify-center min-w-[18px] h-[18px] px-1 bg-amber-700 text-white text-[10px] font-bold rounded-full leading-none">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>

                <div class="h-6 w-[1px] bg-amber-200"></div>

                @auth
                    <div class="relative" x-data="{ userMenuOpen: false }">
                        <button @click="userMenuOpen = !userMenuOpen" @click.outside="userMenuOpen = false"
                            class="flex items-center gap-2 text-sm font-medium text-amber-900 hover:text-amber-600 transition-colors">
                            <i class="fa-solid fa-circle-user text-lg"></i>
                            {{ explode(' ', auth()->user()->name)[0] }}
                            <i class="fa-solid fa-chevron-down text-xs" :class="userMenuOpen ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="userMenuOpen" x-transition x-cloak
                             class="absolute right-0 mt-3 w-44 bg-white rounded-lg shadow-lg border border-amber-100 py-2 z-50">
                            <a href="/profile" class="block px-4 py-2 text-sm text-amber-900 hover:bg-amber-50">Profil Saya</a>
                            @if(auth()->user()->role !== 'admin')
                                <a href="{{ route('orders.my') }}" class="block px-4 py-2 text-sm text-amber-900 hover:bg-amber-50">Pesanan Saya</a>
                            @endif
                            <form method="POST" action="/logout">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-rose-700 hover:bg-rose-50">Keluar</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="/login"
                       class="px-5 py-2 bg-amber-800 hover:bg-amber-900 text-white text-sm font-bold rounded-lg transition-colors shadow-sm">
                        Login
                    </a>
                @endauth
            </div>

            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-amber-900 text-xl focus:outline-none">
                <i class="fa-solid" :class="mobileMenuOpen ? 'fa-xmark' : 'fa-bars'"></i>
            </button>
        </div>

        <div x-show="mobileMenuOpen" x-transition x-cloak class="md:hidden bg-white border-t border-amber-100 px-4 pt-2 pb-4 space-y-2 shadow-lg">
            <a href="/" class="block px-3 py-2 rounded-md text-base font-medium text-amber-900 hover:bg-amber-50">Beranda</a>
            <a href="/katalog" class="block px-3 py-2 rounded-md text-base font-medium text-amber-900 hover:bg-amber-50">Katalog</a>
            <a href="/about" class="block px-3 py-2 rounded-md text-base font-medium text-amber-900 hover:bg-amber-50">Tentang</a>
            <a href="/contact" class="block px-3 py-2 rounded-md text-base font-medium text-amber-900 hover:bg-amber-50">Kontak</a>
            @auth
            <a href="/cart" class="block px-3 py-2 rounded-md text-base font-medium text-amber-900 hover:bg-amber-50">Keranjang</a>
            @endauth

            @if(auth()->check() && auth()->user()->role === 'admin')
                <hr class="my-2 border-amber-100">
                <a href="/admin" class="block px-3 py-2 bg-amber-800 text-white rounded-md text-base font-bold text-center">Dashboard Admin</a>
            @endif

            <hr class="my-2 border-amber-100">
            @auth
                <a href="/profile" class="block px-3 py-2 rounded-md text-base font-medium text-amber-900 hover:bg-amber-50">Profil Saya</a>
                @if(auth()->user()->role !== 'admin')
                    <a href="{{ route('orders.my') }}" class="block px-3 py-2 rounded-md text-base font-medium text-amber-900 hover:bg-amber-50">Pesanan Saya</a>
                @endif
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-md text-base font-medium text-rose-700 hover:bg-rose-50">Keluar</button>
                </form>
            @else
                <a href="/login" class="block px-3 py-2 bg-amber-800 text-white rounded-md text-base font-bold text-center">Login</a>
            @endauth
        </div>
    </nav>

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition
             class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium rounded-lg px-4 py-3 flex items-center justify-between shadow-sm">
                <span><i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}</span>
                <button @click="show = false" class="text-emerald-600 hover:text-emerald-800">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition
             class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-rose-50 border border-rose-200 text-rose-800 text-sm font-medium rounded-lg px-4 py-3 flex items-center justify-between shadow-sm">
                <span><i class="fa-solid fa-circle-exclamation mr-2"></i>{{ session('error') }}</span>
                <button @click="show = false" class="text-rose-600 hover:text-rose-800">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>
    @endif

    <main class="flex-grow w-full">
        @yield('content')
    </main>

    @include('partials.toast')
    {{-- FOOTER --}}
    <footer class="bg-amber-950 text-amber-300 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 grid grid-cols-2 md:grid-cols-4 gap-8">
            <div>
                <h4 class="text-white font-bold mb-3 text-sm uppercase tracking-wide">TepiKopi.</h4>
                <p class="text-xs leading-relaxed text-amber-400/80">
                    Menyajikan kopi terbaik langsung dari petani lokal, diproses dengan hati.
                </p>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-3 text-sm">Navigasi</h4>
                <ul class="space-y-1.5 text-sm">
                    <li><a href="/" class="hover:text-white transition">Beranda</a></li>
                    <li><a href="/katalog" class="hover:text-white transition">Katalog</a></li>
                    <li><a href="/about" class="hover:text-white transition">Tentang</a></li>
                    <li><a href="/contact" class="hover:text-white transition">Kontak</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-3 text-sm">Kategori</h4>
                <ul class="space-y-1.5 text-sm">
                    <li><a href="/katalog?kategori=1" class="hover:text-white transition">Biji Kopi</a></li>
                    <li><a href="/katalog?kategori=2" class="hover:text-white transition">Alat Kopi</a></li>
                    <li><a href="/katalog?kategori=3" class="hover:text-white transition">Aksesoris</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-3 text-sm">Kontak</h4>
                <p class="text-sm">📍 Bandung, Indonesia</p>
                <p class="text-sm mt-1">✉️ halo@tepikopi.com</p>
            </div>
        </div>
        <div class="border-t border-amber-800 mt-8 pt-6 pb-4 text-center text-xs text-amber-600">
            &copy; {{ date('Y') }} TepiKopi. All rights reserved.
        </div>
    </footer>

</body>
</html>