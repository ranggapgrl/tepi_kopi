<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tepi Kopi Admin - @yield('title', 'Panel Kelola')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/icon.svg') }}">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-[#faf8f5] text-[#3e2723] min-h-screen flex flex-col antialiased">

    <nav class="bg-amber-950 sticky top-0 z-50 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-amber-700 rounded-lg flex items-center justify-center text-white shadow-md">
                    <i class="fa-solid fa-coffee text-sm"></i>
                </div>
                <span class="text-lg font-bold tracking-tight text-white">
                    Tepi<span class="text-amber-400">Kopi</span>
                    <span class="text-amber-400/60 font-medium text-sm ml-1">Admin</span>
                </span>
            </a>

            <div class="flex items-center gap-4">
                <a href="/katalog" target="_blank"
                   class="hidden sm:flex items-center gap-2 text-xs font-medium text-amber-100/80 hover:text-white transition-colors">
                </a>

                <div class="h-6 w-[1px] bg-white/10 hidden sm:block"></div>

                @auth
                    @php $unreadNotifications = auth()->user()->unreadNotifications; @endphp
                    <div class="relative" x-data="{ notifOpen: false }">
                        <button @click="notifOpen = !notifOpen" @click.outside="notifOpen = false"
                            class="relative flex items-center justify-center w-9 h-9 rounded-full text-amber-50 hover:bg-white/10 transition-colors">
                            <i class="fa-solid fa-bell text-lg"></i>
                            @if($unreadNotifications->count() > 0)
                                <span class="absolute -top-0.5 -right-0.5 bg-rose-600 text-white text-[10px] font-bold min-w-[1.1rem] h-[1.1rem] px-1 rounded-full flex items-center justify-center">
                                    {{ $unreadNotifications->count() > 9 ? '9+' : $unreadNotifications->count() }}
                                </span>
                            @endif
                        </button>
                        <div x-show="notifOpen" x-transition x-cloak
                             class="absolute right-0 mt-3 w-80 bg-white rounded-xl shadow-lg border border-amber-100 py-2 z-50 max-h-96 overflow-y-auto">
                            <div class="flex items-center justify-between px-4 py-2 border-b border-amber-50">
                                <p class="text-sm font-bold text-amber-900">Notifikasi</p>
                                @if($unreadNotifications->count() > 0)
                                    <form method="POST" action="{{ route('notifications.readAll') }}">
                                        @csrf
                                        <button type="submit" class="text-xs text-amber-700 hover:underline font-medium">Tandai semua dibaca</button>
                                    </form>
                                @endif
                            </div>

                            @forelse($unreadNotifications as $notification)
    @php $notifType = $notification->data['type'] ?? 'order'; @endphp
    <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
        @csrf
        <button type="submit" class="w-full text-left px-4 py-3 hover:bg-amber-50 border-b border-amber-50/70 last:border-0 flex gap-3">
            @if($notifType === 'low_stock')
                <i class="fa-solid fa-triangle-exclamation text-amber-500 mt-0.5"></i>
            @else
                <i class="fa-solid fa-receipt text-emerald-600 mt-0.5"></i>
            @endif
            <div class="min-w-0">
                <p class="text-sm font-semibold text-amber-900">
                    {{ $notifType === 'low_stock' ? 'Stok Menipis' : 'Pesanan Baru — ' . ($notification->data['order_code'] ?? '') }}
                </p>
                <p class="text-xs text-gray-500 mt-0.5">{{ $notification->data['message'] }}</p>
                <p class="text-[11px] text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
            </div>
        </button>
    </form>
@empty
                                <p class="px-4 py-6 text-center text-sm text-gray-400">Tidak ada notifikasi baru.</p>
                            @endforelse
                        </div>
                    </div>
                @endauth

                @auth
                    <div class="relative" x-data="{ userMenuOpen: false }">
                        <button @click="userMenuOpen = !userMenuOpen" @click.outside="userMenuOpen = false"
                            class="flex items-center gap-2 text-sm font-medium text-amber-50 hover:text-white transition-colors">
                            <i class="fa-solid fa-circle-user text-lg"></i>
                            <span class="hidden sm:inline">{{ explode(' ', auth()->user()->name)[0] }}</span>
                            <i class="fa-solid fa-chevron-down text-xs" :class="userMenuOpen ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="userMenuOpen" x-transition x-cloak
                             class="absolute right-0 mt-3 w-44 bg-white rounded-lg shadow-lg border border-amber-100 py-2 z-50">
                            <a href="/profile" class="block px-4 py-2 text-sm text-amber-900 hover:bg-amber-50">Profil Saya</a>
                            <form method="POST" action="/logout">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-rose-700 hover:bg-rose-50">Keluar</button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <main class="flex-grow w-full">
        @yield('content')
    </main>

    @include('partials.toast')

</body>
</html>