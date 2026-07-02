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
                <a href="/products" class="text-sm font-medium text-amber-900 hover:text-amber-600 transition-colors">Katalog</a>
                <a href="/cart" class="text-sm font-medium text-amber-900 hover:text-amber-600 transition-colors relative">
                    <i class="fa-solid fa-cart-shopping mr-1"></i> Keranjang
                    <span class="absolute -top-2 -right-3 bg-rose-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">2</span>
                </a>
                <a href="/admin" class="px-4 py-2 bg-amber-100 hover:bg-amber-200 text-amber-900 text-sm font-bold rounded-lg transition-colors">
                    Dashboard Admin
                </a>
            </div>

            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-amber-900 text-xl focus:outline-none">
                <i class="fa-solid" :class="mobileMenuOpen ? 'fa-xmark' : 'fa-bars'"></i>
            </button>
        </div>

        <div x-show="mobileMenuOpen" x-transition class="md:hidden bg-white border-t border-amber-100 px-4 pt-2 pb-4 space-y-2 shadow-lg">
            <a href="/products" class="block px-3 py-2 rounded-md text-base font-medium text-amber-900 hover:bg-amber-50">Katalog</a>
            <a href="/cart" class="block px-3 py-2 rounded-md text-base font-medium text-amber-900 hover:bg-amber-50">Keranjang Belanja</a>
            <a href="/admin" class="block px-3 py-2 mt-4 bg-amber-800 text-white rounded-md text-base font-medium text-center">Dashboard Admin</a>
        </div>
    </nav>

    <main class="flex-grow w-full">
        @yield('content')
    </main>

</body>
</html>