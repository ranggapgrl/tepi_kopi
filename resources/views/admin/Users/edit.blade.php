@extends('layouts.admin')

@section('title', 'Edit Akun - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col md:flex-row gap-8">

    @include('admin.partials.sidebar')

    <div class="flex-grow space-y-6">

        <div class="flex items-center gap-3">
            <a href="{{ route('users.index') }}" class="w-9 h-9 flex items-center justify-center rounded-lg border border-amber-100 text-amber-700 hover:bg-amber-50 transition-colors">
                <i class="fa-solid fa-arrow-left text-xs"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-amber-950">Edit Akun</h1>
                <p class="text-amber-700/80 text-sm">Perbarui data akun {{ $user->name }}.</p>
            </div>
        </div>

        @if($user->id === auth()->id())
        <div class="bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2 max-w-xl">
            <i class="fa-solid fa-circle-info"></i>
            Ini akunmu sendiri. Role tidak bisa diubah dari sini untuk mencegah kamu terkunci dari panel admin.
        </div>
        @endif

        <div class="bg-white rounded-2xl border border-amber-100 shadow-sm p-6 sm:p-8 max-w-xl">
            <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-xs font-bold text-amber-900 uppercase tracking-wide mb-2">
                        Nama Lengkap
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                        class="w-full px-4 py-3 rounded-xl border {{ $errors->has('name') ? 'border-rose-300 focus:ring-rose-200' : 'border-amber-100 focus:ring-amber-300' }} bg-amber-50/40 text-sm text-amber-950 outline-none focus:ring-2 focus:border-amber-300 transition-all">
                    @error('name')
                        <p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-amber-900 uppercase tracking-wide mb-2">
                        Email
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                        class="w-full px-4 py-3 rounded-xl border {{ $errors->has('email') ? 'border-rose-300 focus:ring-rose-200' : 'border-amber-100 focus:ring-amber-300' }} bg-amber-50/40 text-sm text-amber-950 outline-none focus:ring-2 focus:border-amber-300 transition-all">
                    @error('email')
                        <p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="role" class="block text-xs font-bold text-amber-900 uppercase tracking-wide mb-2">
                        Role
                    </label>
                    <select name="role" id="role" {{ $user->id === auth()->id() ? 'disabled' : '' }}
                        class="w-full px-4 py-3 rounded-xl border {{ $errors->has('role') ? 'border-rose-300 focus:ring-rose-200' : 'border-amber-100 focus:ring-amber-300' }} bg-amber-50/40 text-sm text-amber-950 outline-none focus:ring-2 focus:border-amber-300 transition-all disabled:opacity-60 disabled:cursor-not-allowed">
                        <option value="customer" {{ old('role', $user->role) === 'customer' ? 'selected' : '' }}>Customer</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    {{-- Kalau select di-disable, browser tidak mengirim value-nya. Kirim ulang via hidden input. --}}
                    @if($user->id === auth()->id())
                        <input type="hidden" name="role" value="{{ $user->role }}">
                    @endif
                    @error('role')
                        <p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2 border-t border-amber-50">
                    <label for="password" class="block text-xs font-bold text-amber-900 uppercase tracking-wide mb-2 mt-4">
                        Password Baru <span class="text-gray-400 font-normal normal-case">(opsional, kosongkan jika tidak diubah)</span>
                    </label>
                    <input type="password" name="password" id="password"
                        placeholder="Minimal 8 karakter"
                        class="w-full px-4 py-3 rounded-xl border {{ $errors->has('password') ? 'border-rose-300 focus:ring-rose-200' : 'border-amber-100 focus:ring-amber-300' }} bg-amber-50/40 text-sm text-amber-950 outline-none focus:ring-2 focus:border-amber-300 transition-all">
                    @error('password')
                        <p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-amber-900 uppercase tracking-wide mb-2">
                        Konfirmasi Password Baru
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        placeholder="Ulangi password baru"
                        class="w-full px-4 py-3 rounded-xl border border-amber-100 bg-amber-50/40 text-sm text-amber-950 outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300 transition-all">
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-2">
                    <button type="submit"
                            class="flex-1 sm:flex-none px-8 py-3 bg-amber-800 hover:bg-amber-900 text-white font-bold rounded-xl text-sm shadow-md transition-colors flex items-center justify-center gap-2">
                        <i class="fa-solid fa-check"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('users.index') }}"
                    class="flex-1 sm:flex-none px-8 py-3 border border-amber-200 text-amber-800 hover:bg-amber-50 font-bold rounded-xl text-sm transition-colors text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection