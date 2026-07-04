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
        body{ font-family:'Plus Jakarta Sans', sans-serif; }
        .font-display{ font-family:'Fraunces', serif; }
        .btn-primary{ background:#412D15; transition:background-color .25s ease; }
        .btn-primary:hover{ background:#1F150C; }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center p-6" style="background:#E1DCC9; color:#1F150C;">

    {{-- Halaman utilitas ringan: kartu tunggal terpusat, tanpa panel marketing --}}
    <a href="/" class="inline-flex items-center gap-2 font-display text-xl font-semibold mb-8">
        <i class="fa-solid fa-mug-hot" style="color:#412D15;"></i> TepiKopi.
    </a>

    <div class="w-full max-w-md bg-white rounded-3xl shadow-xl p-7 sm:p-10">

        <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-6" style="background:#E1DCC9; color:#412D15;">
            <i class="fa-solid fa-key text-xl"></i>
        </div>

        <h1 class="font-display text-2xl sm:text-3xl font-semibold mb-2">Lupa Kata Sandi</h1>
        <p class="text-[#1F150C]/55 mb-8 text-sm sm:text-base">Masukkan email kamu, kami akan kirimkan link untuk atur ulang kata sandi.</p>

        @if (session('status'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-lg"></i>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-3">
                <i class="fa-solid fa-circle-exclamation text-lg"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
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

            <button type="submit" class="w-full text-white font-bold py-3.5 rounded-xl transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5 flex items-center justify-center gap-2 btn-primary">
                <span>Kirim Link Reset</span>
                <i class="fa-solid fa-paper-plane"></i>
            </button>
        </form>
    </div>

    <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-sm font-bold mt-6 transition-colors" style="color:#412D15;">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke halaman masuk
    </a>
</body>
</html>