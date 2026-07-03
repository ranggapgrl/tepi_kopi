@extends('layouts.app')

@section('title', 'Kelola Produk')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">

    <div class="flex flex-col md:flex-row gap-8">

        {{-- Sidebar --}}
        <aside class="w-full md:w-64 flex-shrink-0">
            <div class="bg-white p-5 rounded-2xl border border-amber-100 shadow-sm space-y-2">

                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 px-2">
                    Menu Kelola
                </p>

                <a href="/admin"
                    class="flex items-center gap-3 px-3 py-2.5 text-gray-600 hover:bg-amber-50 hover:text-amber-800 font-medium rounded-xl transition-colors">
                    <i class="fa-solid fa-chart-pie w-5"></i>
                    Dashboard
                </a>

                <a href="/products"
                    class="flex items-center gap-3 px-3 py-2.5 bg-amber-50 text-amber-800 font-semibold rounded-xl">
                    <i class="fa-solid fa-mug-hot w-5"></i>
                    Kelola Produk
                </a>

                <a href="/categories"
                    class="flex items-center gap-3 px-3 py-2.5 text-gray-600 hover:bg-amber-50 hover:text-amber-800 font-medium rounded-xl transition-colors">
                    <i class="fa-solid fa-tags w-5"></i>
                    Kategori Menu
                </a>

                <a href="/orders"
                    class="flex items-center gap-3 px-3 py-2.5 text-gray-600 hover:bg-amber-50 hover:text-amber-800 font-medium rounded-xl transition-colors">
                    <i class="fa-solid fa-receipt w-5"></i>
                    Pesanan Masuk
                </a>

            </div>
        </aside>

        {{-- Content --}}
        <div class="flex-1">

            <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-amber-100 pb-8">
                <div>
                    <h1 class="text-3xl font-extrabold text-amber-950">
                        Kelola Produk
                    </h1>
                    <p class="text-amber-700/80 text-sm">
                        Panel admin untuk menambah, mengubah, dan menghapus produk.
                    </p>
                </div>

                <a href="{{ route('products.create') }}"
                    class="inline-flex items-center px-5 py-2.5 bg-amber-800 hover:bg-amber-900 text-white rounded-xl shadow-md transition">
                    <i class="fa-solid fa-plus mr-2"></i>
                    Tambah Produk
                </a>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            @if($products->isEmpty())

                <div class="bg-white rounded-2xl border border-amber-100 shadow-sm p-10 text-center">

                    <div class="w-20 h-20 mx-auto rounded-full bg-amber-50 flex items-center justify-center text-amber-600 mb-5">
                        <i class="fa-solid fa-box-open text-3xl"></i>
                    </div>

                    <h3 class="text-lg font-bold text-amber-900">
                        Belum ada produk
                    </h3>

                    <p class="text-gray-500 mt-2">
                        Etalase masih kosong.
                    </p>

                    <a href="{{ route('products.create') }}"
                        class="inline-flex mt-6 px-6 py-3 bg-amber-700 hover:bg-amber-800 text-white rounded-xl">
                        Tambah Produk Pertama
                    </a>

                </div>

            @else

                <div class="bg-white rounded-2xl border border-amber-100 shadow-sm overflow-hidden">

                    <div class="overflow-x-auto">

                        <table class="w-full">

                            <thead class="bg-amber-900 text-white">

                                <tr>
                                    <th class="px-6 py-4 text-left">Produk</th>
                                    <th class="px-6 py-4 text-left">Kategori</th>
                                    <th class="px-6 py-4 text-left">Harga</th>
                                    <th class="px-6 py-4 text-left">Stok</th>
                                    <th class="px-6 py-4 text-right">Aksi</th>
                                </tr>

                            </thead>

                            <tbody class="divide-y divide-amber-100">

                                @foreach($products as $product)

                                <tr class="hover:bg-amber-50">

                                    <td class="px-6 py-4">

                                        <div class="flex items-center gap-3">

                                            <div class="w-12 h-12 rounded-lg bg-amber-50 border border-amber-100 overflow-hidden">

                                                @if($product->image)

                                                <img src="{{ asset('storage/'.$product->image) }}"
                                                    class="w-full h-full object-cover">

                                                @else

                                                <div class="w-full h-full flex items-center justify-center text-amber-400">
                                                    <i class="fa-solid fa-mug-hot"></i>
                                                </div>

                                                @endif

                                            </div>

                                            <div>

                                                <div class="font-semibold text-amber-950">
                                                    {{ $product->name }}
                                                </div>

                                                <div class="text-xs text-gray-500">
                                                    {{ $product->description }}
                                                </div>

                                            </div>

                                        </div>

                                    </td>

                                    <td class="px-6 py-4">
                                        {{ $product->category->name ?? '-' }}
                                    </td>

                                    <td class="px-6 py-4 font-semibold">
                                        Rp {{ number_format($product->price,0,',','.') }}
                                    </td>

                                    <td class="px-6 py-4">

                                        @if($product->stock>0)

                                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs">
                                                {{ $product->stock }} pcs
                                            </span>

                                        @else

                                            <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs">
                                                Habis
                                            </span>

                                        @endif

                                    </td>

                                    <td class="px-6 py-4">

                                        <div class="flex justify-end gap-2">

                                            <a href="{{ route('products.edit',$product->id) }}"
                                                class="w-10 h-10 rounded-lg bg-amber-50 hover:bg-amber-100 flex items-center justify-center text-amber-700">

                                                <i class="fa-regular fa-pen-to-square"></i>

                                            </a>

                                            <form action="{{ route('products.destroy',$product->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus produk ini?')">

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    class="w-10 h-10 rounded-lg bg-red-50 hover:bg-red-100 text-red-600">

                                                    <i class="fa-regular fa-trash-can"></i>

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

    </div>

</div>
@endsection