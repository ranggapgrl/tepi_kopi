<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tepi Kopi</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/icon.svg') }}">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&display=swap" rel="stylesheet">
    <style>
        body{ font-family:'Plus Jakarta Sans', sans-serif; }
        .font-display{ font-family:'Fraunces', serif; }
        .btn-primary{ background:#412D15; transition:background-color .25s ease; }
        .btn-primary:hover{ background:#1F150C; }
    </style>
</head>
<body class="min-h-screen flex" style="background:#E1DCC9; color:#1F150C;">

    {{-- Bagian Kiri: Panel bermerek dengan kutipan pelanggan, bukan sekadar gambar+overlay --}}
    <div class="hidden lg:flex w-1/2 relative items-center justify-center overflow-hidden" style="background:#1F150C;">
        <img src="https://images.unsplash.com/photo-1497935586351-b67a49e012bf?auto=format&fit=crop&w=1000&q=80"
             alt="Coffee Background"
             class="absolute inset-0 w-full h-full object-cover opacity-35">
        <div class="absolute inset-0" style="background:linear-gradient(to top, #1F150C, rgba(31,21,12,0.5) 55%, rgba(31,21,12,0.75));"></div>

        <div class="relative z-20 px-12 xl:px-16 w-full max-w-lg">
            <a href="/" class="inline-flex items-center gap-2 text-white font-display text-xl font-semibold mb-16">
                <i class="fa-solid fa-mug-hot" style="color:#E1DCC9;"></i> TepiKopi.
            </a>

            <h2 class="font-display text-4xl xl:text-5xl font-semibold text-white mb-5 leading-tight">
                Selamat datang<br>di TepiKopi.
            </h2>
            <p class="text-white/60 text-base mb-12 max-w-sm">
                Masuk untuk melanjutkan belanja, lacak pesanan, dan simpan produk favoritmu.
            </p>

            {{-- Kartu testimoni mengambang — konsisten dengan pola bento di halaman lain --}}
            <div class="bg-white/10 backdrop-blur-sm border border-white/10 rounded-2xl p-6">
                <div class="flex gap-1 mb-3" style="color:#E1DCC9;">
                    <i class="fa-solid fa-star text-xs"></i><i class="fa-solid fa-star text-xs"></i><i class="fa-solid fa-star text-xs"></i><i class="fa-solid fa-star text-xs"></i><i class="fa-solid fa-star text-xs"></i>
                </div>
                <p class="text-white/80 text-sm leading-relaxed italic">"Mesin espresso yang saya beli sudah dipakai operasional harian hampir setahun, awet dan after-sales-nya responsif."</p>
                <div class="flex items-center gap-3 mt-4">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs text-white" style="background:#412D15;">B</div>
                    <p class="text-xs text-white/60 font-medium">Bagus P. — Pemilik Coffee Shop</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Bagian Kanan: Form Login --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 bg-white">
        <div class="w-full max-w-md">

            <div class="flex justify-between items-center mb-10 lg:hidden">
                <a href="/" class="font-display text-xl font-semibold flex items-center gap-2">
                    <i class="fa-solid fa-mug-hot" style="color:#412D15;"></i> TepiKopi.
                </a>
            </div>

            <a href="/" class="hidden lg:inline-flex items-center gap-1.5 text-xs font-bold mb-10 px-3 py-1.5 rounded-full transition-colors" style="background:#E1DCC9; color:#412D15;">
                <i class="fa-solid fa-arrow-left"></i> Beranda
            </a>

            <h1 class="font-display text-3xl font-semibold mb-2">Masuk ke Akun</h1>
            <p class="text-[#1F150C]/55 mb-8">Silakan masukkan email dan kata sandi Anda.</p>

            @if($errors->any())
                <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-3">
                    <i class="fa-solid fa-circle-exclamation text-lg"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="/login" class="space-y-5" x-data="{ showPassword: false }">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-bold mb-1.5">Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#1F150C]/35 group-focus-within:text-[#412D15] transition-colors">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <input type="email" name="email" id="email"
                               class="w-full pl-10 pr-4 py-3 bg-black/[0.02] border border-black/10 rounded-xl focus:ring-2 focus:ring-[#412D15]/20 focus:border-[#412D15]/40 focus:bg-white outline-none transition-all placeholder:text-[#1F150C]/30 font-medium"
                               placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-bold mb-1.5">Kata Sandi</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#1F150C]/35 group-focus-within:text-[#412D15] transition-colors">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input :type="showPassword ? 'text' : 'password'" name="password" id="password"
                               class="w-full pl-10 pr-11 py-3 bg-black/[0.02] border border-black/10 rounded-xl focus:ring-2 focus:ring-[#412D15]/20 focus:border-[#412D15]/40 focus:bg-white outline-none transition-all placeholder:text-[#1F150C]/30 font-medium"
                               placeholder="••••••••" required>
                        <button type="button" @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-[#1F150C]/35 hover:text-[#412D15] transition-colors">
                            <i class="fa-solid" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between text-sm pt-1">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded cursor-pointer" style="accent-color:#412D15;">
                        <span class="font-medium text-[#1F150C]/80 group-hover:text-[#412D15] transition-colors">Ingat saya</span>
                    </label>

                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="font-bold transition-colors" style="color:#412D15;">
                            Lupa sandi?
                        </a>
                    @endif
                </div>

                <button type="submit" class="w-full text-white font-bold py-3.5 rounded-xl transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5 flex items-center justify-center gap-2 mt-4 btn-primary">
                    <span>Masuk</span>
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                </button>
            </form>

            <div class="flex items-center gap-4 mt-8 text-[10px] text-[#1F150C]/35 uppercase tracking-wider">
                <i class="fa-solid fa-lock"></i> Data kamu terenkripsi dan aman
            </div>

            <p class="text-center mt-8 text-sm font-medium text-[#1F150C]/70">
                Belum memiliki akun?
                <a href="/register" class="font-bold ml-1 border-b border-transparent transition-colors hover:border-current" style="color:#412D15;">
                    Daftar Sekarang
                </a>
            </p>

        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>