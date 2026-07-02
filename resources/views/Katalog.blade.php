@extends('layouts.app')

@section('title', 'Katalog Produk Tepi Kopi')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="mb-10 text-center sm:text-left flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-amber-100/60 pb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-amber-950 tracking-tight mb-2">Katalog Kopi Terbaik</h1>
            <p class="text-amber-700/80 text-sm">Pilih dan nikmati varian kopi pilihan langsung dari etalase kami.</p>
        </div>
        <div class="text-sm bg-amber-100/50 text-amber-900 px-4 py-2.5 rounded-xl border border-amber-200/40 font-medium">
            Total: <span class="font-bold">{{ $products->count() }}</span> Produk
        </div>
    </div>

    @if(session('success'))
    <div class="mb-8 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-3">
        <i class="fa-solid fa-circle-check"></i>
        <span class="text-sm font-medium">{{ session('success') }}</span>
    </div>
    @endif

    @if($products->isEmpty())
    <div class="bg-white rounded-3xl p-12 text-center border border-amber-100 shadow-sm max-w-md mx-auto my-12">
        <div class="w-20 h-20 bg-amber-50 rounded-full flex items-center justify-center text-amber-600 mx-auto mb-6">
            <i class="fa-solid fa-box-open text-3xl"></i>
        </div>
        <h3 class="text-lg font-bold text-amber-950 mb-2">Belum ada produk</h3>
        <p class="text-amber-700/70 text-sm">Etalase masih kosong, silakan cek lagi nanti.</p>
    </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @foreach($products as $product)
        <div class="bg-white rounded-2xl border border-amber-100 overflow-hidden shadow-sm hover:shadow-xl hover:border-amber-200 transition-all duration-500 flex flex-col group">

            <a href="/katalog/{{ $product->id }}" class="relative aspect-square w-full bg-amber-50/50 overflow-hidden border-b border-amber-50 block">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-amber-300">
                        <i class="fa-solid fa-mug-hot text-5xl mb-2"></i>
                        <span class="text-xs font-medium uppercase tracking-wider">No Image</span>
                    </div>
                @endif
                <span class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm text-amber-900 text-[10px] font-bold tracking-widest uppercase px-3 py-1.5 rounded-md shadow-sm border border-amber-100">
                    {{ $product->category->name ?? 'Kopi' }}
                </span>
            </a>

            <div class="p-6 flex flex-col flex-grow">
                <a href="/katalog/{{ $product->id }}">
                    <h3 class="text-lg font-bold text-amber-950 line-clamp-1 mb-1 group-hover:text-amber-600 transition-colors">
                        {{ $product->name }}
                    </h3>
                </a>
                <p class="text-xs text-gray-500 mb-4 line-clamp-2 min-h-[32px]">
                    {{ $product->description ?? 'Deskripsi produk belum tersedia.' }}
                </p>

                <div class="mt-auto pt-4 border-t border-amber-50 flex items-center justify-between mb-5">
                    <div>
                        <span class="text-[10px] text-gray-400 block uppercase tracking-wider font-semibold">Harga</span>
                        <span class="text-base font-extrabold text-amber-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] text-gray-400 block uppercase tracking-wider font-semibold">Stok</span>
                        <span class="inline-flex items-center text-xs font-semibold {{ $product->stock > 0 ? 'text-emerald-700 bg-emerald-50' : 'text-rose-700 bg-rose-50' }} px-2 py-1 rounded-md">
                            {{ $product->stock > 0 ? $product->stock . ' Pcs' : 'Habis' }}
                        </span>
                    </div>
                </div>

                <form action="/cart/add" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="w-full py-2.5 bg-amber-800 hover:bg-amber-900 text-white rounded-xl text-xs font-bold transition-all shadow-md flex items-center justify-center gap-2" {{ $product->stock < 1 ? 'disabled' : '' }}>
                        <i class="fa-solid fa-cart-plus"></i> Beli
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection