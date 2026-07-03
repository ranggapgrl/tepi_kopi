@extends('layouts.admin')

@section('title', 'Kategori Produk - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col md:flex-row gap-8">

    @include('admin.partials.sidebar')

    <div class="flex-grow space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-amber-950">Kategori Produk</h1>
                <p class="text-amber-700/80 text-sm">Kelola kategori untuk mengelompokkan produk di katalog.</p>
            </div>
            <a href="{{ route('categories.create') }}"
               class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-amber-800 hover:bg-amber-900 text-white font-bold rounded-xl text-sm shadow-md transition-colors whitespace-nowrap">
                <i class="fa-solid fa-plus"></i> Tambah Kategori
            </a>
        </div>

        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation"></i>
            {{ session('error') }}
        </div>
        @endif

        <div class="bg-white rounded-2xl border border-amber-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-amber-50 bg-amber-950 text-white flex justify-between items-center">
                <h3 class="font-bold">Daftar Kategori</h3>
                <span class="text-xs text-amber-200">{{ $categories->count() }} kategori</span>
            </div>

            @if($categories->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-amber-50 rounded-full flex items-center justify-center text-amber-600 mx-auto mb-4">
                        <i class="fa-solid fa-tags text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-amber-950 mb-1">Belum ada kategori</h3>
                    <p class="text-sm text-gray-500 mb-5">Tambahkan kategori pertama untuk mengelompokkan produk.</p>
                    <a href="{{ route('categories.create') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-amber-800 hover:text-amber-950">
                        <i class="fa-solid fa-plus"></i> Tambah Kategori
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-amber-50/50 text-amber-900 border-b border-amber-100">
                            <tr>
                                <th class="px-6 py-3 font-semibold">Nama Kategori</th>
                                <th class="px-6 py-3 font-semibold">Jumlah Produk</th>
                                <th class="px-6 py-3 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 align-middle">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 flex-shrink-0">
                                            <i class="fa-solid fa-tag text-xs"></i>
                                        </div>
                                        <p class="font-semibold text-amber-950">{{ $category->name }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="bg-amber-100 text-amber-800 text-xs font-semibold px-2.5 py-1 rounded-md">
                                        {{ $category->products_count }} produk
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('categories.edit', $category) }}"
                                           class="w-9 h-9 flex items-center justify-center rounded-lg border border-amber-100 text-amber-700 hover:bg-amber-50 transition-colors"
                                           title="Edit">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </a>
                                        <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                              onsubmit="return confirm('Hapus kategori &quot;{{ $category->name }}&quot;?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="w-9 h-9 flex items-center justify-center rounded-lg border border-rose-100 text-rose-600 hover:bg-rose-50 transition-colors disabled:opacity-40 disabled:pointer-events-none"
                                                    title="Hapus"
                                                    {{ $category->products_count > 0 ? 'disabled' : '' }}>
                                                <i class="fa-solid fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection