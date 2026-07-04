<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Sandi - Tepi Kopi</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/icon.svg') }}">
    <!-- Tailwind CSS -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- FontAwesome untuk Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-amber-50 font-sans antialiased text-amber-950 min-h-screen flex">

    <!-- Bagian Kiri: Gambar Background -->
    <div class="hidden lg:flex w-1/2 bg-amber-950 relative items-center justify-center overflow-hidden">
        <div class="absolute inset-0 bg-black/50 z-10"></div>
        <img src="https://images.unsplash.com/photo-1497935586351-b67a49e012bf?auto=format&fit=crop&w=1000&q=80" 
             alt="Coffee Background" 
             class="absolute inset-0 w-full h-full object-cover z-0">
        <div class="relative z-20 text-center px-12">
            <h2 class="text-4xl xl:text-5xl font-black text-white mb-4 tracking-tight leading-tight">
                Lupa Kata <br> Sandi?
            </h2>
            <p class="text-amber-100/90 text-lg">
                Jangan khawatir, kami akan bantu kamu masuk kembali ke Tepi Kopi.
            </p>
        </div>
    </div>

    <!-- Bagian Kanan: Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 bg-white lg:rounded-l-3xl shadow-[-10px_0_30px_rgba(0,0,0,0.05)] z-20">
        <div class="w-full max-w-md">

            <!-- Header Bar -->
            <div class="flex justify-between items-center mb-10">
                <a href="/" class="text-2xl font-black text-amber-900 flex items-center gap-2 hover:opacity-80 transition-opacity">
                    <i class="fa-solid fa-mug-hot text-amber-700"></i> TepiKopi.
                </a>
                <a href="{{ route('login') }}" class="text-sm font-bold text-amber-700 hover:text-amber-900 flex items-center gap-1.5 transition-colors bg-amber-50 px-3 py-1.5 rounded-full">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
            </div>

            <!-- Judul Form -->
            <h1 class="text-3xl font-extrabold text-amber-950 mb-2">Lupa Kata Sandi</h1>
            <p class="text-amber-800/70 mb-8 font-medium">Masukkan email kamu, kami akan kirimkan link untuk atur ulang kata sandi.</p>

            <!-- Alert Status Sukses -->
            @if (session('status'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-lg"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- Alert Error -->
            @if($errors->any())
                <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-3 animate-pulse">
                    <i class="fa-solid fa-circle-exclamation text-lg"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

                <!-- Input Email -->
                <div>
                    <label for="email" class="block text-sm font-bold text-amber-950 mb-1.5">Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-amber-800/40 group-focus-within:text-amber-700 transition-colors">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <input type="email" name="email" id="email" 
                               class="w-full pl-10 pr-4 py-3 bg-amber-50/50 border border-amber-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:bg-white outline-none transition-all placeholder:text-amber-800/40 text-amber-950 font-medium shadow-sm" 
                               placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" class="w-full bg-amber-800 hover:bg-amber-900 text-white font-bold py-3.5 rounded-xl transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5 flex items-center justify-center gap-2 mt-4">
                    <span>Kirim Link Reset</span>
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>

            <!-- Link Kembali ke Login -->
            <p class="text-center mt-10 text-sm text-amber-900 font-medium">
                Sudah ingat kata sandi? 
                <a href="{{ route('login') }}" class="text-amber-700 font-bold hover:text-amber-950 transition-colors ml-1 border-b border-transparent hover:border-amber-950 pb-0.5">
                    Masuk di sini
                </a>
            </p>

        </div>
    </div>
</body>
</html>