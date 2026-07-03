@extends('layouts.admin')

@section('title', 'Edit Produk - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col md:flex-row gap-8">

    @include('admin.partials.sidebar')

    <div class="flex-grow space-y-6">

        <div class="flex items-center gap-3">
            <a href="{{ route('products.index') }}" class="w-9 h-9 flex items-center justify-center rounded-lg border border-amber-100 text-amber-700 hover:bg-amber-50 transition-colors">
                <i class="fa-solid fa-arrow-left text-xs"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-amber-950">Edit Produk</h1>
                <p class="text-amber-700/80 text-sm">Perbarui detail "{{ $product->name }}".</p>
            </div>
        </div>

        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2 max-w-3xl">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white rounded-2xl border border-amber-100 shadow-sm p-6 sm:p-8 max-w-3xl">
            @php
                $mainImageUrl = $product->image ? asset('storage/' . $product->image) : null;
                $existingVariants = $product->variants->map(fn($v) => [
                    'id' => $v->id,
                    'name' => $v->name,
                    'price' => $v->price,
                    'stock' => $v->stock,
                ])->values();
            @endphp
            <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6"
                  x-data="{
                    mainPreview: {{ Js::from($mainImageUrl) }},
                    galleryPreviews: [],
                    variants: {{ Js::from($existingVariants) }},
                    addVariant() { this.variants.push({ id: null, name: '', price: '', stock: '' }); },
                    removeVariant(i) { this.variants.splice(i, 1); },
                    onGalleryChange(e) {
                        this.galleryPreviews = Array.from(e.target.files).map(f => URL.createObjectURL(f));
                    }
                  }">
                @csrf
                @method('PUT')

                {{-- Nama --}}
                <div>
                    <label for="name" class="block text-xs font-bold text-amber-900 uppercase tracking-wide mb-2">Nama Produk</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}"
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
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                              class="w-full px-4 py-3 rounded-xl border {{ $errors->has('description') ? 'border-rose-300 focus:ring-rose-200' : 'border-amber-100 focus:ring-amber-300' }} bg-amber-50/40 text-sm text-amber-950 outline-none focus:ring-2 focus:border-amber-300 transition-all resize-none">{{ old('description', $product->description) }}</textarea>
                    @error('description')<p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>@enderror
                </div>

                {{-- Harga & Stok default --}}
                <div class="grid sm:grid-cols-2 gap-5">
                    <div>
                        <label for="price" class="block text-xs font-bold text-amber-900 uppercase tracking-wide mb-2">Harga Dasar (Rp)</label>
                        <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" min="0" step="100"
                               class="w-full px-4 py-3 rounded-xl border {{ $errors->has('price') ? 'border-rose-300 focus:ring-rose-200' : 'border-amber-100 focus:ring-amber-300' }} bg-amber-50/40 text-sm text-amber-950 outline-none focus:ring-2 focus:border-amber-300 transition-all">
                        @error('price')<p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>@enderror
                        <p class="text-[11px] text-gray-400 mt-1.5">Dipakai kalau produk tidak punya varian.</p>
                    </div>
                    <div>
                        <label for="stock" class="block text-xs font-bold text-amber-900 uppercase tracking-wide mb-2">Stok Dasar</label>
                        <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" min="0"
                               class="w-full px-4 py-3 rounded-xl border {{ $errors->has('stock') ? 'border-rose-300 focus:ring-rose-200' : 'border-amber-100 focus:ring-amber-300' }} bg-amber-50/40 text-sm text-amber-950 outline-none focus:ring-2 focus:border-amber-300 transition-all">
                        @error('stock')<p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Gambar Utama --}}
                <div>
                    <label class="block text-xs font-bold text-amber-900 uppercase tracking-wide mb-2">Gambar Utama</label>
                    <label for="image"
                           class="flex flex-col items-center justify-center gap-2 border-2 border-dashed {{ $errors->has('image') ? 'border-rose-300' : 'border-amber-200' }} rounded-xl py-8 px-4 cursor-pointer hover:bg-amber-50/40 transition-colors overflow-hidden relative">
                        <template x-if="!mainPreview">
                            <div class="flex flex-col items-center gap-2 text-amber-500">
                                <i class="fa-solid fa-cloud-arrow-up text-2xl"></i>
                                <span class="text-xs font-semibold text-amber-700">Klik untuk ganti gambar utama</span>
                                <span class="text-[11px] text-amber-400">PNG, JPG, maks 2MB</span>
                            </div>
                        </template>
                        <template x-if="mainPreview">
                            <div class="flex flex-col items-center gap-2">
                                <img :src="mainPreview" class="w-32 h-32 object-cover rounded-lg border border-amber-100">
                                <span class="text-[11px] text-amber-500">Klik untuk ganti gambar</span>
                            </div>
                        </template>
                        <input type="file" name="image" id="image" accept="image/*" class="hidden"
                               @change="mainPreview = $event.target.files.length ? URL.createObjectURL($event.target.files[0]) : mainPreview">
                    </label>
                    @error('image')<p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>@enderror
                    <p class="text-[11px] text-gray-400 mt-1.5">Kosongkan jika tidak ingin mengganti gambar utama.</p>
                </div>

                {{-- Galeri Foto Tambahan --}}
                <div>
                    <label class="block text-xs font-bold text-amber-900 uppercase tracking-wide mb-2">Foto Tambahan (Galeri)</label>

                    {{-- Foto yang sudah ada --}}
                    @if($product->images->isNotEmpty())
                    <div class="flex flex-wrap gap-3 mb-3">
                        @foreach($product->images as $img)
                        <div class="relative group">
                            <img src="{{ asset('storage/' . $img->image) }}" class="w-20 h-20 object-cover rounded-lg border border-amber-100">
                            <form action="{{ route('products.images.destroy', [$product, $img]) }}" method="POST"
                                  onsubmit="return confirm('Hapus foto ini?')"
                                  class="absolute -top-2 -right-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-6 h-6 bg-rose-600 hover:bg-rose-700 text-white rounded-full flex items-center justify-center text-[10px] shadow-md">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                    <p class="text-[11px] text-gray-400 mb-3">Klik ✕ merah untuk hapus foto lama. Foto baru yang diunggah di bawah akan ditambahkan (bukan menggantikan).</p>
                    @endif

                    <label for="images"
                           class="flex flex-col items-center justify-center gap-2 border-2 border-dashed border-amber-200 rounded-xl py-6 px-4 cursor-pointer hover:bg-amber-50/40 transition-colors">
                        <div class="flex flex-col items-center gap-2 text-amber-500">
                            <i class="fa-solid fa-images text-xl"></i>
                            <span class="text-xs font-semibold text-amber-700">Tambah foto baru (opsional)</span>
                        </div>
                        <input type="file" name="images[]" id="images" accept="image/*" multiple class="hidden" @change="onGalleryChange">
                    </label>
                    <template x-if="galleryPreviews.length">
                        <div class="flex flex-wrap gap-2 mt-3">
                            <template x-for="(src, i) in galleryPreviews" :key="i">
                                <img :src="src" class="w-16 h-16 object-cover rounded-lg border border-amber-100">
                            </template>
                        </div>
                    </template>
                    @error('images.*')<p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>@enderror
                </div>

                {{-- Varian --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold text-amber-900 uppercase tracking-wide">Varian (Opsional)</label>
                        <button type="button" @click="addVariant()" class="text-xs font-semibold text-amber-700 hover:text-amber-900 flex items-center gap-1">
                            <i class="fa-solid fa-plus"></i> Tambah Varian
                        </button>
                    </div>
                    <p class="text-[11px] text-gray-400 mb-3">Contoh: "250g", "500g", atau "Merah", "Biru". Kosongkan / hapus semua baris kalau produk tidak punya varian.</p>

                    <div class="space-y-3">
                        <template x-if="variants.length === 0">
                            <p class="text-xs text-gray-400 italic">Belum ada varian.</p>
                        </template>
                        <template x-for="(variant, index) in variants" :key="index">
                            <div class="flex flex-wrap sm:flex-nowrap items-start gap-2 bg-amber-50/40 border border-amber-100 rounded-xl p-3">
                                <input type="hidden" :name="'variants[' + index + '][id]'" :value="variant.id">
                                <input type="text" :name="'variants[' + index + '][name]'" x-model="variant.name"
                                       placeholder="Nama varian"
                                       class="flex-1 min-w-[120px] px-3 py-2 rounded-lg border border-amber-100 bg-white text-sm text-amber-950 outline-none focus:ring-2 focus:ring-amber-300">
                                <input type="number" :name="'variants[' + index + '][price]'" x-model="variant.price"
                                       placeholder="Harga" min="0" step="100"
                                       class="w-28 px-3 py-2 rounded-lg border border-amber-100 bg-white text-sm text-amber-950 outline-none focus:ring-2 focus:ring-amber-300">
                                <input type="number" :name="'variants[' + index + '][stock]'" x-model="variant.stock"
                                       placeholder="Stok" min="0"
                                       class="w-24 px-3 py-2 rounded-lg border border-amber-100 bg-white text-sm text-amber-950 outline-none focus:ring-2 focus:ring-amber-300">
                                <button type="button" @click="removeVariant(index)"
                                        class="w-9 h-9 flex items-center justify-center rounded-lg border border-rose-100 text-rose-600 hover:bg-rose-50 transition-colors flex-shrink-0">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                    <p class="text-[11px] text-amber-500 mt-2">
                        <i class="fa-solid fa-circle-info"></i> Varian yang dihapus dari daftar ini di sini <strong>tidak otomatis terhapus</strong> dari database saat disimpan — fitur hapus varian individual belum tersedia, hubungi developer kalau perlu.
                    </p>
                </div>

                {{-- Tampilkan di Beranda --}}
                <div class="flex items-start gap-3 bg-amber-50/60 border border-amber-100 rounded-xl p-4">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                           class="mt-0.5 w-4 h-4 rounded border-amber-300 text-amber-700 focus:ring-amber-400">
                    <label for="is_featured" class="text-sm">
                        <span class="font-semibold text-amber-950 block">Tampilkan di "Produk Pilihan" (Beranda)</span>
                        <span class="text-xs text-gray-500">Produk akan tetap muncul di beranda sampai kamu matikan sendiri — tidak otomatis berubah saat ada produk baru.</span>
                    </label>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-2">
                    <button type="submit"
                            class="flex-1 sm:flex-none px-8 py-3 bg-amber-800 hover:bg-amber-900 text-white font-bold rounded-xl text-sm shadow-md transition-colors flex items-center justify-center gap-2">
                        <i class="fa-solid fa-check"></i> Simpan Perubahan
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