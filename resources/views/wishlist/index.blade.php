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
                    <x-product-card :product="$product" :wishlisted="true" />
                @endif
            @endforeach
        </div>

    @endif

</div>
@endsection
