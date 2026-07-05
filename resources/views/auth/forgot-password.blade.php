<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Sandi - Tepi Kopi</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/icon.svg') }}">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&display=swap" rel="stylesheet">
    <style>
        body{ font-family:'Plus Jakarta Sans', sans-serif; position:relative; overflow-x:hidden; }
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

        /* Tombol: kilau halus + pesawat kertas melesat saat hover */
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
        .btn-primary i{ transition:transform .3s ease; }
        .btn-primary:hover i{ transform:translate(4px,-3px) rotate(20deg); }

        /* Bola ambient lembut di belakang kartu agar latar krem tidak datar */
        .ambient-orb{
            position:fixed;
            border-radius:9999px;
            filter:blur(70px);
            pointer-events:none;
            z-index:0;
            animation:orbFloat 16s ease-in-out infinite;
        }
        @keyframes orbFloat{
            0%, 100% { transform:translate(0,0) scale(1); }
            50% { transform:translate(24px,-20px) scale(1.08); }
        }

        /* Urutan masuk halaman */
        .reveal{
            opacity:0;
            transform:translateY(16px);
            animation:reveal .65s cubic-bezier(.22,1,.36,1) forwards;
        }
        @keyframes reveal{ to{ opacity:1; transform:translateY(0); } }
        .delay-1{ animation-delay:.05s; }
        .delay-2{ animation-delay:.14s; }
        .delay-3{ animation-delay:.22s; }
        .delay-4{ animation-delay:.3s; }
        .delay-5{ animation-delay:.38s; }
        .delay-6{ animation-delay:.46s; }

        /* Kartu utama masuk dengan sedikit scale, terasa lebih hidup dari sekadar fade */
        .card-pop{
            opacity:0;
            transform:translateY(18px) scale(.97);
            animation:cardPop .55s cubic-bezier(.22,1,.36,1) forwards;
            animation-delay:.08s;
        }
        @keyframes cardPop{ to{ opacity:1; transform:translateY(0) scale(1); } }

        /* Ikon kunci: cincin denyut lembut di belakangnya */
        .icon-badge{ position:relative; }
        .icon-badge::after{
            content:'';
            position:absolute; inset:-6px;
            border-radius:1rem;
            border:2px solid rgba(65,45,21,0.25);
            animation:pulseRing 2.4s ease-out infinite;
        }
        @keyframes pulseRing{
            0%   { transform:scale(0.85); opacity:.8; }
            80%  { transform:scale(1.25); opacity:0; }
            100% { opacity:0; }
        }

        /* Input: garis bawah tumbuh saat fokus */
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

        /* Notifikasi status: sukses "pop" masuk, error bergetar */
        .status-pop{ animation:statusPop .4s cubic-bezier(.34,1.56,.64,1); }
        @keyframes statusPop{
            0%{ opacity:0; transform:scale(.92) translateY(-4px); }
            100%{ opacity:1; transform:scale(1) translateY(0); }
        }
        .error-shake{ animation:shake .45s ease; }
        @keyframes shake{
            10%, 90% { transform:translateX(-1px); }
            20%, 80% { transform:translateX(2px); }
            30%, 50%, 70% { transform:translateX(-4px); }
            40%, 60% { transform:translateX(4px); }
        }

        /* Tautan kembali: panah bergeser sedikit saat hover */
        .back-link i{ transition:transform .2s ease; }
        .back-link:hover i{ transform:translateX(-3px); }

        @media (prefers-reduced-motion: reduce){
            .reveal, .card-pop, .ambient-orb, .icon-badge::after, .status-pop, .error-shake{ animation:none !important; }
            .reveal, .card-pop{ opacity:1; transform:none; }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center p-6" style="background:#E1DCC9; color:#1F150C;">

    {{-- Bola ambient lembut agar latar krem tidak terasa datar --}}
    <div class="ambient-orb" style="width:320px; height:320px; top:-80px; left:-60px; background:radial-gradient(circle, rgba(65,45,21,0.16), transparent 70%);"></div>
    <div class="ambient-orb" style="width:280px; height:280px; bottom:-60px; right:-60px; background:radial-gradient(circle, rgba(65,45,21,0.12), transparent 70%); animation-delay:-8s;"></div>

    {{-- Halaman utilitas ringan: kartu tunggal terpusat, tanpa panel marketing --}}
    <a href="/" class="reveal relative z-10 inline-flex items-center gap-2 font-display text-xl font-semibold mb-8">
        <i class="fa-solid fa-mug-hot" style="color:#412D15;"></i> TepiKopi.
    </a>

    <div class="card-pop relative z-10 w-full max-w-md bg-white rounded-3xl shadow-xl p-7 sm:p-10">

        <div class="icon-badge w-14 h-14 rounded-2xl flex items-center justify-center mb-6" style="background:#E1DCC9; color:#412D15;">
            <i class="fa-solid fa-key text-xl"></i>
        </div>

        <h1 class="reveal delay-1 font-display text-2xl sm:text-3xl font-semibold mb-2">Lupa Kata Sandi</h1>
        <p class="reveal delay-2 text-[#1F150C]/55 mb-8 text-sm sm:text-base">Masukkan email kamu, kami akan kirimkan link untuk atur ulang kata sandi.</p>

        @if (session('status'))
            <div class="status-pop mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-lg"></i>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="error-shake mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-3">
                <i class="fa-solid fa-circle-exclamation text-lg"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
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

            <button type="submit" class="reveal delay-4 w-full text-white font-bold py-3.5 rounded-xl transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5 flex items-center justify-center gap-2 btn-primary">
                <span>Kirim Link Reset</span>
                <i class="fa-solid fa-paper-plane"></i>
            </button>
        </form>
    </div>

    <a href="{{ route('login') }}" class="back-link reveal delay-5 relative z-10 inline-flex items-center gap-1.5 text-sm font-bold mt-6 transition-colors" style="color:#412D15;">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke halaman masuk
    </a>
</body>
</html>