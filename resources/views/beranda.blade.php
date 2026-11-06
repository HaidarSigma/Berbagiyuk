<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BerbagiYuk</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <script>
    tailwind.config = { theme: { extend: { fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'], hand: ['Caveat', 'cursive'] } } } }
  </script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

@php
  $ikon  = ['👕','📚','🪑','🏠','🎒','💻','🍽️','⋯'];
  $warna = ['bg-green-100','bg-blue-100','bg-orange-100','bg-purple-100','bg-rose-100','bg-cyan-100','bg-red-100','bg-gray-100'];
  $adaFoto = file_exists(public_path('img/hero.jpg'));
@endphp

{{-- Navbar --}}
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
      <a href="/" class="py-1 border-b-2 border-green-800 text-green-800 font-semibold">Beranda</a>
      <a href="/cari" class="py-1 border-b-2 border-transparent hover:text-green-800">Cari Barang</a>
      @if (! auth()->check() || auth()->user()->role === 'donatur')
      <a href="/berbagi" class="py-1 border-b-2 border-transparent hover:text-green-800">Berbagi Barang</a>
      @endif
      <a href="#" class="py-1 border-b-2 border-transparent hover:text-green-800">Komunitas</a>
      <a href="#" class="py-1 border-b-2 border-transparent hover:text-green-800">Tentang Kami</a>
    </div>

    <form action="/cari" method="GET" class="hidden md:flex items-center bg-gray-100 rounded-full px-4 py-2 w-56 text-sm">
      <span class="text-gray-400 mr-2">🔍</span>
      <input type="text" name="q" placeholder="Cari barang, kategori..." class="bg-transparent outline-none w-full">
    </form>

    @include('partials.menu-profil')
    
  </div>
</nav>

<main class="max-w-6xl mx-auto px-4 py-6 space-y-8">

  {{-- Hero --}}
  <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-green-100 via-green-50 to-white">
    @if ($adaFoto)
      <img src="{{ asset('img/hero.jpg') }}" alt="" class="absolute inset-y-0 right-0 w-full md:w-3/5 h-full object-cover">
      <div class="absolute inset-0 bg-gradient-to-r from-green-50 via-green-50/80 to-transparent"></div>
    @else
      <div class="absolute -right-16 -top-16 w-72 h-72 rounded-full bg-green-200/60"></div>
      <div class="absolute right-28 -bottom-24 w-64 h-64 rounded-full bg-green-300/40"></div>
      <div class="hidden md:flex absolute right-12 top-10 gap-4 text-6xl">
        <span class="bg-white rounded-2xl shadow-lg p-5 -rotate-6">👕</span>
        <span class="bg-white rounded-2xl shadow-lg p-5 mt-8">📦</span>
        <span class="bg-white rounded-2xl shadow-lg p-5 rotate-6">📚</span>
      </div>
    @endif

    <div class="relative p-8 md:p-14 max-w-xl">
      <span class="inline-block bg-white text-green-800 text-xs font-semibold px-3 py-1 rounded-full shadow-sm">🌱 Berbagi barang, tanpa biaya</span>
      <h1 class="mt-4 text-4xl md:text-5xl font-extrabold leading-tight text-gray-900">
        Barang tak terpakai,<br><span class="text-green-700">manfaat bagi sesama.</span>
      </h1>
      <p class="mt-4 text-gray-600">Temukan barang gratis atau bagikan barang layak pakai kepada orang yang membutuhkan.</p>
      <div class="mt-6 flex flex-wrap gap-3">
        @if (! auth()->check() || auth()->user()->role === 'donatur')
        <a href="/berbagi" class="bg-green-800 text-white px-6 py-3 rounded-xl font-semibold shadow hover:bg-green-900 transition">+ Bagikan Barang</a>
        @endif
        <a href="/cari" class="bg-white/70 border-2 border-green-800 text-green-800 px-6 py-3 rounded-xl font-semibold hover:bg-white transition">🔍 Cari Barang</a>
      </div>
      <div class="mt-6 flex flex-wrap gap-x-5 gap-y-1 text-xs text-gray-600">
        <span>✔ Barang diperiksa admin</span>
        <span>✔ Penjemputan dibantu petugas</span>
        <span>✔ 100% gratis</span>
      </div>
    </div>

    <p class="hidden md:block absolute right-10 bottom-8 font-hand text-3xl -rotate-6 text-right leading-tight {{ $adaFoto ? 'text-white drop-shadow' : 'text-green-800' }}">
      Sedikit berbagi,<br>banyak kebahagiaan ♥
    </p>
  </section>

  {{-- Kategori --}}
  <section class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3">
    @foreach ($kategori as $i => $k)
      <a href="/cari?kategori={{ $k->id }}" class="group bg-white rounded-2xl p-3 text-center shadow-sm hover:shadow-md hover:-translate-y-0.5 transition">
        <div class="w-12 h-12 mx-auto rounded-full {{ $warna[$i] ?? 'bg-gray-100' }} flex items-center justify-center text-2xl group-hover:scale-110 transition">{{ $ikon[$i] ?? '📦' }}</div>
        <div class="text-xs font-semibold mt-2 leading-tight">{{ $k->nama }}</div>
      </a>
    @endforeach
  </section>

  <div class="grid lg:grid-cols-3 gap-6">

    {{-- Barang Gratis Terbaru --}}
    <section class="lg:col-span-2">
      <div class="flex justify-between items-center mb-3">
        <h2 class="text-xl font-extrabold">📢 Barang Gratis Terbaru</h2>
        <a href="/cari" class="text-sm text-green-800 font-semibold">Lihat Semua →</a>
      </div>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @forelse ($barang as $b)
          <a href="/barang/{{ $b->id }}" class="group bg-white rounded-2xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition overflow-hidden">
            <div class="h-32 relative flex items-center justify-center {{ $warna[$b->kategori_id - 1] ?? 'bg-gray-100' }}">
              @if ($b->foto)
                <img src="{{ asset('storage/'.$b->foto) }}" class="absolute inset-0 w-full h-full object-cover">
              @else
                <span class="text-5xl group-hover:scale-110 transition">{{ $ikon[$b->kategori_id - 1] ?? '📦' }}</span>
              @endif
              <span class="absolute bottom-2 left-2 bg-green-700 text-white text-[11px] font-semibold px-2 py-0.5 rounded-md">Tersedia</span>
              <span class="absolute top-2 right-2 w-8 h-8 bg-white/90 rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 transition">♥</span>
            </div>
            <div class="p-3">
              <div class="text-sm font-bold leading-snug">{{ $b->judul }}</div>
              <span class="inline-block mt-1 text-[11px] bg-green-50 text-green-800 px-2 py-0.5 rounded">{{ $b->kondisi }}</span>
              <div class="text-xs text-gray-500 mt-2">📍 {{ $b->lokasi }}</div>
              <div class="text-xs text-gray-400 mt-0.5">{{ $b->created_at->locale('id')->diffForHumans() }}</div>
            </div>
          </a>
        @empty
          <p class="col-span-full text-gray-500">Belum ada barang.</p>
        @endforelse
      </div>
    </section>

    {{-- Sidebar --}}
    <aside class="space-y-4">
      <div class="bg-green-50 rounded-2xl p-4">
        <div class="flex justify-between items-center mb-3">
          <h2 class="font-extrabold">📦 Kebutuhan Mendesak</h2>
          <a href="#" class="text-xs text-green-800 font-semibold">Lihat Semua →</a>
        </div>
        <div class="space-y-2">
          @foreach ($mendesak as $m)
            <div class="bg-white rounded-xl p-3 flex justify-between items-start gap-2 hover:shadow-sm transition">
              <div>
                <div class="text-sm font-bold">{{ $m->judul }}</div>
                <div class="text-xs text-gray-500">{{ $m->keterangan }}</div>
                <div class="text-xs text-gray-400 mt-1">📍 {{ $m->lokasi }}</div>
              </div>
              <span class="text-[11px] bg-green-100 text-green-800 font-semibold px-2 py-0.5 rounded-full">Mendukung</span>
            </div>
          @endforeach
        </div>
      </div>

      <div class="relative overflow-hidden bg-gradient-to-br from-green-100 to-green-50 rounded-2xl p-5">
        <div class="absolute -right-6 -bottom-6 text-8xl opacity-30">🤲</div>
        <h3 class="relative font-extrabold text-lg">Yuk, jadi bagian dari kebaikan ini!</h3>
        <p class="relative text-sm text-gray-600 mt-1">Setiap barang yang kamu bagikan bisa menjadi harapan baru bagi orang lain.</p>
        @if (! auth()->check() || auth()->user()->role === 'donatur')
        <a href="/berbagi" class="relative inline-block mt-3 bg-green-800 text-white text-sm font-semibold px-4 py-2 rounded-xl hover:bg-green-900 transition">Mulai Berbagi Sekarang →</a>
        @endif
      </div>

      <div class="bg-white rounded-2xl p-4 grid grid-cols-3 text-center shadow-sm divide-x">
        <div class="px-1"><div class="text-2xl">🎁</div><div class="font-extrabold text-green-800">{{ number_format($stat['barang'], 0, ',', '.') }}</div><div class="text-[11px] text-gray-500">Barang Dibagikan</div></div>
        <div class="px-1"><div class="text-2xl">👥</div><div class="font-extrabold text-green-800">{{ number_format($stat['penerima'], 0, ',', '.') }}</div><div class="text-[11px] text-gray-500">Penerima Manfaat</div></div>
        <div class="px-1"><div class="text-2xl">🌿</div><div class="font-extrabold text-green-800">{{ number_format($stat['komunitas'], 0, ',', '.') }}</div><div class="text-[11px] text-gray-500">Komunitas Terlibat</div></div>
      </div>
    </aside>
  </div>

  {{-- Nilai-nilai --}}
  @php
    $nilai = [
      ['🛡️', 'Aman & Terpercaya', 'Profil pengguna diverifikasi dan ada fitur pelaporan.'],
      ['🌿', 'Ramah Lingkungan', 'Mengurangi sampah dan memanfaatkan barang yang masih layak.'],
      ['👥', 'Untuk Semua', 'Siapa pun bisa berbagi dan menerima bantuan.'],
      ['💚', 'Bersama Lebih Baik', 'Karena kebaikan selalu berawal dari hal kecil.'],
    ];
  @endphp
  <section class="bg-green-50 rounded-2xl p-6 grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
    @foreach ($nilai as [$ic, $jd, $ket])
      <div class="flex gap-3">
        <div class="w-11 h-11 shrink-0 rounded-full bg-green-700 flex items-center justify-center text-lg">{{ $ic }}</div>
        <div class="text-sm"><b>{{ $jd }}</b><p class="text-gray-500 text-xs mt-0.5">{{ $ket }}</p></div>
      </div>
    @endforeach
  </section>
</main>

{{-- Footer --}}
<footer class="mt-10 bg-green-900 text-green-100">
  <div class="max-w-6xl mx-auto px-4 py-8 grid md:grid-cols-3 gap-6 text-sm">
    <div>
      <div class="text-xl font-extrabold text-white">BerbagiYuk</div>
      <p class="mt-2 text-green-200 text-xs">Berbagi Sedikit, Berarti Banyak.</p>
    </div>
    <div class="space-y-1">
      <div class="font-semibold text-white">Menu</div>
      <a href="/cari" class="block hover:text-white">Cari Barang</a>
      @if (! auth()->check() || auth()->user()->role === 'donatur')
      <a href="/berbagi" class="block hover:text-white">Berbagi Barang</a>
      @endif
      <a href="#" class="block hover:text-white">Komunitas</a>
    </div>
    <div class="space-y-1">
      <div class="font-semibold text-white">Tentang</div>
      <a href="#" class="block hover:text-white">Tentang Kami</a>
      <a href="#" class="block hover:text-white">Aturan Barang</a>
    </div>
  </div>
  <div class="border-t border-green-800 text-center text-xs py-3 text-green-300">© {{ date('Y') }} BerbagiYuk. Dibuat dengan ♥ untuk berbagi.</div>
</footer>

</body>
</html>