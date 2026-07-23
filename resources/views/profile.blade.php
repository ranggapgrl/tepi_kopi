@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.app')

@section('title', 'Profil Saya')

@section('content')
<style>[x-cloak]{display:none!important;}</style>

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12" x-data="{ tab: 'akun' }">

    @if(session('success'))
    <div class="mb-6 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2" style="background:#f3f8f1; border:1px solid #cfe6c9; color:#2f5e29;">
        <i class="fa-solid fa-circle-check"></i>
        {{ session('success') }}
    </div>
    @endif

    @if(auth()->user()->role === 'admin')
    <a href="{{ route('admin.dashboard') }}"
    class="inline-flex items-center gap-2 text-sm font-bold text-[#412D15] hover:text-[#1F150C] transition-colors mb-6">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
    </a>
    @endif

    <div class="grid lg:grid-cols-[280px_1fr] gap-8 items-start">

        {{-- ============ SIDEBAR ============ --}}
        <aside class="bg-white border border-black/5 rounded-2xl shadow-sm p-6 lg:sticky lg:top-24">

            @php $avatarUrl = $user->avatar ? asset('storage/' . $user->avatar) : null; @endphp

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
                  x-data="{ preview: {{ Js::from($avatarUrl) }} }" id="avatar-form" class="flex flex-col items-center text-center pb-6 mb-6 border-b border-black/5">
                @csrf
                @method('PUT')
                <input type="hidden" name="name" value="{{ $user->name }}">
                <input type="hidden" name="email" value="{{ $user->email }}">

                <div class="relative">
                    <div class="w-24 h-24 rounded-full border-4 border-white shadow-md overflow-hidden flex items-center justify-center" style="background:#E1DCC9;">
                        <template x-if="preview"><img :src="preview" class="w-full h-full object-cover"></template>
                        <template x-if="!preview">
                            <span class="text-2xl font-black" style="color:#412D15;">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </template>
                    </div>
                    <label for="avatar" class="absolute bottom-0 right-0 w-8 h-8 text-white rounded-full flex items-center justify-center cursor-pointer shadow-md border-2 border-white transition-colors" style="background:#412D15;" onmouseover="this.style.background='#1F150C'" onmouseout="this.style.background='#412D15'">
                        <i class="fa-solid fa-camera text-[11px]"></i>
                    </label>
                    <input type="file" name="avatar" id="avatar" accept="image/*" class="hidden"
                           @change="preview = $event.target.files.length ? URL.createObjectURL($event.target.files[0]) : preview; $el.form.requestSubmit()">
                </div>

                <h1 class="text-base font-black text-[#1F150C] mt-4 truncate max-w-full">{{ $user->name }}</h1>
                <p class="text-xs text-[#1F150C]/45 truncate max-w-full">{{ $user->email }}</p>

                <div class="flex items-center gap-2 mt-3">
                    @if($user->role === 'admin')
                    <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wide px-2.5 py-1 rounded-full" style="background:#E1DCC9; color:#412D15;">
                        <i class="fa-solid fa-shield-halved"></i> Admin
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wide px-2.5 py-1 rounded-full" style="background:#f3f8f1; color:#2f5e29;">
                        <i class="fa-solid fa-mug-hot"></i> Member
                    </span>
                    @endif
                </div>
                <p class="text-[10px] text-[#1F150C]/35 mt-3">Sejak {{ $user->created_at->translatedFormat('M Y') }}</p>
            </form>

            {{-- Nav tabs --}}
            <nav class="space-y-1">
                <button @click="tab='akun'" :class="tab==='akun' ? 'text-white' : 'text-[#1F150C]/70 hover:bg-black/[0.03]'" :style="tab==='akun' ? 'background:#412D15' : ''"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-colors">
                    <i class="fa-solid fa-id-card w-4"></i> Informasi Akun
                </button>
                <button @click="tab='keamanan'" :class="tab==='keamanan' ? 'text-white' : 'text-[#1F150C]/70 hover:bg-black/[0.03]'" :style="tab==='keamanan' ? 'background:#412D15' : ''"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-colors">
                    <i class="fa-solid fa-lock w-4"></i> Keamanan
                </button>
                <a href="{{ route('orders.my') }}" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-[#1F150C]/70 hover:bg-black/[0.03] transition-colors">
                    <i class="fa-solid fa-receipt w-4"></i> Pesanan Saya
                </a>
                <hr class="my-2 border-black/5">
                <form method="POST" action="/logout">
                    @csrf
                    <button class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-red-600 hover:bg-red-50 transition-colors">
                        <i class="fa-solid fa-arrow-right-from-bracket w-4"></i> Keluar
                    </button>
                </form>
            </nav>
        </aside>

        {{-- ============ MAIN PANEL ============ --}}
        <div>
            {{-- Tab: Informasi Akun --}}
            <div x-show="tab==='akun'" x-cloak x-transition class="bg-white rounded-2xl border border-black/5 shadow-sm p-6 sm:p-8">
                <h2 class="font-display text-xl font-semibold text-[#1F150C] mb-1">Informasi Akun</h2>
                <p class="text-sm text-[#1F150C]/50 mb-6">Perbarui nama dan alamat email kamu.</p>

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5 max-w-lg">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block text-xs font-bold text-[#1F150C]/60 uppercase tracking-wide mb-2">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                               class="w-full px-4 py-3 rounded-xl border {{ $errors->has('name') ? 'border-rose-300 focus:ring-rose-200' : 'border-black/10 focus:ring-[#412D15]/20' }} bg-black/[0.02] text-sm text-[#1F150C] outline-none focus:ring-2 focus:border-[#412D15]/40 transition-all">
                        @error('name')<p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-bold text-[#1F150C]/60 uppercase tracking-wide mb-2">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                               class="w-full px-4 py-3 rounded-xl border {{ $errors->has('email') ? 'border-rose-300 focus:ring-rose-200' : 'border-black/10 focus:ring-[#412D15]/20' }} bg-black/[0.02] text-sm text-[#1F150C] outline-none focus:ring-2 focus:border-[#412D15]/40 transition-all">
                        @error('email')<p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit"
                            class="px-8 py-3 btn-primary font-bold rounded-xl text-sm shadow-md transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2 w-full sm:w-auto">
                        <i class="fa-solid fa-check"></i> Simpan Perubahan
                    </button>
                </form>
            </div>

            {{-- Tab: Keamanan --}}
            <div x-show="tab==='keamanan'" x-cloak x-transition class="bg-white rounded-2xl border border-black/5 shadow-sm p-6 sm:p-8">
                <h2 class="font-display text-xl font-semibold text-[#1F150C] mb-1">Keamanan</h2>
                <p class="text-sm text-[#1F150C]/50 mb-6">Perbarui password secara berkala untuk menjaga keamanan akun.</p>

                <form action="{{ route('profile.password') }}" method="POST" class="space-y-5 max-w-lg">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="block text-xs font-bold text-[#1F150C]/60 uppercase tracking-wide mb-2">Password Saat Ini</label>
                        <input type="password" name="current_password" id="current_password"
                               class="w-full px-4 py-3 rounded-xl border {{ $errors->has('current_password') ? 'border-rose-300 focus:ring-rose-200' : 'border-black/10 focus:ring-[#412D15]/20' }} bg-black/[0.02] text-sm text-[#1F150C] outline-none focus:ring-2 focus:border-[#412D15]/40 transition-all">
                        @error('current_password')<p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-bold text-[#1F150C]/60 uppercase tracking-wide mb-2">Password Baru</label>
                        <input type="password" name="password" id="password"
                               class="w-full px-4 py-3 rounded-xl border {{ $errors->has('password') ? 'border-rose-300 focus:ring-rose-200' : 'border-black/10 focus:ring-[#412D15]/20' }} bg-black/[0.02] text-sm text-[#1F150C] outline-none focus:ring-2 focus:border-[#412D15]/40 transition-all">
                        @error('password')<p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-[#1F150C]/60 uppercase tracking-wide mb-2">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="w-full px-4 py-3 rounded-xl border border-black/10 bg-black/[0.02] text-sm text-[#1F150C] outline-none focus:ring-2 focus:ring-[#412D15]/20 focus:border-[#412D15]/40 transition-all">
                    </div>

                    <button type="submit"
                            class="px-8 py-3 btn-primary font-bold rounded-xl text-sm shadow-md transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2 w-full sm:w-auto">
                        <i class="fa-solid fa-lock"></i> Ubah Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection