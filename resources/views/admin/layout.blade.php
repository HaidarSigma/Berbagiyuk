<!DOCTYPE html>
<html lang="id" class="overflow-y-scroll">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('judul') - Admin BerbagiYuk</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <script>tailwind.config = { theme: { extend: { fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] } } } }</script>
</head>
<body class="bg-gray-100 font-sans text-gray-800">

@php
  $item = 'flex items-center gap-3 w-full px-3 py-2 rounded-lg text-sm text-left';
  $menu = [['/admin', '📊', 'Dashboard'], ['/admin/barang', '✅', 'Verifikasi Barang'], ['/admin/penerima', '👥', 'Verifikasi Penerima'], ['/admin/permintaan', '📨', 'Permintaan Masuk'],['/admin/log', '🧾', 'Log Aktivitas']];
@endphp

<div class="flex min-h-screen">

  {{-- Sidebar --}}
  <aside class="hidden md:flex sticky top-0 h-screen w-60 shrink-0 flex-col bg-green-900 text-green-100 p-4">
    <div class="px-3 py-3 mb-4">
      <div class="text-xl font-extrabold text-white leading-tight">BerbagiYuk</div>
      <div class="text-xs text-green-300">Panel Admin</div>
    </div>

    <nav class="space-y-1">
      @foreach ($menu as [$url, $ic, $nama])
        <a href="{{ $url }}" class="{{ $item }} {{ request()->is(trim($url, '/')) ? 'bg-green-700 text-white font-semibold' : 'hover:bg-green-800' }}">
          <span class="w-5 text-center">{{ $ic }}</span><span>{{ $nama }}</span>
        </a>
      @endforeach
    </nav>

    <div class="mt-4 space-y-1 border-t border-green-800 pt-3">
      <a href="/?lihat=1" class="{{ $item }} hover:bg-green-800">
        <span class="w-5 text-center">🌐</span><span>Lihat Situs</span>
      </a>
      <form method="POST" action="/keluar">@csrf
        <button class="{{ $item }} hover:bg-red-600 hover:text-white">
          <span class="w-5 text-center">🚪</span><span>Keluar</span>
        </button>
      </form>
    </div>
  </aside>

  {{-- Konten --}}
  <div class="flex-1 min-w-0">
    <header class="sticky top-0 z-10 bg-white border-b px-6 h-14 flex justify-between items-center">
      <h1 class="font-extrabold text-lg">@yield('judul')</h1>
      <div class="flex items-center gap-3 text-sm">
        <span class="md:hidden flex gap-3 text-green-800 font-semibold">
          <a href="/admin">Dashboard</a><a href="/admin/barang">Verifikasi</a>
        </span>
        <div class="w-8 h-8 rounded-full bg-green-800 text-white flex items-center justify-center font-bold">
          {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <span class="hidden sm:block font-semibold">{{ auth()->user()->name }}</span>
        <form method="POST" action="/keluar">@csrf
          <button class="border border-gray-300 text-gray-600 px-3 py-1.5 rounded-lg hover:bg-red-50 hover:text-red-600 hover:border-red-300 transition">Keluar</button>
        </form>
      </div>
    </header>

    <main class="p-6">
      @if (session('status'))
        <div class="mb-4 bg-green-50 text-green-800 text-sm rounded-lg p-3">{{ session('status') }}</div>
      @endif
      @yield('isi')
    </main>
  </div>
</div>

</body>
</html>