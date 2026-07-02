@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-10">

    <div class="mb-6">
        <a href="{{ route('products.index') }}" class="inline-flex items-center text-sm font-medium text-amber-800 hover:text-amber-600 transition-colors">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Kelola Produk
        </a>
    </div>

    <div class="bg-white border border-amber-100 rounded-3xl shadow-sm overflow-hidden">

        <div class="px-8 py-6 bg-gradient-to-r from-amber-900 to-amber-950 text-white relative overflow-hidden">
            <div class="relative z-10">
                <h1 class="text-xl font-bold mb-1">Edit Produk</h1>
                <p class="text-amber-200/80 text-xs font-light">Perbarui informasi "{{ $product->name }}".</p>
            </div>
            <div class="absolute right-4 top-1/2 -translate-y-1/2 opacity-10 text-6xl pointer-events-none">
                <i class="fa-solid fa-leaf"></i>
            </div>
        </div>

        @if ($errors->any())
        <div class="mx-8 mt-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6" x-data="{ imageUrl: null }">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold tracking-wide text-amber-900 uppercase mb-2">Nama Produk</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400 pointer-events-none text-sm">
                            <i class="fa-solid fa-tag"></i>
                        </span>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" required placeholder="Contoh: Kopi Susu Aren"
                            class="w-full pl-10 pr-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-amber-600/20 focus:border-amber-700 transition-all outline-none text-sm placeholder:text-gray-400">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold tracking-wide text-amber-900 uppercase mb-2">Kategori Menu</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400 pointer-events-none text-sm">
                            <i class="fa-solid fa-layer-group"></i>
                        </span>
                        <select name="category_id" required
                            class="w-full pl-10 pr-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-amber-600/20 focus:border-amber-700 transition-all outline-none text-sm appearance-none cursor-pointer">
                            <option value="" disabled>Pilih Kategori...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <span class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 pointer-events-none text-xs">
                            <i class="fa-solid fa-chevron-down"></i>
                        </span>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold tracking-wide text-amber-900 uppercase mb-2">Deskripsi Produk</label>
                <textarea name="description" rows="3" placeholder="Jelaskan rasa, asal biji kopi, atau cara penyajian..."
                    class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-amber-600/20 focus:border-amber-700 transition-all outline-none text-sm placeholder:text-gray-400 resize-none">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold tracking-wide text-amber-900 uppercase mb-2">Harga Jual (Rp)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-amber-900 font-extrabold text-xs pointer-events-none">
                            Rp
                        </span>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}" required placeholder="0" min="0"
                            class="w-full pl-10 pr-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-amber-600/20 focus:border-amber-700 transition-all outline-none text-sm font-semibold">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold tracking-wide text-amber-900 uppercase mb-2">Jumlah Stok</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400 pointer-events-none text-sm">
                            <i class="fa-solid fa-cubes"></i>
                        </span>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required placeholder="0" min="0"
                            class="w-full pl-10 pr-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-amber-600/20 focus:border-amber-700 transition-all outline-none text-sm font-semibold">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold tracking-wide text-amber-900 uppercase mb-2">Foto Produk</label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-center">

                    <div class="sm:col-span-2 relative group border-2 border-dashed border-gray-200 hover:border-amber-600 rounded-2xl bg-gray-50/30 hover:bg-amber-50/10 p-6 text-center transition-all duration-300 flex flex-col items-center justify-center cursor-pointer min-h-[140px]">
                        <input type="file" name="image" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                            @change="const file = $event.target.files[0]; if (file) { imageUrl = URL.createObjectURL(file) }">
                        <div class="w-10 h-10 rounded-full bg-amber-50 text-amber-700 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-cloud-arrow-up text-sm"></i>
                        </div>
                        <span class="text-xs font-semibold text-amber-950 mb-0.5">Ganti Gambar (opsional)</span>
                        <span class="text-[10px] text-gray-400">Format PNG/JPG (Maks. 2MB)</span>
                    </div>

                    <div class="w-full aspect-square sm:h-[140px] sm:w-[140px] rounded-2xl border border-amber-100 bg-amber-50/40 flex items-center justify-center overflow-hidden mx-auto relative">
                        <template x-if="imageUrl">
                            <img :src="imageUrl" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!imageUrl">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover">
                            @else
                                <div class="text-center text-amber-300">
                                    <i class="fa-regular fa-image text-3xl mb-1 block"></i>
                                    <span class="text-[9px] tracking-wider uppercase font-medium">Preview</span>
                                </div>
                            @endif
                        </template>
                    </div>

                </div>
            </div>

            <div class="pt-6 border-t border-amber-50 flex flex-col-reverse sm:flex-row items-center sm:justify-end gap-3 mt-4">
                <a href="{{ route('products.index') }}" class="w-full sm:w-auto px-6 py-3 border border-gray-200 text-gray-600 hover:bg-gray-50 rounded-xl font-medium text-sm transition-colors text-center">
                    Batal
                </a>
                <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-amber-800 hover:bg-amber-900 text-white rounded-xl font-bold text-sm shadow-md transition-all hover:-translate-y-0.5">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection