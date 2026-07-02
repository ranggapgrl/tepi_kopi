@extends('layouts.app')

@section('title', 'Katalog Produk Tepi Kopi')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    <div class="mb-10 text-center sm:text-left flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-amber-100/60 pb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-amber-950 tracking-tight mb-2">Katalog Kopi Terbaik</h1>
            <p class="text-amber-700/80 text-sm">Pilih dan nikmati varian kopi pilihan langsung dari etalase kami.</p>
        </div>
        <div class="flex flex-col sm:flex-row items-center gap-4">
            <div class="text-sm bg-amber-100/50 text-amber-900 px-4 py-2.5 rounded-xl border border-amber-200/40 font-medium">
                Total: <span class="font-bold">{{ $products->count() }}</span> Produk
            </div>

            @if(auth()->check() && auth()->user()->role === 'admin')
            <a href="{{ route('products.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-amber-800 hover:bg-amber-900 text-white font-medium text-sm rounded-xl shadow-md transition-all duration-300 hover:-translate-y-0.5">
                <i class="fa-solid fa-plus mr-2 text-xs"></i> Tambah Produk
            </a>
            @endif
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
        <p class="text-amber-700/70 text-sm mb-6">Etalase masih kosong.</p>
        
        @if(auth()->check() && auth()->user()->role === 'admin')
        <a href="{{ route('products.create') }}" class="inline-flex items-center px-6 py-3 bg-amber-700 hover:bg-amber-800 text-white text-sm font-medium rounded-xl transition-colors shadow-md">
            Tambah Produk Pertama
        </a>
        @endif
    </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @foreach($products as $product)
        <div class="bg-white rounded-2xl border border-amber-100 overflow-hidden shadow-sm hover:shadow-xl hover:border-amber-200 transition-all duration-500 flex flex-col group">
            
            <div class="relative aspect-square w-full bg-amber-50/50 overflow-hidden border-b border-amber-50">
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
            </div>

            <div class="p-6 flex flex-col flex-grow">
                <h3 class="text-lg font-bold text-amber-950 line-clamp-1 mb-1 group-hover:text-amber-600 transition-colors">
                    {{ $product->name }}
                </h3>
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

                <div class="flex items-center gap-2">
                    <form action="/cart/add" method="POST" class="w-full">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="w-full py-2.5 bg-amber-800 hover:bg-amber-900 text-white rounded-xl text-xs font-bold transition-all shadow-md flex items-center justify-center gap-2" {{ $product->stock < 1 ? 'disabled' : '' }}>
                            <i class="fa-solid fa-cart-plus"></i> Beli
                        </button>
                    </form>
                    
                    @if(auth()->check() && auth()->user()->role === 'admin')
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Yakin ingin menghapus produk ini?')" class="w-10 h-10 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-100 rounded-xl flex items-center justify-center transition-colors">
                            <i class="fa-regular fa-trash-can text-sm"></i>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection