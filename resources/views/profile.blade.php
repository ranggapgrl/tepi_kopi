@extends('layouts.app')

@section('title', 'Profil Saya - Tepi Kopi')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">

    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-amber-950 tracking-tight mb-2">Profil Saya</h1>
        <p class="text-amber-700/80 text-sm">Kelola informasi akun dan keamanan kamu.</p>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
        <i class="fa-solid fa-circle-check"></i>
        {{ session('success') }}
    </div>
    @endif

    <div class="grid gap-6">

        {{-- Kartu identitas --}}
        <div class="bg-white rounded-2xl border border-amber-100 shadow-sm p-6 sm:p-8 flex items-center gap-4">
            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center text-2xl sm:text-3xl font-black flex-shrink-0">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="min-w-0">
                <h2 class="text-lg sm:text-xl font-bold text-amber-950 truncate">{{ $user->name }}</h2>
                <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
                @if($user->role === 'admin')
                <span class="inline-flex items-center gap-1 mt-2 bg-amber-100 text-amber-800 text-[11px] font-bold uppercase tracking-wide px-2.5 py-1 rounded-md">
                    <i class="fa-solid fa-shield-halved"></i> Admin
                </span>
                @endif
            </div>
        </div>

        {{-- Form update data diri --}}
        <div class="bg-white rounded-2xl border border-amber-100 shadow-sm p-6 sm:p-8">
            <h3 class="font-bold text-amber-950 mb-5">Informasi Akun</h3>

            <form action="{{ route('profile.update') }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-xs font-bold text-amber-900 uppercase tracking-wide mb-2">Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                           class="w-full px-4 py-3 rounded-xl border {{ $errors->has('name') ? 'border-rose-300 focus:ring-rose-200' : 'border-amber-100 focus:ring-amber-300' }} bg-amber-50/40 text-sm text-amber-950 outline-none focus:ring-2 focus:border-amber-300 transition-all">
                    @error('name')<p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-amber-900 uppercase tracking-wide mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                           class="w-full px-4 py-3 rounded-xl border {{ $errors->has('email') ? 'border-rose-300 focus:ring-rose-200' : 'border-amber-100 focus:ring-amber-300' }} bg-amber-50/40 text-sm text-amber-950 outline-none focus:ring-2 focus:border-amber-300 transition-all">
                    @error('email')<p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>@enderror
                </div>

                <button type="submit"
                        class="px-8 py-3 bg-amber-800 hover:bg-amber-900 text-white font-bold rounded-xl text-sm shadow-md transition-colors flex items-center justify-center gap-2 w-full sm:w-auto">
                    <i class="fa-solid fa-check"></i> Simpan Perubahan
                </button>
            </form>
        </div>

        {{-- Form ganti password --}}
        <div class="bg-white rounded-2xl border border-amber-100 shadow-sm p-6 sm:p-8">
            <h3 class="font-bold text-amber-950 mb-5">Ganti Password</h3>

            <form action="{{ route('profile.password') }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-xs font-bold text-amber-900 uppercase tracking-wide mb-2">Password Saat Ini</label>
                    <input type="password" name="current_password" id="current_password"
                           class="w-full px-4 py-3 rounded-xl border {{ $errors->has('current_password') ? 'border-rose-300 focus:ring-rose-200' : 'border-amber-100 focus:ring-amber-300' }} bg-amber-50/40 text-sm text-amber-950 outline-none focus:ring-2 focus:border-amber-300 transition-all">
                    @error('current_password')<p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>@enderror
                </div>

                <div class="grid sm:grid-cols-2 gap-5">
                    <div>
                        <label for="password" class="block text-xs font-bold text-amber-900 uppercase tracking-wide mb-2">Password Baru</label>
                        <input type="password" name="password" id="password"
                               class="w-full px-4 py-3 rounded-xl border {{ $errors->has('password') ? 'border-rose-300 focus:ring-rose-200' : 'border-amber-100 focus:ring-amber-300' }} bg-amber-50/40 text-sm text-amber-950 outline-none focus:ring-2 focus:border-amber-300 transition-all">
                        @error('password')<p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-amber-900 uppercase tracking-wide mb-2">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="w-full px-4 py-3 rounded-xl border border-amber-100 bg-amber-50/40 text-sm text-amber-950 outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300 transition-all">
                    </div>
                </div>

                <button type="submit"
                        class="px-8 py-3 bg-amber-800 hover:bg-amber-900 text-white font-bold rounded-xl text-sm shadow-md transition-colors flex items-center justify-center gap-2 w-full sm:w-auto">
                    <i class="fa-solid fa-lock"></i> Ubah Password
                </button>
            </form>
        </div>
    </div>
</div>
@endsection