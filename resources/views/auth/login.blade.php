<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tepi Kopi</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-amber-50 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
        <h1 class="text-2xl font-bold text-amber-950 mb-6">Login</h1>
        @if($errors->any())
            <div class="bg-red-50 text-red-700 p-3 rounded mb-4 text-sm">
                {{ $errors->first() }}
            </div>
        @endif
        <form method="POST" action="/login">
            @csrf
            <input type="email" name="email" placeholder="Email" class="w-full border p-3 rounded mb-3" required>
            <input type="password" name="password" placeholder="Password" class="w-full border p-3 rounded mb-4" required>
            <button class="w-full bg-amber-800 text-white p-3 rounded font-bold">Login</button>
        </form>
        <p class="text-sm text-center mt-4">Belum punya akun? <a href="/register" class="text-amber-800 font-bold">Register</a></p>
    </div>
</body>
</html>