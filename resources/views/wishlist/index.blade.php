@extends('layouts.app')

@section('title', 'Wishlist Saya - Tepi Kopi')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

    <div class="mb-8">
        <h1 class="font-display text-2xl sm:text-3xl font-semibold text-[#1F150C] mt-2">Wishlist Saya</h1>
        <p class="text-[#1F150C]/50 text-sm mt-1">Produk-produk yang kamu simpan buat dibeli nanti.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($wishlists->isEmpty())

        <div class="bg-white rounded-2xl border border-black/10 shadow-sm p-12 text-center">
            <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" style="background:#E1DCC9; color:#412D15;">
                <i class="fa-solid fa-heart text-2xl"></i>
            </div>
            <h3 class="font-bold text-[#1F150C] mb-1">Wishlist kamu masih kosong</h3>
            <p class="text-sm text-[#1F150C]/45 mb-6">Tap ikon hati di produk favoritmu supaya gampang dicari lagi nanti.</p>
            <a href="{{ route('katalog.index') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 btn-primary text-white text-sm font-bold rounded-lg transition-colors shadow-sm">
                Lihat Katalog
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

    @else

        <p class="text-sm text-[#1F150C]/50 mb-6"><span class="font-bold text-[#1F150C]">{{ $wishlists->count() }}</span> produk tersimpan</p>

        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        @foreach($wishlists as $wishlist)
            @php $product = $wishlist->product; @endphp
            @if($product)
            <div class="group bg-white rounded-2xl overflow-hidden border border-black/5 shadow-sm hover:shadow-lg transition-all duration-300">

                <a href="{{ route('katalog.show', $product) }}" class="block">
                    <div class="relative aspect-square overflow-hidden">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        @else
                            <div class="absolute inset-0 w-full h-full flex flex-col items-center justify-center" style="background:#E1DCC9; color:#412D15;">
                                <i class="fa-solid fa-mug-hot text-4xl sm:text-5xl mb-2 opacity-60"></i>
                                <span class="text-[10px] sm:text-xs font-medium uppercase tracking-wider opacity-60">No Image</span>
                            </div>
                        @endif

                        <span class="absolute top-2.5 left-2.5 bg-white/95 backdrop-blur-sm text-[#1F150C] text-[9px] font-bold tracking-widest uppercase px-2.5 py-1 rounded-md shadow-sm z-10">
                            {{ $product->category->name ?? 'Kopi' }}
                        </span>

                        @if($product->stock <= 0)
                            <span class="absolute bottom-2.5 left-2.5 bg-red-600 text-white text-[9px] font-bold px-2.5 py-1 rounded-full z-10 shadow">Habis</span>
                        @endif
                    </div>

                    <div class="p-3.5 sm:p-4">
                        <h3 class="text-sm sm:text-base font-bold text-[#1F150C] leading-tight line-clamp-1">{{ $product->name }}</h3>
                        <p class="font-extrabold text-sm sm:text-base mt-1.5" style="color:#412D15;">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    </div>
                </a>

                <div class="px-3.5 sm:px-4 pb-3.5 sm:pb-4 flex items-center gap-2">
                    <form action="/cart/add" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit"
                                class="w-full py-2.5 btn-primary text-sm font-bold rounded-lg shadow-sm transition-colors disabled:opacity-40 disabled:cursor-not-allowed"
                                {{ $product->stock < 1 ? 'disabled' : '' }}>
                            <i class="fa-solid fa-cart-plus mr-2"></i> Beli
                        </button>
                    </form>

                    <form action="{{ route('wishlist.destroy', $product) }}" method="POST"
                          onsubmit="return confirm('Hapus produk ini dari wishlist?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-10 h-10 shrink-0 rounded-lg border border-black/10 flex items-center justify-center text-[#1F150C]/50 hover:text-rose-600 hover:border-rose-200 transition"
                                title="Hapus dari wishlist">
                            <i class="fa-solid fa-trash-can text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endif
        @endforeach
        </div>

    @endif

</div>
@endsection