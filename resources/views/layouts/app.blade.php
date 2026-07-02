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
                <a href="/cart" class="text-sm font-medium text-amber-900 hover:text-amber-600 transition-colors">
                    <i class="fa-solid fa-cart-shopping mr-1"></i> Keranjang
                </a>

                @if(auth()->check() && auth()->user()->role === 'admin')
                    <div class="h-6 w-[1px] bg-amber-200"></div>
                    <a href="/products" class="text-sm font-bold text-rose-800 hover:text-rose-600 transition-colors">Kelola Produk</a>
                    <a href="/admin" class="px-4 py-2 bg-amber-800 hover:bg-amber-900 text-white text-sm font-bold rounded-lg transition-colors shadow-sm">
                        Dashboard Admin
                    </a>
                @endif

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
                            <a href="/orders" class="block px-4 py-2 text-sm text-amber-900 hover:bg-amber-50">Pesanan Saya</a>
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
            <a href="/cart" class="block px-3 py-2 rounded-md text-base font-medium text-amber-900 hover:bg-amber-50">Keranjang</a>

            @if(auth()->check() && auth()->user()->role === 'admin')
                <hr class="my-2 border-amber-100">
                <a href="/products" class="block px-3 py-2 rounded-md text-base font-bold text-rose-800 hover:bg-rose-50">Kelola Produk</a>
                <a href="/admin" class="block px-3 py-2 bg-amber-800 text-white rounded-md text-base font-bold text-center">Dashboard Admin</a>
            @endif

            <hr class="my-2 border-amber-100">
            @auth
                <a href="/profile" class="block px-3 py-2 rounded-md text-base font-medium text-amber-900 hover:bg-amber-50">Profil Saya</a>
                <a href="/orders" class="block px-3 py-2 rounded-md text-base font-medium text-amber-900 hover:bg-amber-50">Pesanan Saya</a>
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-md text-base font-medium text-rose-700 hover:bg-rose-50">Keluar</button>
                </form>
            @else
                <a href="/login" class="block px-3 py-2 bg-amber-800 text-white rounded-md text-base font-bold text-center">Login</a>
            @endauth
        </div>
    </nav>

    <main class="flex-grow w-full">
        @yield('content')
    </main>

</body>
</html>