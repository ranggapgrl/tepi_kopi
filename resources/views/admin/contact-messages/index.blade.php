@extends('layouts.admin')

@section('title', 'Pesan Kontak - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col md:flex-row gap-8">

    @include('admin.partials.sidebar')

    <div class="flex-grow space-y-6">

        <div>
            <h1 class="text-2xl font-bold text-amber-950">Pesan Kontak</h1>
            <p class="text-amber-700/80 text-sm">Pesan yang masuk lewat form "Hubungi Kami" di website.</p>
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
                    <i class="fa-solid fa-envelope text-lg"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Pesan</p>
                    <p class="text-xl font-bold text-amber-950">{{ $totalMessages }} Pesan</p>
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-amber-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-rose-50 flex items-center justify-center text-rose-600">
                    <i class="fa-solid fa-envelope-open-text text-lg"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Belum Dibaca</p>
                    <p class="text-xl font-bold text-amber-950">{{ $unreadCount }} Pesan</p>
                </div>
            </div>
        </div>

        {{-- Pencarian --}}
        <form action="{{ route('contact-messages.index') }}" method="GET" class="flex items-center gap-2">
            <div class="relative flex-grow max-w-md">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama, email, subjek, atau isi pesan..."
                       class="text-sm border border-amber-100 rounded-lg pl-9 pr-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-amber-200">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-amber-300 text-xs"></i>
            </div>
        </form>

        <div class="bg-white rounded-2xl border border-amber-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-amber-50 bg-amber-950 text-white flex justify-between items-center">
                <h3 class="font-bold">Daftar Pesan</h3>
                <span class="text-xs text-amber-200">{{ $messages->total() }} pesan</span>
            </div>

            @if($messages->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-amber-50 rounded-full flex items-center justify-center text-amber-600 mx-auto mb-4">
                        <i class="fa-solid fa-envelope text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-amber-950 mb-1">Belum ada pesan</h3>
                    <p class="text-sm text-gray-500">Pesan dari form kontak akan muncul di sini.</p>
                </div>
            @else
                <div class="divide-y divide-amber-50">
                    @foreach($messages as $message)
                    <a href="{{ route('contact-messages.show', $message) }}"
                       class="p-6 flex flex-col sm:flex-row sm:items-start gap-4 hover:bg-gray-50/60 transition-colors">

                        <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center font-bold flex-shrink-0">
                            {{ strtoupper(substr($message->name, 0, 1)) }}
                        </div>

                        <div class="flex-grow min-w-0">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <div>
                                    <p class="font-semibold text-amber-950 flex items-center gap-2">
                                        {{ $message->name }}
                                        @if(! $message->read_at)
                                            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-400">
                                        {{ $message->email }} · {{ $message->created_at->translatedFormat('d M Y, H:i') }}
                                    </p>
                                </div>
                            </div>

                            <p class="text-sm font-medium text-amber-800 mt-2">{{ $message->subject }}</p>
                            <p class="text-sm text-gray-500 mt-1 line-clamp-1">{{ $message->message }}</p>
                        </div>
                    </a>
                    @endforeach
                </div>

                <div class="p-6 border-t border-amber-50">
                    {{ $messages->onEachSide(1)->links('pagination.tepikopi', ['itemLabel' => 'pesan']) }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection