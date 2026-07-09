@extends('layouts.admin')

@section('title', 'Detail Pesan Kontak - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col md:flex-row gap-8">

    @include('admin.partials.sidebar')

    <div class="flex-grow space-y-6 max-w-2xl">

        <div class="flex items-center gap-3">
            <a href="{{ route('contact-messages.index') }}" class="w-9 h-9 flex items-center justify-center rounded-lg border border-amber-100 text-amber-700 hover:bg-amber-50 transition-colors">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-amber-950">Detail Pesan</h1>
                <p class="text-amber-700/80 text-sm">Dikirim {{ $contactMessage->created_at->translatedFormat('d M Y, H:i') }}</p>
            </div>
        </div>

        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white rounded-2xl border border-amber-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-amber-50 flex items-start gap-4">
                <div class="w-12 h-12 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center font-bold text-lg flex-shrink-0">
                    {{ strtoupper(substr($contactMessage->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="font-bold text-amber-950">{{ $contactMessage->name }}</p>
                    <a href="mailto:{{ $contactMessage->email }}" class="text-sm text-amber-700 hover:underline">{{ $contactMessage->email }}</a>
                </div>
            </div>

            <div class="px-6 py-5 space-y-4">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">Subjek</p>
                    <p class="font-semibold text-amber-950">{{ $contactMessage->subject }}</p>
                </div>

                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">Pesan</p>
                    <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $contactMessage->message }}</p>
                </div>
            </div>

            <div class="px-6 py-4 bg-amber-50/50 border-t border-amber-50 flex flex-wrap items-center justify-between gap-3">
                <a href="mailto:{{ $contactMessage->email }}?subject=Re: {{ $contactMessage->subject }}"
                   class="text-sm font-semibold text-white bg-amber-800 hover:bg-amber-900 transition-colors px-4 py-2 rounded-lg flex items-center gap-2">
                    <i class="fa-solid fa-reply"></i> Balas via Email
                </a>

                <form action="{{ route('contact-messages.destroy', $contactMessage) }}" method="POST"
                      onsubmit="return confirm('Hapus pesan ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm font-semibold text-rose-600 hover:text-rose-800 transition-colors">
                        <i class="fa-solid fa-trash-can"></i> Hapus Pesan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection