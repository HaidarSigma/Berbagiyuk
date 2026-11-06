<!DOCTYPE html>
<html lang="id" class="overflow-y-scroll">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('judul', 'BerbagiYuk')</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <script>tailwind.config = { theme: { extend: { fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] } } } }</script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

@php
  $aktif = 'py-1 border-b-2 border-green-800 text-green-800 font-semibold';
  $biasa = 'py-1 border-b-2 border-transparent hover:text-green-800';
@endphp

<nav class="bg-white/90 backdrop-blur sticky top-0 z-30 border-b border-gray-100">
  <div class="max-w-6xl mx-auto flex items-center justify-between gap-4 px-4 py-3">
    <a href="/" class="flex items-center gap-2">
      <span class="w-10 h-10 rounded-xl bg-green-800 text-white flex items-center justify-center text-xl">♥</span>
      <span>
        <span class="block text-xl font-extrabold text-green-800 leading-none">BerbagiYuk</span>
        <span class="block text-[11px] text-gray-500">Berbagi Sedikit, Berarti Banyak.</span>
      </span>
    </a>

    <div class="hidden lg:flex items-center gap-6 text-sm font-medium whitespace-nowrap">
      <a href="/" class="{{ $biasa }}">Beranda</a>
      <a href="/cari" class="{{ request()->is('cari*', 'barang/*') ? $aktif : $biasa }}">Cari Barang</a>
      @if (! auth()->check() || auth()->user()->role === 'donatur')
        <a href="/berbagi" class="{{ $biasa }}">Berbagi Barang</a>
      @endif
      <a href="#" class="{{ $biasa }}">Komunitas</a>
      <a href="#" class="{{ $biasa }}">Tentang Kami</a>
    </div>

    <form action="/cari" method="GET" class="hidden md:flex items-center bg-gray-100 rounded-full px-4 py-2 w-56 text-sm">
      <span class="text-gray-400 mr-2">🔍</span>
      <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari barang, kategori..." class="bg-transparent outline-none w-full">
    </form>

    @include('partials.menu-profil')
  </div>
</nav>

<main class="max-w-6xl mx-auto px-4 py-6">
  @yield('isi')
</main>

<footer class="mt-10 bg-green-900 text-green-100">
  <div class="max-w-6xl mx-auto px-4 py-6 text-center text-xs text-green-300">
    © {{ date('Y') }} BerbagiYuk. Dibuat dengan ♥ untuk berbagi.
  </div>
</footer>

</body>
</html>