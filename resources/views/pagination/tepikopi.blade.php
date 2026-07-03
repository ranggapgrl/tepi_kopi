@if ($paginator->hasPages())
<nav role="navigation" aria-label="Pagination" class="flex flex-col sm:flex-row items-center justify-between gap-4">

    {{-- Info jumlah data --}}
    <p class="text-xs sm:text-sm text-amber-800/60 order-2 sm:order-1">
        Menampilkan
        <span class="font-semibold text-amber-900">{{ $paginator->firstItem() }}</span>
        –
        <span class="font-semibold text-amber-900">{{ $paginator->lastItem() }}</span>
        dari
        <span class="font-semibold text-amber-900">{{ $paginator->total() }}</span>
        produk
    </p>

    {{-- Tombol halaman --}}
    <div class="flex items-center gap-1.5 order-1 sm:order-2">

        {{-- Sebelumnya --}}
        @if ($paginator->onFirstPage())
            <span class="w-9 h-9 flex items-center justify-center rounded-lg border border-amber-100 text-amber-300 cursor-not-allowed">
                <i class="fa-solid fa-chevron-left text-xs"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
               class="w-9 h-9 flex items-center justify-center rounded-lg border border-amber-100 text-amber-800 hover:bg-amber-50 transition-colors">
                <i class="fa-solid fa-chevron-left text-xs"></i>
            </a>
        @endif

        {{-- Nomor halaman --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="w-9 h-9 flex items-center justify-center text-amber-400 text-sm">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="w-9 h-9 flex items-center justify-center rounded-lg bg-amber-800 text-white text-sm font-bold shadow-sm">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}"
                           class="w-9 h-9 flex items-center justify-center rounded-lg border border-amber-100 text-amber-800 hover:bg-amber-50 text-sm font-semibold transition-colors">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Berikutnya --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
               class="w-9 h-9 flex items-center justify-center rounded-lg border border-amber-100 text-amber-800 hover:bg-amber-50 transition-colors">
                <i class="fa-solid fa-chevron-right text-xs"></i>
            </a>
        @else
            <span class="w-9 h-9 flex items-center justify-center rounded-lg border border-amber-100 text-amber-300 cursor-not-allowed">
                <i class="fa-solid fa-chevron-right text-xs"></i>
            </span>
        @endif
    </div>
</nav>
@endif