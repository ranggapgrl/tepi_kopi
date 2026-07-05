<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Tepi Kopi</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/icon.svg') }}">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&display=swap" rel="stylesheet">
    <style>
        body{ font-family:'Plus Jakarta Sans', sans-serif; }
        .font-display{ font-family:'Fraunces', serif; }
        .btn-primary{
            background:#412D15;
            transition:background-color .25s ease, transform .25s ease, box-shadow .25s ease;
            position:relative;
            overflow:hidden;
            isolation:isolate;
        }
        .btn-primary:hover{ background:#1F150C; }
        .btn-primary:active{ transform:translateY(0) scale(0.98); }

        /* Tombol: kilau halus yang menyapu saat hover */
        .btn-primary::before{
            content:'';
            position:absolute;
            inset:0;
            z-index:-1;
            background:linear-gradient(115deg, transparent 20%, rgba(255,255,255,0.16) 40%, transparent 60%);
            transform:translateX(-120%);
            transition:transform .7s ease;
        }
        .btn-primary:hover::before{ transform:translateX(120%); }
        .btn-primary:hover i{ transform:translateX(3px) rotate(0deg); }
        .btn-primary i{ transition:transform .25s ease; }

        /* Urutan masuk form: elemen muncul bertahap dari bawah */
        .reveal{
            opacity:0;
            transform:translateY(14px);
            animation:reveal .6s cubic-bezier(.22,1,.36,1) forwards;
        }
        @keyframes reveal{
            to{ opacity:1; transform:translateY(0); }
        }
        .delay-1{ animation-delay:.04s; }
        .delay-2{ animation-delay:.1s; }
        .delay-3{ animation-delay:.16s; }
        .delay-4{ animation-delay:.22s; }
        .delay-5{ animation-delay:.28s; }
        .delay-6{ animation-delay:.34s; }
        .delay-7{ animation-delay:.4s; }
        .delay-8{ animation-delay:.46s; }

        /* Input: garis bawah tumbuh dari kiri saat fokus + lift halus */
        .input-wrap input{ transition:all .2s ease, transform .2s ease; }
        .input-wrap:focus-within input{ transform:translateY(-1px); }
        .input-underline{
            position:absolute;
            left:0; right:0; bottom:0;
            height:2px;
            background:#412D15;
            border-radius:2px;
            transform:scaleX(0);
            transition:transform .3s cubic-bezier(.22,1,.36,1);
        }
        .input-wrap:focus-within .input-underline{ transform:scaleX(1); }

        /* Ikon mata: sedikit membesar saat hover */
        .icon-nudge{ transition:transform .2s ease; }
        .icon-nudge:hover{ transform:scale(1.15); }

        /* Pesan error: guncangan singkat agar terasa perhatian */
        .error-shake{ animation:shake .45s ease; }
        @keyframes shake{
            10%, 90% { transform:translateX(-1px); }
            20%, 80% { transform:translateX(2px); }
            30%, 50%, 70% { transform:translateX(-4px); }
            40%, 60% { transform:translateX(4px); }
        }

        /* Checklist kekuatan sandi: setiap item "pop" saat berubah jadi valid */
        .strength-item i{ transition:transform .25s cubic-bezier(.34,1.56,.64,1), color .2s ease; }
        .strength-item.is-valid i{ transform:scale(1.15); }

        @media (prefers-reduced-motion: reduce){
            .reveal{ animation:none !important; opacity:1; transform:none; }
            .error-shake{ animation:none !important; }
        }
    </style>
</head>
<body class="min-h-screen flex lg:flex-row-reverse" style="background:#E1DCC9; color:#1F150C;">

    {{-- Bagian Kanan: Panel manfaat member, bukan sekadar gambar+overlay --}}
    <div class="hidden lg:flex w-1/2 relative items-center justify-center overflow-hidden" style="background:#1F150C;">
        <img src="https://images.unsplash.com/photo-1559056199-641a0ac8b55e?auto=format&fit=crop&w=1000&q=80"
             alt="Coffee Beans Background"
             class="absolute inset-0 w-full h-full object-cover opacity-30">
        <div class="absolute inset-0" style="background:linear-gradient(to top, #1F150C, rgba(31,21,12,0.55) 55%, rgba(31,21,12,0.8));"></div>

        <div class="relative z-20 px-12 xl:px-16 w-full max-w-lg">
            <a href="/" class="inline-flex items-center gap-2 text-white font-display text-xl font-semibold mb-14">
                <i class="fa-solid fa-mug-hot" style="color:#E1DCC9;"></i> TepiKopi.
            </a>

            <h2 class="font-display text-4xl xl:text-5xl font-semibold text-white mb-5 leading-tight">
                Bergabunglah<br>bersama kami.
            </h2>
            <p class="text-white/60 text-base mb-10 max-w-sm">
                Jadilah bagian dari komunitas pecinta kopi dan dapatkan akses ke koleksi premium kami.
            </p>

            {{-- Manfaat member sebagai checklist, bukan paragraf --}}
            <div class="space-y-4">
                @foreach([
                    ['icon' => 'fa-truck-fast', 'text' => 'Gratis ongkir untuk pembelian pertama'],
                    ['icon' => 'fa-tags', 'text' => 'Akses promo & harga khusus member'],
                    ['icon' => 'fa-receipt', 'text' => 'Lacak riwayat pesanan kapan saja'],
                ] as $benefit)
                <div class="flex items-center gap-3.5">
                    <div class="w-9 h-9 shrink-0 rounded-full flex items-center justify-center" style="background:rgba(225,220,201,0.15); color:#E1DCC9;">
                        <i class="fa-solid {{ $benefit['icon'] }} text-xs"></i>
                    </div>
                    <p class="text-white/75 text-sm font-medium">{{ $benefit['text'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Bagian Kiri: Form Register --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 bg-white overflow-y-auto">
        <div class="w-full max-w-md py-8">

            <div class="reveal flex justify-between items-center mb-8">
                <a href="/" class="font-display text-xl font-semibold flex items-center gap-2">
                    <i class="fa-solid fa-mug-hot" style="color:#412D15;"></i> TepiKopi.
                </a>
                <a href="/" class="text-xs font-bold px-3 py-1.5 rounded-full transition-all hover:-translate-x-0.5" style="background:#E1DCC9; color:#412D15;">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Beranda
                </a>
            </div>

            <h1 class="reveal delay-1 font-display text-3xl font-semibold mb-2">Buat Akun Baru</h1>
            <p class="reveal delay-2 text-[#1F150C]/55 mb-8">Lengkapi data di bawah ini untuk mendaftar.</p>

            @if($errors->any())
                <div class="error-shake mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm font-bold flex items-start gap-3">
                    <i class="fa-solid fa-circle-exclamation text-lg mt-0.5"></i>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="/register" class="space-y-4"
                  x-data="{
                    showPassword: false,
                    showConfirm: false,
                    password: '',
                    get hasLength() { return this.password.length >= 8; },
                    get hasNumber() { return /[0-9]/.test(this.password); },
                    get hasUpper() { return /[A-Z]/.test(this.password); }
                  }">
                @csrf

                <div class="reveal delay-3">
                    <label for="name" class="block text-sm font-bold mb-1.5">Nama Lengkap</label>
                    <div class="input-wrap relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#1F150C]/35 group-focus-within:text-[#412D15] transition-colors">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <input type="text" name="name" id="name"
                               class="w-full pl-10 pr-4 py-3 bg-black/[0.02] border border-black/10 rounded-xl focus:ring-2 focus:ring-[#412D15]/20 focus:border-[#412D15]/40 focus:bg-white outline-none transition-all placeholder:text-[#1F150C]/30 font-medium"
                               placeholder="Nama lengkap Anda" value="{{ old('name') }}" required autofocus>
                        <span class="input-underline"></span>
                    </div>
                </div>

                <div class="reveal delay-4">
                    <label for="email" class="block text-sm font-bold mb-1.5">Email</label>
                    <div class="input-wrap relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#1F150C]/35 group-focus-within:text-[#412D15] transition-colors">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <input type="email" name="email" id="email"
                               class="w-full pl-10 pr-4 py-3 bg-black/[0.02] border border-black/10 rounded-xl focus:ring-2 focus:ring-[#412D15]/20 focus:border-[#412D15]/40 focus:bg-white outline-none transition-all placeholder:text-[#1F150C]/30 font-medium"
                               placeholder="nama@email.com" value="{{ old('email') }}" required>
                        <span class="input-underline"></span>
                    </div>
                </div>

                <div class="reveal delay-5">
                    <label for="password" class="block text-sm font-bold mb-1.5">Kata Sandi</label>
                    <div class="input-wrap relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#1F150C]/35 group-focus-within:text-[#412D15] transition-colors">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input :type="showPassword ? 'text' : 'password'" name="password" id="password" x-model="password"
                               class="w-full pl-10 pr-11 py-3 bg-black/[0.02] border border-black/10 rounded-xl focus:ring-2 focus:ring-[#412D15]/20 focus:border-[#412D15]/40 focus:bg-white outline-none transition-all placeholder:text-[#1F150C]/30 font-medium"
                               placeholder="Minimal 8 karakter" required>
                        <button type="button" @click="showPassword = !showPassword"
                                class="icon-nudge absolute inset-y-0 right-0 pr-3.5 flex items-center text-[#1F150C]/35 hover:text-[#412D15] transition-colors">
                            <i class="fa-solid" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                        <span class="input-underline"></span>
                    </div>

                    {{-- Checklist kekuatan sandi live — fitur baru yang benar-benar fungsional --}}
                    <div class="flex flex-wrap gap-x-4 gap-y-1 mt-2.5 text-xs" x-show="password.length > 0" x-cloak x-transition>
                        <span class="strength-item flex items-center gap-1.5" :class="hasLength ? 'text-emerald-600 is-valid' : 'text-[#1F150C]/35'">
                            <i class="fa-solid" :class="hasLength ? 'fa-circle-check' : 'fa-circle'"></i> 8+ karakter
                        </span>
                        <span class="strength-item flex items-center gap-1.5" :class="hasUpper ? 'text-emerald-600 is-valid' : 'text-[#1F150C]/35'">
                            <i class="fa-solid" :class="hasUpper ? 'fa-circle-check' : 'fa-circle'"></i> Huruf besar
                        </span>
                        <span class="strength-item flex items-center gap-1.5" :class="hasNumber ? 'text-emerald-600 is-valid' : 'text-[#1F150C]/35'">
                            <i class="fa-solid" :class="hasNumber ? 'fa-circle-check' : 'fa-circle'"></i> Angka
                        </span>
                    </div>
                </div>

                <div class="reveal delay-6">
                    <label for="password_confirmation" class="block text-sm font-bold mb-1.5">Konfirmasi Kata Sandi</label>
                    <div class="input-wrap relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#1F150C]/35 group-focus-within:text-[#412D15] transition-colors">
                            <i class="fa-solid fa-check-double"></i>
                        </div>
                        <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" id="password_confirmation"
                            class="w-full pl-10 pr-11 py-3 bg-black/[0.02] border border-black/10 rounded-xl focus:ring-2 focus:ring-[#412D15]/20 focus:border-[#412D15]/40 focus:bg-white outline-none transition-all placeholder:text-[#1F150C]/30 font-medium"
                            placeholder="Ulangi kata sandi" required>
                        <button type="button" @click="showConfirm = !showConfirm"
                                class="icon-nudge absolute inset-y-0 right-0 pr-3.5 flex items-center text-[#1F150C]/35 hover:text-[#412D15] transition-colors">
                            <i class="fa-solid" :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                        <span class="input-underline"></span>
                    </div>
                </div>

                <button type="submit" class="reveal delay-7 w-full text-white font-bold py-3.5 rounded-xl transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5 flex items-center justify-center gap-2 mt-6 btn-primary">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>Daftar Sekarang</span>
                </button>
            </form>

            <p class="reveal delay-8 text-center mt-8 text-sm font-medium text-[#1F150C]/70">
                Sudah memiliki akun?
                <a href="/login" class="font-bold ml-1 border-b border-transparent transition-colors hover:border-current" style="color:#412D15;">
                    Masuk di sini
                </a>
            </p>

        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>