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
                   class="text-sm font-semibold text-amber-800 hover:text-amber-950 transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i> Buka di Email Sendiri
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

        {{-- Balasan yang sudah pernah dikirim --}}
        @if($contactMessage->replied_at)
        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-5">
            <div class="flex items-center gap-2 mb-2">
                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                <p class="text-sm font-bold text-emerald-800">
                    Sudah dibalas {{ $contactMessage->replied_at->translatedFormat('d M Y, H:i') }}
                    @if($contactMessage->repliedBy) oleh {{ $contactMessage->repliedBy->name }} @endif
                </p>
            </div>
            <p class="text-sm text-emerald-900 whitespace-pre-line">{{ $contactMessage->reply_message }}</p>
        </div>
        @endif

        {{-- Form balas langsung dari admin panel --}}
        <div class="bg-white rounded-2xl border border-amber-100 shadow-sm p-6">
            <h3 class="font-bold text-amber-950 mb-1">
                {{ $contactMessage->replied_at ? 'Kirim Balasan Lagi' : 'Balas Pesan Ini' }}
            </h3>
            <p class="text-xs text-amber-700/70 mb-4">Balasan akan dikirim otomatis ke {{ $contactMessage->email }}.</p>

            <form action="{{ route('contact-messages.reply', $contactMessage) }}" method="POST" class="space-y-3">
                @csrf
                <textarea name="reply_message" rows="5" required maxlength="2000"
                          placeholder="Tulis balasan kamu di sini..."
                          class="w-full px-4 py-3 rounded-xl border {{ $errors->has('reply_message') ? 'border-rose-300 focus:ring-rose-200' : 'border-amber-100 focus:ring-amber-300' }} bg-amber-50/40 text-sm text-amber-950 outline-none focus:ring-2 focus:border-amber-300 transition-all resize-none">{{ old('reply_message') }}</textarea>
                @error('reply_message')
                    <p class="text-xs text-rose-600">{{ $message }}</p>
                @enderror

                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-800 hover:bg-amber-900 text-white text-sm font-bold rounded-lg transition-colors shadow-sm">
                    <i class="fa-solid fa-reply"></i> Kirim Balasan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection