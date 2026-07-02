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
            <i class="fa-solid fa-tags w-5"></i> Kategori Menu
        </a>

        <a href="/orders"
           class="flex items-center gap-3 px-3 py-2.5 font-semibold rounded-xl transition-colors {{ request()->is('orders*') ? 'bg-amber-50 text-amber-800' : 'text-gray-600 hover:bg-amber-50 hover:text-amber-800 font-medium' }}">
            <i class="fa-solid fa-receipt w-5"></i> Pesanan Masuk
        </a>
    </div>
</aside>