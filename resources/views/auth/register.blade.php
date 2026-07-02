<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Tepi Kopi</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- FontAwesome untuk Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-amber-50 font-sans antialiased text-amber-950 min-h-screen flex lg:flex-row-reverse">

    <!-- Bagian Kanan: Gambar Background (Sembunyi di Mobile, Muncul di Desktop) -->
    <div class="hidden lg:flex w-1/2 bg-amber-950 relative items-center justify-center overflow-hidden">
        <!-- Overlay Gelap -->
        <div class="absolute inset-0 bg-black/60 z-10"></div>
        <!-- Gambar Kopi (Berbeda dengan halaman login) -->
        <img src="https://images.unsplash.com/photo-1559056199-641a0ac8b55e?auto=format&fit=crop&w=1000&q=80" 
             alt="Coffee Beans Background" 
             class="absolute inset-0 w-full h-full object-cover z-0">
        
        <!-- Teks Sapaan -->
        <div class="relative z-20 text-center px-12">
            <h2 class="text-4xl xl:text-5xl font-black text-white mb-4 tracking-tight leading-tight">
                Bergabunglah <br> Bersama Kami!
            </h2>
            <p class="text-amber-100/90 text-lg">
                Jadilah bagian dari komunitas pecinta kopi dan dapatkan akses ke koleksi biji kopi premium kami.
            </p>
        </div>
    </div>

    <!-- Bagian Kiri: Form Register -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 bg-white lg:rounded-r-3xl shadow-[10px_0_30px_rgba(0,0,0,0.05)] z-20 overflow-y-auto">
        <div class="w-full max-w-md py-8">
            
            <!-- Header Bar (Logo & Tombol Kembali) -->
            <div class="flex justify-between items-center mb-10">
                <a href="/" class="text-2xl font-black text-amber-900 flex items-center gap-2 hover:opacity-80 transition-opacity">
                    <i class="fa-solid fa-mug-hot text-amber-700"></i> TepiKopi.
                </a>
                <a href="/" class="text-sm font-bold text-amber-700 hover:text-amber-900 flex items-center gap-1.5 transition-colors bg-amber-50 px-3 py-1.5 rounded-full">
                    <i class="fa-solid fa-arrow-left"></i> Beranda
                </a>
            </div>

            <!-- Judul Form -->
            <h1 class="text-3xl font-extrabold text-amber-950 mb-2">Buat Akun Baru</h1>
            <p class="text-amber-800/70 mb-8 font-medium">Lengkapi data di bawah ini untuk mendaftar.</p>

            <!-- Alert Error Umum -->
            @if($errors->any())
                <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm font-bold flex items-start gap-3 animate-pulse">
                    <i class="fa-solid fa-circle-exclamation text-lg mt-0.5"></i>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="/register" class="space-y-4">
                @csrf
                
                <!-- Input Nama Lengkap -->
                <div>
                    <label for="name" class="block text-sm font-bold text-amber-950 mb-1.5">Nama Lengkap</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-amber-800/40 group-focus-within:text-amber-700 transition-colors">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <input type="text" name="name" id="name" 
                               class="w-full pl-10 pr-4 py-3 bg-amber-50/50 border border-amber-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:bg-white outline-none transition-all placeholder:text-amber-800/40 text-amber-950 font-medium shadow-sm" 
                               placeholder="Nama lengkap Anda" value="{{ old('name') }}" required autofocus>
                    </div>
                </div>

                <!-- Input Email -->
                <div>
                    <label for="email" class="block text-sm font-bold text-amber-950 mb-1.5">Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-amber-800/40 group-focus-within:text-amber-700 transition-colors">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <input type="email" name="email" id="email" 
                               class="w-full pl-10 pr-4 py-3 bg-amber-50/50 border border-amber-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:bg-white outline-none transition-all placeholder:text-amber-800/40 text-amber-950 font-medium shadow-sm" 
                               placeholder="nama@email.com" value="{{ old('email') }}" required>
                    </div>
                </div>

                <!-- Input Password -->
                <div>
                    <label for="password" class="block text-sm font-bold text-amber-950 mb-1.5">Kata Sandi</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-amber-800/40 group-focus-within:text-amber-700 transition-colors">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input type="password" name="password" id="password" 
                               class="w-full pl-10 pr-4 py-3 bg-amber-50/50 border border-amber-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:bg-white outline-none transition-all placeholder:text-amber-800/40 text-amber-950 font-medium shadow-sm" 
                               placeholder="Minimal 8 karakter" required>
                    </div>
                </div>

                <!-- Input Konfirmasi Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-bold text-amber-950 mb-1.5">Konfirmasi Kata Sandi</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-amber-800/40 group-focus-within:text-amber-700 transition-colors">
                            <i class="fa-solid fa-check-double"></i>
                        </div>
                        <input type="password" name="password_confirmation" id="password_confirmation" 
                               class="w-full pl-10 pr-4 py-3 bg-amber-50/50 border border-amber-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 focus:bg-white outline-none transition-all placeholder:text-amber-800/40 text-amber-950 font-medium shadow-sm" 
                               placeholder="Ulangi kata sandi" required>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" class="w-full bg-amber-800 hover:bg-amber-900 text-white font-bold py-3.5 rounded-xl transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5 flex items-center justify-center gap-2 mt-6">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>Daftar Sekarang</span>
                </button>
            </form>

            <!-- Link Login -->
            <p class="text-center mt-8 text-sm text-amber-900 font-medium">
                Sudah memiliki akun? 
                <a href="/login" class="text-amber-700 font-bold hover:text-amber-950 transition-colors ml-1 border-b border-transparent hover:border-amber-950 pb-0.5">
                    Masuk di sini
                </a>
            </p>
            
        </div>
    </div>
</body>
</html>