<aside class="w-full md:w-64 flex-shrink-0">
    <div class="bg-white p-5 rounded-2xl border border-amber-100 shadow-sm space-y-2">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 px-2">Menu Kelola</p>

        <a href="/admin"
           class="flex items-center gap-3 px-3 py-2.5 font-semibold rounded-xl transition-colors {{ request()->is('admin') ? 'bg-amber-50 text-amber-800' : 'text-gray-600 hover:bg-amber-50 hover:text-amber-800 font-medium' }}">
            <i class="fa-solid fa-chart-pie w-5"></i> Dashboard
        </a>

        <a href="/products"
           class="flex items-center gap-3 px-3 py-2.5 font-semibold rounded-xl transition-colors {{ request()->is('products*') ? 'bg-amber-50 text-amber-800' : 'text-gray-600 hover:bg-amber-50 hover:text-amber-800 font-medium' }}">
            <i class="fa-solid fa-mug-hot w-5"></i> Kelola Produk
        </a>

        <a href="/categories"
           class="flex items-center gap-3 px-3 py-2.5 font-semibold rounded-xl transition-colors {{ request()->is('categories*') ? 'bg-amber-50 text-amber-800' : 'text-gray-600 hover:bg-amber-50 hover:text-amber-800 font-medium' }}">
            <i class="fa-solid fa-tags w-5"></i> Kategori Produk
        </a>

        <a href="{{ route('coupons.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 font-semibold rounded-xl transition-colors {{ request()->is('coupons*') ? 'bg-amber-50 text-amber-800' : 'text-gray-600 hover:bg-amber-50 hover:text-amber-800 font-medium' }}">
            <i class="fa-solid fa-ticket w-5"></i> Kupon Diskon
        </a>

        <a href="/orders"
           class="flex items-center justify-between gap-3 px-3 py-2.5 font-semibold rounded-xl transition-colors {{ request()->is('orders*') ? 'bg-amber-50 text-amber-800' : 'text-gray-600 hover:bg-amber-50 hover:text-amber-800 font-medium' }}">
            <span class="flex items-center gap-3">
                <i class="fa-solid fa-receipt w-5"></i> Pesanan Masuk
            </span>
            @if(($pendingOrdersCount ?? 0) > 0)
                <span class="bg-rose-600 text-white text-[10px] font-bold min-w-[1.25rem] h-5 px-1 rounded-full flex items-center justify-center">
                    {{ $pendingOrdersCount > 99 ? '99+' : $pendingOrdersCount }}
                </span>
            @endif
        </a>

        <a href="{{ route('reports.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 font-semibold rounded-xl transition-colors {{ request()->is('laporan*') ? 'bg-amber-50 text-amber-800' : 'text-gray-600 hover:bg-amber-50 hover:text-amber-800 font-medium' }}">
            <i class="fa-solid fa-chart-line w-5"></i> Laporan Penjualan
        </a>

        <a href="/reviews"
           class="flex items-center gap-3 px-3 py-2.5 font-semibold rounded-xl transition-colors {{ request()->is('reviews*') ? 'bg-amber-50 text-amber-800' : 'text-gray-600 hover:bg-amber-50 hover:text-amber-800 font-medium' }}">
            <i class="fa-solid fa-star w-5"></i> Ulasan Pelanggan
        </a>

        <a href="{{ route('contact-messages.index') }}"
           class="flex items-center justify-between gap-3 px-3 py-2.5 font-semibold rounded-xl transition-colors {{ request()->is('pesan-kontak*') ? 'bg-amber-50 text-amber-800' : 'text-gray-600 hover:bg-amber-50 hover:text-amber-800 font-medium' }}">
            <span class="flex items-center gap-3">
                <i class="fa-solid fa-envelope w-5"></i> Pesan Kontak
            </span>
            @if(($unreadContactCount ?? 0) > 0)
                <span class="bg-rose-600 text-white text-[10px] font-bold min-w-[1.25rem] h-5 px-1 rounded-full flex items-center justify-center">
                    {{ $unreadContactCount > 99 ? '99+' : $unreadContactCount }}
                </span>
            @endif
        </a>

        <a href="{{ route('users.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 font-semibold rounded-xl transition-colors {{ request()->is('users*') ? 'bg-amber-50 text-amber-800' : 'text-gray-600 hover:bg-amber-50 hover:text-amber-800 font-medium' }}">
            <i class="fa-solid fa-users w-5"></i> Manajemen User
        </a>

        <a href="{{ route('activity-logs.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 font-semibold rounded-xl transition-colors {{ request()->is('log-aktivitas*') ? 'bg-amber-50 text-amber-800' : 'text-gray-600 hover:bg-amber-50 hover:text-amber-800 font-medium' }}">
            <i class="fa-solid fa-clock-rotate-left w-5"></i> Log Aktivitas
        </a>
    </div>
</aside>