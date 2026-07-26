@props(['product', 'wishlisted' => false, 'featured' => false])

<div class="group bg-white rounded-2xl overflow-hidden border border-black/5 shadow-sm hover:shadow-lg transition-all duration-300">
    <a href="/katalog/{{ $product->id }}" class="block">
        <div class="relative aspect-square overflow-hidden">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                     class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">
            @else
                <div class="absolute inset-0 w-full h-full flex flex-col items-center justify-center" style="background:#E1DCC9; color:#412D15;">
                    <i class="fa-solid fa-mug-hot text-4xl sm:text-5xl mb-2 opacity-60"></i>
                    <span class="text-[10px] sm:text-xs font-medium uppercase tracking-wider opacity-60">No Image</span>
                </div>
            @endif

            <span class="absolute top-2.5 left-2.5 bg-white/95 backdrop-blur-sm text-[#1F150C] text-[9px] font-bold tracking-widest uppercase px-2.5 py-1 rounded-md shadow-sm z-10">
                {{ $product->category->name ?? 'Kopi' }}
            </span>

            @if($featured)
                <span class="absolute top-2.5 left-2.5 bg-[#412D15] text-white text-[10px] font-bold px-2.5 py-1 rounded-full z-10" style="left:auto; right:2.5rem;">
                    <i class="fa-solid fa-fire mr-1"></i>Terlaris
                </span>
            @endif

            @if($product->stock <= 0)
                <span class="absolute bottom-2.5 left-2.5 bg-red-600 text-white text-[9px] font-bold px-2.5 py-1 rounded-full z-10 shadow">Habis</span>
            @endif

            @auth
            <button type="button"
                    x-data="{ wishlisted: {{ $wishlisted ? 'true' : 'false' }}, loading: false }"
                    @click.stop.prevent="
                        if (loading) return;
                        loading = true;
                        fetch('{{ route('wishlist.toggle', $product) }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                'Accept': 'application/json',
                            },
                        })
                        .then(res => res.json())
                        .then(data => { wishlisted = data.wishlisted; })
                        .finally(() => { loading = false; });
                    "
                    class="absolute top-2.5 right-2.5 w-8 h-8 rounded-full bg-white/90 backdrop-blur-sm flex items-center justify-center transition z-10"
                    :class="wishlisted ? 'text-rose-500' : 'text-[#1F150C]/50 hover:text-rose-500'"
                    aria-label="{{ __('Toggle wishlist') }}">
                <i class="text-xs" :class="wishlisted ? 'fa-solid fa-heart' : 'fa-regular fa-heart'"></i>
            </button>
            @else
            <button type="button"
                    @click.stop.prevent="window.location.href = '{{ route('login') }}'"
                    class="absolute top-2.5 right-2.5 w-8 h-8 rounded-full bg-white/90 backdrop-blur-sm flex items-center justify-center text-[#1F150C]/50 hover:text-rose-500 transition z-10"
                    aria-label="{{ __('Add to wishlist') }}">
                <i class="fa-regular fa-heart text-xs"></i>
            </button>
            @endauth
        </div>

        <div class="p-3.5 sm:p-4">
            <div class="flex items-center gap-1 text-[9px] mb-1" style="color:#412D15;">
                @for($i = 1; $i <= 5; $i++)
                    <i class="fa-{{ $i <= round($product->reviews_avg_rating ?? 0) ? 'solid' : 'regular' }} fa-star"></i>
                @endfor
                @if($product->reviews_avg_rating)
                    <span class="text-[9px] text-[#1F150C]/40 ml-0.5">({{ number_format($product->reviews_avg_rating, 1) }})</span>
                @endif
            </div>
            <h3 class="text-sm sm:text-base font-bold text-[#1F150C] leading-tight line-clamp-1">{{ $product->name }}</h3>
            <p class="font-extrabold text-sm sm:text-base mt-1.5" style="color:#412D15;">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
        </div>
    </a>

    <div class="px-3.5 sm:px-4 pb-3.5 sm:pb-4">
        <button type="button"
                x-data="{ loading: false }"
                @click="
                    if (loading) return;
                    loading = true;
                    fetch('{{ route('cart.add') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            product_id: {{ $product->id }},
                            quantity: 1
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            window.dispatchEvent(new CustomEvent('cart-updated', { detail: data.cart_count }));
                            // Optional: show a small toast or flash message
                        } else {
                            alert(data.error || 'Gagal menambahkan ke keranjang.');
                        }
                    })
                    .catch(() => alert('Terjadi kesalahan jaringan.'))
                    .finally(() => { loading = false; });
                "
                class="w-full py-2.5 btn-primary text-sm font-bold rounded-lg shadow-sm transition-colors disabled:opacity-40 disabled:cursor-not-allowed"
                :disabled="loading || {{ $product->stock < 1 ? 'true' : 'false' }}">
            <i class="fa-solid fa-cart-plus mr-2" :class="loading ? 'fa-spin' : ''"></i>
            <span x-text="loading ? 'Menambahkan...' : 'Beli'"></span>
        </button>
    </div>
</div>
