@extends('layouts.admin')

@section('title', 'Edit Kategori - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col md:flex-row gap-8">

    @include('admin.partials.sidebar')

    <div class="flex-grow space-y-6">

        <div class="flex items-center gap-3">
            <a href="{{ route('categories.index') }}" class="w-9 h-9 flex items-center justify-center rounded-lg border border-amber-100 text-amber-700 hover:bg-amber-50 transition-colors">
                <i class="fa-solid fa-arrow-left text-xs"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-amber-950">Edit Kategori</h1>
                <p class="text-amber-700/80 text-sm">Perbarui nama kategori "{{ $category->name }}".</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-amber-100 shadow-sm p-6 sm:p-8 max-w-xl">
            <form action="{{ route('categories.update', $category) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-xs font-bold text-amber-900 uppercase tracking-wide mb-2">
                        Nama Kategori
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}"
                           class="w-full px-4 py-3 rounded-xl border {{ $errors->has('name') ? 'border-rose-300 focus:ring-rose-200' : 'border-amber-100 focus:ring-amber-300' }} bg-amber-50/40 text-sm text-amber-950 outline-none focus:ring-2 focus:border-amber-300 transition-all">
                    @error('name')
                        <p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-2">
                    <button type="submit"
                            class="flex-1 sm:flex-none px-8 py-3 bg-amber-800 hover:bg-amber-900 text-white font-bold rounded-xl text-sm shadow-md transition-colors flex items-center justify-center gap-2">
                        <i class="fa-solid fa-check"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('categories.index') }}"
                       class="flex-1 sm:flex-none px-8 py-3 border border-amber-200 text-amber-800 hover:bg-amber-50 font-bold rounded-xl text-sm transition-colors text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection