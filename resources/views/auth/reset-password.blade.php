<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Ulang Sandi - Tepi Kopi</title>
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
                Buat Sandi <br> Baru
            </h2>
            <p class="text-amber-100/90 text-lg">
                Amankan akun Tepi Kopi kamu dengan kata sandi yang baru.
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
            <h1 class="text-3xl font-extrabold text-amber-950 mb-2">Atur Ulang Sandi</h1>
            <p class="text-amber-800/70 mb-8 font-medium">Masukkan kata sandi baru untuk akun kamu.</p>

            <!-- Alert Error -->
            @if($errors->any())
                <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-3 animate-pulse">
                    <i class="fa-solid fa-circle-exclamation text-lg"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Input Email -->
                <div>
                    <label for="email" class="block text-sm font-bold text-amber-950 mb-1.5">Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-amber-800/40 group-focus-within:text-amber-700 transition-colors">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <input type="email" name="email" id="email" 
                               class="w-full pl-10 pr-4 py-3 bg-amber-50/50 border border-amber-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:bg-white outline-none transition-all placeholder:text-amber-800/40 text-amber-950 font-medium shadow-sm" 
                               placeholder="nama@email.com" value="{{ old('email', $email) }}" required autofocus>
                    </div>
                </div>

                <!-- Input Password Baru -->
                <div>
                    <label for="password" class="block text-sm font-bold text-amber-950 mb-1.5">Kata Sandi Baru</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-amber-800/40 group-focus-within:text-amber-700 transition-colors">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input type="password" name="password" id="password" 
                               class="w-full pl-10 pr-4 py-3 bg-amber-50/50 border border-amber-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:bg-white outline-none transition-all placeholder:text-amber-800/40 text-amber-950 font-medium shadow-sm" 
                               placeholder="••••••••" required>
                    </div>
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-bold text-amber-950 mb-1.5">Konfirmasi Kata Sandi</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-amber-800/40 group-focus-within:text-amber-700 transition-colors">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input type="password" name="password_confirmation" id="password_confirmation" 
                               class="w-full pl-10 pr-4 py-3 bg-amber-50/50 border border-amber-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:bg-white outline-none transition-all placeholder:text-amber-800/40 text-amber-950 font-medium shadow-sm" 
                               placeholder="••••••••" required>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" class="w-full bg-amber-800 hover:bg-amber-900 text-white font-bold py-3.5 rounded-xl transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5 flex items-center justify-center gap-2 mt-4">
                    <span>Simpan Sandi Baru</span>
                    <i class="fa-solid fa-check"></i>
                </button>
            </form>

        </div>
    </div>
</body>
</html>