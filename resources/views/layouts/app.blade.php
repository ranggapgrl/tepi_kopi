<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tepi Kopi - @yield('title', 'Premium Coffee')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/icon.svg') }}">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root{
            --ink:#1F150C;
            --brown:#412D15;
            --cream:#E1DCC9;
            --black:#000000;
            --white:#FFFFFF;
        }
        body{ font-family:'Plus Jakarta Sans', sans-serif; color:var(--ink); }
        .font-display{ font-family:'Fraunces', serif; }
        ::selection{ background:var(--cream); color:var(--ink); }
        [x-cloak]{ display:none !important; }
        .btn-primary{
            background:var(--brown); color:var(--white);
            transition:background-color .25s ease, transform .2s ease;
        }
        .btn-primary:hover{ background:var(--ink); }
        .link-underline{ position:relative; }
        .link-underline::after{
            content:''; position:absolute; left:0; bottom:-4px; width:0; height:1.5px;
            background:var(--brown); transition:width .25s ease;
        }
        .link-underline:hover::after, .link-underline.is-active::after{ width:100%; }
        .scrollbar-none::-webkit-scrollbar{ display:none; }
        .scrollbar-none{ -ms-overflow-style:none; scrollbar-width:none; }
    </style>
</head>
<body class="bg-white text-[#1F150C] antialiased min-h-screen flex flex-col" x-data="{ mobileMenuOpen: false }">

    {{-- ANNOUNCEMENT BAR --}}
    <div class="bg-[#1F150C] text-[#E1DCC9] text-center text-[11px] sm:text-xs py-2 px-4 tracking-wide">
        <i class="fa-solid fa-truck-fast mr-1.5"></i>
        Gratis ongkir se-Indonesia untuk pembelian di atas Rp500.000
    </div>

    {{-- NAVBAR --}}
    <nav class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-black/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-[72px] flex items-center justify-between gap-6">

            <a href="/" class="flex items-center space-x-2.5 shrink-0">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center text-white" style="background:var(--brown);">
                    <i class="fa-solid fa-coffee text-sm"></i>
                </div>
                <span class="text-xl font-display font-semibold tracking-tight text-[#1F150C]">Tepi<span style="color:var(--brown);">Kopi.</span></span>
            </a>

            <div class="hidden md:flex items-center space-x-9">
                <a href="/" class="link-underline text-sm font-medium transition {{ request()->routeIs('home') ? 'is-active text-[#412D15] font-bold' : 'text-[#1F150C]/70 hover:text-[#412D15]' }}">Beranda</a>
                <a href="/katalog" class="link-underline text-sm font-medium transition {{ request()->routeIs('katalog.*') ? 'is-active text-[#412D15] font-bold' : 'text-[#1F150C]/70 hover:text-[#412D15]' }}">Katalog</a>
                <a href="/about" class="link-underline text-sm font-medium transition {{ request()->routeIs('about') ? 'is-active text-[#412D15] font-bold' : 'text-[#1F150C]/70 hover:text-[#412D15]' }}">Tentang</a>
                <a href="/contact" class="link-underline text-sm font-medium transition {{ request()->routeIs('contact') ? 'is-active text-[#412D15] font-bold' : 'text-[#1F150C]/70 hover:text-[#412D15]' }}">Kontak</a>
            </div>

            <div class="flex items-center gap-3 sm:gap-5">
    @auth
    <a href="{{ route('wishlist.index') }}" class="relative w-10 h-10 rounded-full flex items-center justify-center text-[#1F150C] hover:bg-[#E1DCC9]/60 transition">
        <i class="fa-solid fa-heart {{ request()->routeIs('wishlist.index') ? '' : 'text-[#1F150C]' }}"></i>
        @if(($wishlistCount ?? 0) > 0)
        <span class="absolute top-0 right-0 text-white text-[10px] font-bold rounded-full h-4 w-4 flex items-center justify-center" style="background:var(--brown);">
            {{ $wishlistCount }}
        </span>
        @endif
    </a>
    @endauth

    @auth
    <div class="relative" x-data="{ notifOpen: false }">
        <button @click="notifOpen = !notifOpen" @click.outside="notifOpen = false"
            class="relative w-10 h-10 rounded-full flex items-center justify-center text-[#1F150C] hover:bg-[#E1DCC9]/60 transition">
            <i class="fa-solid fa-bell"></i>
            @if($unreadCustomerNotifications->count() > 0)
            <span class="absolute top-0 right-0 text-white text-[10px] font-bold rounded-full h-4 w-4 flex items-center justify-center" style="background:var(--brown);">
                {{ $unreadCustomerNotifications->count() > 9 ? '9+' : $unreadCustomerNotifications->count() }}
            </span>
            @endif
        </button>
        <div x-show="notifOpen" x-cloak x-transition
             class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-black/5 py-2 z-50 max-h-96 overflow-y-auto">
            <div class="flex items-center justify-between px-4 py-2 border-b border-black/5">
                <p class="text-sm font-bold text-[#1F150C]">Notifikasi</p>
                @if($unreadCustomerNotifications->count() > 0)
                <form method="POST" action="{{ route('my-notifications.readAll') }}">
                    @csrf
                    <button type="submit" class="text-xs font-medium hover:underline" style="color:var(--brown);">Tandai semua dibaca</button>
                </form>
                @endif
            </div>

            @forelse($unreadCustomerNotifications as $notification)
            <form method="POST" action="{{ route('my-notifications.read', $notification->id) }}">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-3 hover:bg-[#E1DCC9]/30 border-b border-black/5 last:border-0 flex gap-3">
                    <i class="fa-solid fa-receipt mt-0.5" style="color:var(--brown);"></i>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-[#1F150C]">Pesanan {{ $notification->data['order_code'] ?? '' }}</p>
                        <p class="text-xs text-[#1F150C]/60 mt-0.5">{{ $notification->data['message'] }}</p>
                        <p class="text-[11px] text-[#1F150C]/40 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                </button>
            </form>
            @empty
            <p class="px-4 py-6 text-center text-sm text-[#1F150C]/40">Tidak ada notifikasi baru.</p>
            @endforelse
        </div>
    </div>
    @endauth

    <a href="/cart" class="relative w-10 h-10 rounded-full flex items-center justify-center text-[#1F150C] hover:bg-[#E1DCC9]/60 transition">
        <i class="fa-solid fa-bag-shopping"></i>
        @if(($cartCount ?? 0) > 0)
        <span class="absolute top-0 right-0 text-white text-[10px] font-bold rounded-full h-4 w-4 flex items-center justify-center" style="background:var(--brown);">
            {{ $cartCount }}
        </span>
        @endif
    </a>

                @auth
                    <div class="relative hidden sm:block" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 pl-1 pr-3 py-1 rounded-full border border-black/10 hover:border-[#412D15]/40 transition">
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" class="w-7 h-7 rounded-full object-cover">
                            @else
                                <span class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold" style="background:var(--brown);">
                                    {{ strtoupper(substr(auth()->user()->name,0,1)) }}
                                </span>
                            @endif
                            <span class="text-sm font-medium text-[#1F150C]">{{ explode(' ', auth()->user()->name)[0] }}</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-[#1F150C]/50"></i>
                        </button>
                        <div x-show="open" x-cloak @click.outside="open = false"
                             x-transition
                             class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-black/5 py-2 z-50">
                            <a href="/profile" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-[#1F150C] hover:bg-[#E1DCC9]/40 transition"><i class="fa-solid fa-user w-4 text-[#412D15]"></i> Profil</a>
<a href="{{ route('orders.my') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-[#1F150C] hover:bg-[#E1DCC9]/40 transition"><i class="fa-solid fa-receipt w-4 text-[#412D15]"></i> Pesanan</a>
<a href="{{ route('addresses.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-[#1F150C] hover:bg-[#E1DCC9]/40 transition"><i class="fa-solid fa-location-dot w-4 text-[#412D15]"></i> Alamat Tersimpan</a>
<a href="{{ route('wishlist.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-[#1F150C] hover:bg-[#E1DCC9]/40 transition"><i class="fa-solid fa-heart w-4 text-[#412D15]"></i> Wishlist</a>
                            <hr class="my-1 border-black/5">
                            <form method="POST" action="/logout">
                                @csrf
                                <button class="w-full flex items-center gap-2.5 text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition"><i class="fa-solid fa-arrow-right-from-bracket w-4"></i> Keluar</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="/login" class="hidden sm:inline-flex px-5 py-2.5 btn-primary text-sm font-bold rounded-full transition">Masuk</a>
                @endauth

                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden w-10 h-10 flex items-center justify-center text-[#1F150C]">
                    <i class="fa-solid" :class="mobileMenuOpen ? 'fa-xmark' : 'fa-bars'"></i>
                </button>
            </div>
        </div>

        <div x-show="mobileMenuOpen" x-cloak x-transition class="md:hidden bg-white border-t border-black/5 px-5 py-5 space-y-4">
            <a href="/" class="block text-sm font-medium text-[#1F150C]/80">Beranda</a>
            <a href="/katalog" class="block text-sm font-medium text-[#1F150C]/80">Katalog</a>
            <a href="/about" class="block text-sm font-medium text-[#1F150C]/80">Tentang</a>
            <a href="/contact" class="block text-sm font-medium text-[#1F150C]/80">Kontak</a>
            <hr class="border-black/5">
           @auth
    <a href="/profile" class="block text-sm font-medium text-[#1F150C]/80">Profil</a>
    <a href="{{ route('orders.my') }}" class="block text-sm font-medium text-[#1F150C]/80">Pesanan</a>
    <a href="{{ route('addresses.index') }}" class="block text-sm font-medium text-[#1F150C]/80">Alamat Tersimpan</a>
    <a href="{{ route('wishlist.index') }}" class="block text-sm font-medium text-[#1F150C]/80">Wishlist</a>
    <form method="POST" action="/logout">@csrf<button class="text-sm font-medium text-red-600">Keluar</button></form>
@else
                <a href="/login" class="inline-block px-5 py-2.5 btn-primary text-sm font-bold rounded-full">Masuk</a>
            @endauth
        </div>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-cloak x-init="setTimeout(() => show = false, 4000)" x-transition class="max-w-7xl mx-auto px-4 mt-5">
            <div class="flex items-center justify-between gap-3 px-5 py-3.5 rounded-xl border" style="background:#f3f8f1; border-color:#cfe6c9; color:#2f5e29;">
                <span class="text-sm font-medium"><i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}</span>
                <button @click="show = false"><i class="fa-solid fa-xmark"></i></button>
            </div>
        </div>
    @endif

    <main class="flex-grow">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="pt-16 pb-8" style="background:var(--ink); color:#cfc4b0;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Newsletter --}}
            <div class="flex flex-col lg:flex-row items-center justify-between gap-6 pb-12 mb-12 border-b border-white/10">
                <div class="text-center lg:text-left">
                    <h3 class="text-white font-display text-xl sm:text-2xl font-semibold mb-1">Dapatkan promo & resep kopi mingguan</h3>
                    <p class="text-sm text-[#cfc4b0]/80">Langsung ke inbox kamu, tanpa spam.</p>
                </div>
                <form class="flex w-full max-w-md gap-2">
                    <input type="email" placeholder="Alamat email kamu" class="flex-1 px-4 py-3 rounded-lg bg-white/5 border border-white/15 text-sm text-white placeholder-white/40 outline-none focus:border-[#E1DCC9] transition">
                    <button type="submit" class="px-5 py-3 rounded-lg text-sm font-bold shrink-0" style="background:var(--cream); color:var(--ink);">Kirim</button>
                </form>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-12">
                <div class="col-span-2 md:col-span-1">
                    <div class="flex items-center space-x-2 mb-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white" style="background:var(--brown);">
                            <i class="fa-solid fa-coffee text-xs"></i>
                        </div>
                        <span class="text-lg font-display font-semibold text-white">TepiKopi.</span>
                    </div>
                    <p class="text-sm leading-relaxed text-[#cfc4b0]/70">
                        Kopi premium langsung dari petani lokal, diproses dengan standar tertinggi.
                    </p>
                    <div class="flex gap-2.5 mt-5">
                        <a href="https://instagram.com" target="_blank" class="w-9 h-9 rounded-full bg-white/5 border border-white/10 flex items-center justify-center hover:bg-[#412D15] hover:border-[#412D15] transition"><i class="fa-brands fa-instagram text-sm"></i></a>
                        <a href="https://wa.me/" target="_blank" class="w-9 h-9 rounded-full bg-white/5 border border-white/10 flex items-center justify-center hover:bg-[#412D15] hover:border-[#412D15] transition"><i class="fa-brands fa-whatsapp text-sm"></i></a>
                        <a href="https://www.tiktok.com" target="_blank" class="w-9 h-9 rounded-full bg-white/5 border border-white/10 flex items-center justify-center hover:bg-[#412D15] hover:border-[#412D15] transition"><i class="fa-brands fa-tiktok text-sm"></i></a>
                    </div>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Navigasi</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="/" class="text-[#cfc4b0]/70 hover:text-[#E1DCC9] transition">Beranda</a></li>
                        <li><a href="/katalog" class="text-[#cfc4b0]/70 hover:text-[#E1DCC9] transition">Katalog</a></li>
                        <li><a href="/about" class="text-[#cfc4b0]/70 hover:text-[#E1DCC9] transition">Tentang</a></li>
                        <li><a href="/contact" class="text-[#cfc4b0]/70 hover:text-[#E1DCC9] transition">Kontak</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Kategori</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="/katalog?kategori=1" class="text-[#cfc4b0]/70 hover:text-[#E1DCC9] transition">Biji Kopi</a></li>
                        <li><a href="/katalog?kategori=2" class="text-[#cfc4b0]/70 hover:text-[#E1DCC9] transition">Alat Kopi</a></li>
                        <li><a href="/katalog?kategori=3" class="text-[#cfc4b0]/70 hover:text-[#E1DCC9] transition">Aksesoris</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Kontak</h4>
                    <p class="text-sm text-[#cfc4b0]/70 flex items-start gap-2 mb-2"><i class="fa-solid fa-location-dot mt-0.5"></i> Bandung, Indonesia</p>
                    <p class="text-sm text-[#cfc4b0]/70 flex items-start gap-2"><i class="fa-solid fa-envelope mt-0.5"></i> halo@tepikopi.com</p>
                </div>
            </div>

            <div class="border-t border-white/10 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-[#cfc4b0]/50">
                <span>&copy; {{ date('Y') }} TepiKopi. All rights reserved.</span>
                <div class="flex items-center gap-3 text-lg opacity-70">
                    <i class="fa-brands fa-cc-visa"></i>
                    <i class="fa-brands fa-cc-mastercard"></i>
                    <i class="fa-solid fa-building-columns"></i>
                    <i class="fa-brands fa-google-pay"></i>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>