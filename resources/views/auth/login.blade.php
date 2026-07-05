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

        /* Latar foto kiri: efek ken-burns lambat agar panel terasa hidup */
        .kb-bg{ animation:kenburns 22s ease-in-out infinite alternate; }
        @keyframes kenburns{
            0%   { transform:scale(1) translate(0,0); }
            100% { transform:scale(1.12) translate(-1.5%, -1%); }
        }

        /* Urutan masuk halaman: elemen muncul bertahap dari bawah */
        .reveal{
            opacity:0;
            transform:translateY(16px);
            animation:reveal .7s cubic-bezier(.22,1,.36,1) forwards;
        }
        @keyframes reveal{
            to{ opacity:1; transform:translateY(0); }
        }
        .delay-1{ animation-delay:.05s; }
        .delay-2{ animation-delay:.15s; }
        .delay-3{ animation-delay:.25s; }
        .delay-4{ animation-delay:.35s; }
        .delay-5{ animation-delay:.45s; }
        .delay-6{ animation-delay:.55s; }
        .delay-7{ animation-delay:.65s; }

        /* Kartu testimoni: sedikit mengambang */
        .float-card{ animation:floaty 5s ease-in-out infinite; }
        @keyframes floaty{
            0%, 100% { transform:translateY(0); }
            50% { transform:translateY(-6px); }
        }

        /* Bola cahaya lembut di panel kiri untuk kedalaman & suasana */
        .glow-orb{
            position:absolute;
            border-radius:9999px;
            filter:blur(60px);
            pointer-events:none;
            animation:orbFloat 14s ease-in-out infinite;
        }
        @keyframes orbFloat{
            0%, 100% { transform:translate(0,0) scale(1); }
            50% { transform:translate(20px,-24px) scale(1.08); }
        }

        /* Tekstur grain halus agar panel gelap tidak terasa datar */
        .grain-overlay{
            position:absolute; inset:0;
            opacity:.5;
            mix-blend-mode:overlay;
            pointer-events:none;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='2' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.35'/%3E%3C/svg%3E");
        }

        /* Uap kopi mengepul dari ikon mug — sentuhan tanda tangan brand */
        .steam{
            position:absolute;
            width:2px;
            border-radius:2px;
            background:linear-gradient(to top, rgba(225,220,201,.7), transparent);
            transform-origin:bottom center;
            animation:steamRise 3s ease-in-out infinite;
        }
        @keyframes steamRise{
            0%   { height:0px; opacity:0; transform:translateY(0) translateX(0) scaleX(1); }
            30%  { opacity:.8; }
            100% { height:14px; opacity:0; transform:translateY(-16px) translateX(3px) scaleX(1.4); }
        }

        /* Badge kepercayaan kecil */
        .trust-badge{
            transition:transform .2s ease, background-color .2s ease;
        }
        .trust-badge:hover{ transform:translateY(-2px); }

        /* Tanda kutip besar dekoratif pada kartu testimoni */
        .quote-mark{
            font-family:'Fraunces', serif;
            line-height:1;
        }

        /* Input: lift halus saat fokus + garis bawah tumbuh dari tengah */
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

        /* Pesan error: guncangan singkat agar terasa perhatian */
        .error-shake{ animation:shake .45s ease; }
        @keyframes shake{
            10%, 90% { transform:translateX(-1px); }
            20%, 80% { transform:translateX(2px); }
            30%, 50%, 70% { transform:translateX(-4px); }
            40%, 60% { transform:translateX(4px); }
        }

        /* Ikon: sedikit gerak saat interaksi */
        .icon-nudge{ transition:transform .2s ease; }
        .icon-nudge:hover{ transform:scale(1.15); }
        .btn-primary:hover i{ transform:translateX(3px); }
        .btn-primary i{ transition:transform .25s ease; }

        @media (prefers-reduced-motion: reduce){
            .kb-bg, .reveal, .float-card, .error-shake, .glow-orb, .steam{ animation:none !important; }
            .steam{ display:none; }
            .reveal{ opacity:1; transform:none; }
        }
    </style>
</head>
<body class="min-h-screen flex" style="background:#E1DCC9; color:#1F150C;">

    {{-- Bagian Kiri: Panel bermerek dengan kutipan pelanggan, bukan sekadar gambar+overlay --}}
    <div class="hidden lg:flex w-1/2 relative items-center justify-center overflow-hidden" style="background:#1F150C;">
        <img src="https://images.unsplash.com/photo-1497935586351-b67a49e012bf?auto=format&fit=crop&w=1000&q=80"
             alt="Coffee Background"
             class="kb-bg absolute inset-0 w-full h-full object-cover opacity-35">
        <div class="absolute inset-0" style="background:linear-gradient(to top, #1F150C, rgba(31,21,12,0.5) 55%, rgba(31,21,12,0.75));"></div>

        {{-- Bola cahaya lembut untuk kedalaman --}}
        <div class="glow-orb" style="width:340px; height:340px; top:-60px; right:-80px; background:radial-gradient(circle, rgba(226,168,90,0.28), transparent 70%);"></div>
        <div class="glow-orb" style="width:280px; height:280px; bottom:-40px; left:-60px; background:radial-gradient(circle, rgba(65,45,21,0.5), transparent 70%); animation-delay:-6s;"></div>
        <div class="grain-overlay"></div>

        <div class="relative z-20 px-12 xl:px-16 w-full max-w-lg">
            <a href="/" class="reveal delay-1 relative inline-flex items-center gap-2 text-white font-display text-xl font-semibold mb-16">
                <span class="relative inline-flex">
                    <i class="fa-solid fa-mug-hot" style="color:#E1DCC9;"></i>
                    <span class="steam" style="left:2px; top:-6px;"></span>
                    <span class="steam" style="left:7px; top:-6px; animation-delay:.9s;"></span>
                    <span class="steam" style="left:12px; top:-6px; animation-delay:1.8s;"></span>
                </span>
                TepiKopi.
            </a>

            <h2 class="reveal delay-2 font-display text-4xl xl:text-5xl font-semibold text-white mb-5 leading-tight">
                Selamat datang<br>di TepiKopi.
            </h2>
            <p class="reveal delay-3 text-white/60 text-base mb-8 max-w-sm">
                Masuk untuk melanjutkan belanja, lacak pesanan, dan simpan produk favoritmu.
            </p>

            {{-- Badge kepercayaan --}}
            <div class="reveal delay-4 flex items-center gap-3 mb-8">
                <div class="trust-badge flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold text-white" style="background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.16);">
                    <i class="fa-solid fa-star" style="color:#E2A85A;"></i> 4.9/5 rating
                </div>
                <div class="trust-badge flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold text-white" style="background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.16);">
                    <i class="fa-solid fa-mug-saucer" style="color:#E2A85A;"></i> 500+ pelanggan
                </div>
            </div>

            {{-- Kartu testimoni mengambang — konsisten dengan pola bento di halaman lain --}}
            <div class="reveal delay-5 float-card relative overflow-hidden rounded-2xl p-6 transition-shadow hover:shadow-2xl"
                 style="background:rgba(255,255,255,0.14); border:1px solid rgba(255,255,255,0.22); box-shadow:0 20px 45px -20px rgba(0,0,0,0.55);">
                <span class="quote-mark absolute -top-3 right-4 text-6xl select-none" style="color:rgba(255,255,255,0.12);">"</span>
                <div class="flex gap-1 mb-3" style="color:#E2A85A;">
                    <i class="fa-solid fa-star text-xs"></i><i class="fa-solid fa-star text-xs"></i><i class="fa-solid fa-star text-xs"></i><i class="fa-solid fa-star text-xs"></i><i class="fa-solid fa-star text-xs"></i>
                </div>
                <p class="relative text-white/90 text-sm leading-relaxed italic">"Mesin espresso yang saya beli sudah dipakai operasional harian hampir setahun, awet dan after-sales-nya responsif."</p>
                <div class="flex items-center gap-3 mt-4">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs text-white" style="background:#412D15;">B</div>
                    <p class="text-xs text-white/70 font-medium">Bagus P. — Pemilik Coffee Shop</p>
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

            <a href="/" class="reveal hidden lg:inline-flex items-center gap-1.5 text-xs font-bold mb-10 px-3 py-1.5 rounded-full transition-all hover:-translate-x-0.5" style="background:#E1DCC9; color:#412D15;">
                <i class="fa-solid fa-arrow-left"></i> Beranda
            </a>

            <h1 class="reveal delay-1 font-display text-3xl font-semibold mb-2">Masuk ke Akun</h1>
            <p class="reveal delay-2 text-[#1F150C]/55 mb-8">Silakan masukkan email dan kata sandi Anda.</p>

            @if($errors->any())
                <div class="error-shake mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-3">
                    <i class="fa-solid fa-circle-exclamation text-lg"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="/login" class="space-y-5" x-data="{ showPassword: false }">
                @csrf

                <div class="reveal delay-3">
                    <label for="email" class="block text-sm font-bold mb-1.5">Email</label>
                    <div class="input-wrap relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#1F150C]/35 group-focus-within:text-[#412D15] transition-colors">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <input type="email" name="email" id="email"
                               class="w-full pl-10 pr-4 py-3 bg-black/[0.02] border border-black/10 rounded-xl focus:ring-2 focus:ring-[#412D15]/20 focus:border-[#412D15]/40 focus:bg-white outline-none transition-all placeholder:text-[#1F150C]/30 font-medium"
                               placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
                        <span class="input-underline"></span>
                    </div>
                </div>

                <div class="reveal delay-4">
                    <label for="password" class="block text-sm font-bold mb-1.5">Kata Sandi</label>
                    <div class="input-wrap relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#1F150C]/35 group-focus-within:text-[#412D15] transition-colors">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input :type="showPassword ? 'text' : 'password'" name="password" id="password"
                               class="w-full pl-10 pr-11 py-3 bg-black/[0.02] border border-black/10 rounded-xl focus:ring-2 focus:ring-[#412D15]/20 focus:border-[#412D15]/40 focus:bg-white outline-none transition-all placeholder:text-[#1F150C]/30 font-medium"
                               placeholder="••••••••" required>
                        <button type="button" @click="showPassword = !showPassword"
                                class="icon-nudge absolute inset-y-0 right-0 pr-3.5 flex items-center text-[#1F150C]/35 hover:text-[#412D15] transition-colors">
                            <i class="fa-solid" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                        <span class="input-underline"></span>
                    </div>
                </div>

                <div class="reveal delay-5 flex items-center justify-between text-sm pt-1">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded cursor-pointer transition-transform active:scale-90" style="accent-color:#412D15;">
                        <span class="font-medium text-[#1F150C]/80 group-hover:text-[#412D15] transition-colors">Ingat saya</span>
                    </label>

                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="font-bold transition-colors hover:underline" style="color:#412D15;">
                            Lupa sandi?
                        </a>
                    @endif
                </div>

                <button type="submit" class="reveal delay-6 w-full text-white font-bold py-3.5 rounded-xl transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5 flex items-center justify-center gap-2 mt-4 btn-primary">
                    <span>Masuk</span>
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                </button>
            </form>

            <div class="reveal delay-7 flex items-center gap-4 mt-8 text-[10px] text-[#1F150C]/35 uppercase tracking-wider">
                <i class="fa-solid fa-lock"></i> Data kamu terenkripsi dan aman
            </div>

            <p class="reveal delay-7 text-center mt-8 text-sm font-medium text-[#1F150C]/70">
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