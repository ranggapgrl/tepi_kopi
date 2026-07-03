@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.app')

@section('title', 'Profil Saya - Tepi Kopi')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">

    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
        <i class="fa-solid fa-circle-check"></i>
        {{ session('success') }}
    </div>
    @endif

    {{-- Header profil dengan cover gradient --}}
    <div class="relative rounded-3xl overflow-hidden mb-8 shadow-sm">
        <div class="h-28 sm:h-36 bg-gradient-to-r from-amber-700 via-amber-800 to-amber-950"></div>

        @php
            $avatarUrl = $user->avatar ? asset('storage/' . $user->avatar) : null;
        @endphp

        <div class="bg-white px-6 sm:px-8 pb-6 sm:pb-8 -mt-14 sm:-mt-16">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
                  x-data="{ preview: {{ Js::from($avatarUrl) }} }"
                  id="avatar-form">
                @csrf
                @method('PUT')

                {{-- TAMBAHAN: Field hidden agar validasi Controller tidak gagal saat ganti avatar --}}
                <input type="hidden" name="name" value="{{ $user->name }}">
                <input type="hidden" name="email" value="{{ $user->email }}">

                <div class="flex flex-col sm:flex-row items-center sm:items-end gap-4 sm:gap-6">
                    {{-- Avatar --}}
                    <div class="relative flex-shrink-0">
                        <div class="w-28 h-28 sm:w-32 sm:h-32 rounded-full border-4 border-white shadow-lg overflow-hidden bg-amber-100 flex items-center justify-center">
                            <template x-if="preview">
                                <img :src="preview" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!preview">
                                <span class="text-3xl sm:text-4xl font-black text-amber-700">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </span>
                            </template>
                        </div>

                        <label for="avatar"
                               class="absolute bottom-1 right-1 w-9 h-9 bg-amber-800 hover:bg-amber-900 text-white rounded-full flex items-center justify-center cursor-pointer shadow-md border-2 border-white transition-colors">
                            <i class="fa-solid fa-camera text-xs"></i>
                        </label>
                        <input type="file" name="avatar" id="avatar" accept="image/*" class="hidden"
                               @change="preview = $event.target.files.length ? URL.createObjectURL($event.target.files[0]) : preview; $el.form.requestSubmit()">
                    </div>

                    {{-- Nama & badge --}}
                    <div class="text-center sm:text-left pb-1 sm:pb-2 min-w-0">
                        <h1 class="text-xl sm:text-2xl font-black text-amber-950 truncate">{{ $user->name }}</h1>
                        <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
                        <div class="flex items-center justify-center sm:justify-start gap-2 mt-2">
                            @if($user->role === 'admin')
                            <span class="inline-flex items-center gap-1 bg-amber-100 text-amber-800 text-[11px] font-bold uppercase tracking-wide px-2.5 py-1 rounded-full">
                                <i class="fa-solid fa-shield-halved"></i> Admin
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-700 text-[11px] font-bold uppercase tracking-wide px-2.5 py-1 rounded-full">
                                <i class="fa-solid fa-mug-hot"></i> Member
                            </span>
                            @endif
                            <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-600 text-[11px] font-medium px-2.5 py-1 rounded-full">
                                <i class="fa-regular fa-calendar"></i> Sejak {{ $user->created_at->translatedFormat('M Y') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Field name/email disembunyikan di form ini, dipakai form terpisah di bawah --}}
            </form>
            <p class="text-[11px] text-gray-400 mt-3 text-center sm:text-left">
                <i class="fa-solid fa-circle-info"></i> Klik ikon kamera untuk ganti foto profil — otomatis tersimpan.
            </p>
        </div>
    </div>

    <div class="grid gap-6">

        {{-- Form update data diri --}}
        <div class="bg-white rounded-2xl border border-amber-100 shadow-sm p-6 sm:p-8">
            <h3 class="font-bold text-amber-950 mb-5 flex items-center gap-2">
                <i class="fa-solid fa-id-card text-amber-600"></i> Informasi Akun
            </h3>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
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
                        class="px-8 py-3 bg-amber-800 hover:bg-amber-900 text-white font-bold rounded-xl text-sm shadow-md transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2 w-full sm:w-auto">
                    <i class="fa-solid fa-check"></i> Simpan Perubahan
                </button>
            </form>
        </div>

        {{-- Form ganti password --}}
        <div class="bg-white rounded-2xl border border-amber-100 shadow-sm p-6 sm:p-8">
            <h3 class="font-bold text-amber-950 mb-5 flex items-center gap-2">
                <i class="fa-solid fa-lock text-amber-600"></i> Ganti Password
            </h3>

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
                        class="px-8 py-3 bg-amber-800 hover:bg-amber-900 text-white font-bold rounded-xl text-sm shadow-md transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2 w-full sm:w-auto">
                    <i class="fa-solid fa-lock"></i> Ubah Password
                </button>
            </form>
        </div>
    </div>
</div>
@endsection