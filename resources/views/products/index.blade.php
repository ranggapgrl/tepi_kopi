@extends('layouts.app')

@section('title', 'Kelola Produk')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-amber-100/60 pb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-amber-950 tracking-tight mb-2">Kelola Produk</h1>
            <p class="text-amber-700/80 text-sm">Panel admin untuk menambah, mengubah, dan menghapus produk.</p>
        </div>
        <a href="{{ route('products.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-amber-800 hover:bg-amber-900 text-white font-medium text-sm rounded-xl shadow-md transition-all duration-300 hover:-translate-y-0.5">
            <i class="fa-solid fa-plus mr-2 text-xs"></i> Tambah Produk
        </a>
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
        <a href="{{ route('products.create') }}" class="inline-flex items-center px-6 py-3 bg-amber-700 hover:bg-amber-800 text-white text-sm font-medium rounded-xl transition-colors shadow-md">
            Tambah Produk Pertama
        </a>
    </div>
    @else
    <div class="bg-white rounded-2xl border border-amber-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-amber-50/60 text-amber-900 text-left text-xs uppercase tracking-wider font-bold">
                        <th class="px-6 py-4">Produk</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Harga</th>
                        <th class="px-6 py-4">Stok</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-amber-50">
                    @foreach($products as $product)
                    <tr class="hover:bg-amber-50/30 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-lg bg-amber-50 overflow-hidden shrink-0 border border-amber-100">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover" alt="{{ $product->name }}">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-amber-300">
                                            <i class="fa-solid fa-mug-hot"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-amber-950">{{ $product->name }}</p>
                                    <p class="text-xs text-gray-400 line-clamp-1 max-w-xs">{{ $product->description ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-amber-800">{{ $product->category->name ?? '-' }}</td>
                        <td class="px-6 py-4 font-semibold text-amber-900">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center text-xs font-semibold {{ $product->stock > 0 ? 'text-emerald-700 bg-emerald-50' : 'text-rose-700 bg-rose-50' }} px-2.5 py-1 rounded-md">
                                {{ $product->stock > 0 ? $product->stock . ' Pcs' : 'Habis' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('products.edit', $product->id) }}"
                                class="w-9 h-9 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-100 rounded-lg flex items-center justify-center transition-colors">
                                    <i class="fa-regular fa-pen-to-square text-sm"></i>
                                </a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-9 h-9 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-100 rounded-lg flex items-center justify-center transition-colors">
                                        <i class="fa-regular fa-trash-can text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection