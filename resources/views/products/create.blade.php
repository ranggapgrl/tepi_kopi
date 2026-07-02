@extends('layouts.app')

@section('title', 'Tambah Produk - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col md:flex-row gap-8">

    @include('admin.partials.sidebar')

    <div class="flex-grow space-y-6">

        <div class="flex items-center gap-3">
            <a href="{{ route('products.index') }}" class="w-9 h-9 flex items-center justify-center rounded-lg border border-amber-100 text-amber-700 hover:bg-amber-50 transition-colors">
                <i class="fa-solid fa-arrow-left text-xs"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-amber-950">Tambah Produk</h1>
                <p class="text-amber-700/80 text-sm">Isi detail produk yang akan tampil di katalog.</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-amber-100 shadow-sm p-6 sm:p-8 max-w-3xl">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5" x-data="{ preview: null }">
                @csrf

                {{-- Nama --}}
                <div>
                    <label for="name" class="block text-xs font-bold text-amber-900 uppercase tracking-wide mb-2">Nama Produk</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                           placeholder="Contoh: Kopi Susu Gula Aren"
                           class="w-full px-4 py-3 rounded-xl border {{ $errors->has('name') ? 'border-rose-300 focus:ring-rose-200' : 'border-amber-100 focus:ring-amber-300' }} bg-amber-50/40 text-sm text-amber-950 outline-none focus:ring-2 focus:border-amber-300 transition-all">
                    @error('name')<p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>@enderror
                </div>

                {{-- Kategori --}}
                <div>
                    <label for="category_id" class="block text-xs font-bold text-amber-900 uppercase tracking-wide mb-2">Kategori</label>
                    <select name="category_id" id="category_id"
                            class="w-full px-4 py-3 rounded-xl border {{ $errors->has('category_id') ? 'border-rose-300 focus:ring-rose-200' : 'border-amber-100 focus:ring-amber-300' }} bg-amber-50/40 text-sm text-amber-950 outline-none focus:ring-2 focus:border-amber-300 transition-all">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>@enderror
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label for="description" class="block text-xs font-bold text-amber-900 uppercase tracking-wide mb-2">Deskripsi</label>
                    <textarea name="description" id="description" rows="4"
                              placeholder="Deskripsi singkat produk (opsional)"
                              class="w-full px-4 py-3 rounded-xl border {{ $errors->has('description') ? 'border-rose-300 focus:ring-rose-200' : 'border-amber-100 focus:ring-amber-300' }} bg-amber-50/40 text-sm text-amber-950 outline-none focus:ring-2 focus:border-amber-300 transition-all resize-none">{{ old('description') }}</textarea>
                    @error('description')<p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>@enderror
                </div>

                {{-- Harga & Stok --}}
                <div class="grid sm:grid-cols-2 gap-5">
                    <div>
                        <label for="price" class="block text-xs font-bold text-amber-900 uppercase tracking-wide mb-2">Harga (Rp)</label>
                        <input type="number" name="price" id="price" value="{{ old('price') }}" min="0" step="100"
                               placeholder="25000"
                               class="w-full px-4 py-3 rounded-xl border {{ $errors->has('price') ? 'border-rose-300 focus:ring-rose-200' : 'border-amber-100 focus:ring-amber-300' }} bg-amber-50/40 text-sm text-amber-950 outline-none focus:ring-2 focus:border-amber-300 transition-all">
                        @error('price')<p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="stock" class="block text-xs font-bold text-amber-900 uppercase tracking-wide mb-2">Stok</label>
                        <input type="number" name="stock" id="stock" value="{{ old('stock') }}" min="0"
                               placeholder="20"
                               class="w-full px-4 py-3 rounded-xl border {{ $errors->has('stock') ? 'border-rose-300 focus:ring-rose-200' : 'border-amber-100 focus:ring-amber-300' }} bg-amber-50/40 text-sm text-amber-950 outline-none focus:ring-2 focus:border-amber-300 transition-all">
                        @error('stock')<p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Gambar --}}
                <div>
                    <label class="block text-xs font-bold text-amber-900 uppercase tracking-wide mb-2">Gambar Produk</label>

                    <label for="image"
                           class="flex flex-col items-center justify-center gap-2 border-2 border-dashed {{ $errors->has('image') ? 'border-rose-300' : 'border-amber-200' }} rounded-xl py-8 px-4 cursor-pointer hover:bg-amber-50/40 transition-colors overflow-hidden relative">
                        <template x-if="!preview">
                            <div class="flex flex-col items-center gap-2 text-amber-500">
                                <i class="fa-solid fa-cloud-arrow-up text-2xl"></i>
                                <span class="text-xs font-semibold text-amber-700">Klik untuk unggah gambar</span>
                                <span class="text-[11px] text-amber-400">PNG, JPG, maks 2MB</span>
                            </div>
                        </template>
                        <template x-if="preview">
                            <img :src="preview" class="w-32 h-32 object-cover rounded-lg border border-amber-100">
                        </template>
                        <input type="file" name="image" id="image" accept="image/*" class="hidden"
                               @change="preview = $event.target.files.length ? URL.createObjectURL($event.target.files[0]) : null">
                    </label>
                    @error('image')<p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>@enderror
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-2">
                    <button type="submit"
                            class="flex-1 sm:flex-none px-8 py-3 bg-amber-800 hover:bg-amber-900 text-white font-bold rounded-xl text-sm shadow-md transition-colors flex items-center justify-center gap-2">
                        <i class="fa-solid fa-check"></i> Simpan Produk
                    </button>
                    <a href="{{ route('products.index') }}"
                       class="flex-1 sm:flex-none px-8 py-3 border border-amber-200 text-amber-800 hover:bg-amber-50 font-bold rounded-xl text-sm transition-colors text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection