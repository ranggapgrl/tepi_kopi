<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Tepi Kopi</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-amber-50 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
        <h1 class="text-2xl font-bold text-amber-950 mb-6">Register</h1>
        <form method="POST" action="/register">
            @csrf
            <input type="text" name="name" placeholder="Nama Lengkap" class="w-full border p-3 rounded mb-3" required>
            <input type="email" name="email" placeholder="Email" class="w-full border p-3 rounded mb-3" required>
            <input type="password" name="password" placeholder="Password" class="w-full border p-3 rounded mb-3" required>
            <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" class="w-full border p-3 rounded mb-4" required>
            <button class="w-full bg-amber-800 text-white p-3 rounded font-bold">Register</button>
        </form>
        <p class="text-sm text-center mt-4">Sudah punya akun? <a href="/login" class="text-amber-800 font-bold">Login</a></p>
    </div>
</body>
</html>