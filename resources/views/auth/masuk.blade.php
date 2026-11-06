<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Masuk - BerbagiYuk</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
  <form method="POST" action="/masuk" class="bg-white rounded-2xl shadow-sm p-8 w-full max-w-sm space-y-4">
    @csrf
    <h1 class="text-2xl font-bold text-green-800">Masuk</h1>
    @if (session('status')) <p class="text-sm text-green-700">{{ session('status') }}</p> @endif
    @error('email') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
    <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" class="w-full border rounded-lg px-3 py-2" required>
    <input type="password" name="password" placeholder="Password" class="w-full border rounded-lg px-3 py-2" required>
    <button class="w-full bg-green-800 text-white rounded-lg py-2 font-semibold">Masuk</button>
    <p class="text-sm text-center"><a href="/lupa" class="text-green-800">Lupa password?</a></p>
    <p class="text-sm text-center text-gray-500">Belum punya akun? <a href="/daftar" class="text-green-800">Daftar</a></p>
  </form>
</body>
</html>