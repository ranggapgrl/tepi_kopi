@extends('layouts.admin')

@section('title', 'Ulasan Pelanggan - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col md:flex-row gap-8">

    @include('admin.partials.sidebar')

    <div class="flex-grow space-y-6">

        <div>
            <h1 class="text-2xl font-bold text-amber-950">Ulasan Pelanggan</h1>
            <p class="text-amber-700/80 text-sm">Pantau apa kata pelanggan tentang produk-produk Tepi Kopi.</p>
        </div>

        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
        @endif

        {{-- Ringkasan --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-amber-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center text-amber-700">
                    <i class="fa-solid fa-comments text-lg"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Ulasan</p>
                    <p class="text-xl font-bold text-amber-950">{{ $totalReviews }} Ulasan</p>
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-amber-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center text-amber-700">
                    <i class="fa-solid fa-star text-lg"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Rata-rata Rating</p>
                    <p class="text-xl font-bold text-amber-950">{{ $averageRating }} / 5</p>
                </div>
            </div>
        </div>

        {{-- Filter & Pencarian --}}
        <form action="{{ route('reviews.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
            <a href="{{ route('reviews.index') }}"
               class="text-xs font-semibold px-3.5 py-2 rounded-lg transition-colors {{ !request('rating') ? 'bg-amber-800 text-white' : 'bg-white border border-amber-100 text-amber-800 hover:bg-amber-50' }}">
                Semua Rating
            </a>
            @for ($i = 5; $i >= 1; $i--)
                <a href="{{ route('reviews.index', ['rating' => $i, 'search' => request('search')]) }}"
                   class="text-xs font-semibold px-3.5 py-2 rounded-lg transition-colors flex items-center gap-1 {{ (string) request('rating') === (string) $i ? 'bg-amber-800 text-white' : 'bg-white border border-amber-100 text-amber-800 hover:bg-amber-50' }}">
                    {{ $i }} <i class="fa-solid fa-star text-[10px]"></i>
                </a>
            @endfor

            <div class="flex-grow"></div>

            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari produk, pelanggan, atau komentar..."
                       class="text-sm border border-amber-100 rounded-lg pl-9 pr-3 py-2 w-64 focus:outline-none focus:ring-2 focus:ring-amber-200">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-amber-300 text-xs"></i>
            </div>
        </form>

        <div class="bg-white rounded-2xl border border-amber-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-amber-50 bg-amber-950 text-white flex justify-between items-center">
                <h3 class="font-bold">Daftar Ulasan</h3>
                <span class="text-xs text-amber-200">{{ $reviews->total() }} ulasan</span>
            </div>

            @if($reviews->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-amber-50 rounded-full flex items-center justify-center text-amber-600 mx-auto mb-4">
                        <i class="fa-solid fa-star text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-amber-950 mb-1">Belum ada ulasan</h3>
                    <p class="text-sm text-gray-500">Ulasan dari pelanggan akan muncul di sini.</p>
                </div>
            @else
                <div class="divide-y divide-amber-50">
                    @foreach($reviews as $review)
                    <div class="p-6 flex flex-col sm:flex-row sm:items-start gap-4 hover:bg-gray-50/60">

                        {{-- Avatar pelanggan --}}
                        <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center font-bold flex-shrink-0">
                            {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                        </div>

                        <div class="flex-grow min-w-0">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <div>
                                    <p class="font-semibold text-amber-950">{{ $review->user->name ?? 'Pengguna Dihapus' }}</p>
                                    <p class="text-xs text-gray-400">
                                        untuk produk
                                        <span class="font-medium text-amber-700">{{ $review->product->name ?? 'Produk Dihapus' }}</span>
                                        · {{ $review->created_at->translatedFormat('d M Y, H:i') }}
                                    </p>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="flex items-center gap-0.5 text-amber-400 text-sm">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fa-{{ $i <= $review->rating ? 'solid' : 'regular' }} fa-star"></i>
                                        @endfor
                                    </div>

                                    <form action="{{ route('reviews.destroy', $review) }}" method="POST"
                                          onsubmit="return confirm('Hapus ulasan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-xs font-semibold text-rose-600 hover:text-rose-800 transition-colors">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            @if($review->comment)
                                <p class="text-sm text-gray-600 mt-2 leading-relaxed">{{ $review->comment }}</p>
                            @else
                                <p class="text-sm text-gray-400 italic mt-2">Tidak ada komentar tertulis.</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="p-6 border-t border-amber-50">
                    {{ $reviews->onEachSide(1)->links('pagination.tepikopi', ['itemLabel' => 'ulasan']) }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
