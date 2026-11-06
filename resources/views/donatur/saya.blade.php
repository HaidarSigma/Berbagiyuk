<!DOCTYPE html>
<html lang="id" class="overflow-y-scroll">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Barang Saya - BerbagiYuk</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-6">
  <div class="max-w-3xl mx-auto space-y-4">
    <div class="flex justify-between items-center">
      <h1 class="text-2xl font-bold text-green-800">Barang Saya</h1>
      <div class="flex gap-3 text-sm items-center">
        <a href="/berbagi" class="bg-green-800 text-white px-4 py-2 rounded-lg">+ Bagikan Barang</a>
        <a href="/" class="text-green-800">← Beranda</a>
      </div>
    </div>

    @if (session('status'))
      <div class="bg-green-50 text-green-800 text-sm rounded-lg p-3">{{ session('status') }}</div>
    @endif

    @php
      $label = [
        'menunggu'  => ['Menunggu verifikasi', 'bg-yellow-100 text-yellow-800'],
        'disetujui' => ['Disetujui', 'bg-green-100 text-green-800'],
        'ditolak'   => ['Ditolak', 'bg-red-100 text-red-700'],
      ];
    @endphp

    @forelse ($barang as $b)
      <div class="bg-white rounded-xl p-4 shadow-sm flex gap-4">
        <div class="w-24 h-24 shrink-0 rounded-lg bg-gray-200 overflow-hidden">
          @if ($b->foto)<img src="{{ asset('storage/'.$b->foto) }}" class="w-full h-full object-cover">@endif
        </div>
        <div class="flex-1">
          <div class="flex justify-between items-start gap-2">
            <div class="font-semibold">{{ $b->judul }}</div>
            @if ($b->status === 'ditarik')
              <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">Ditarik</span>
            @else
              <span class="text-xs px-2 py-0.5 rounded-full {{ $label[$b->verifikasi][1] }}">{{ $label[$b->verifikasi][0] }}</span>
            @endif
          </div>
          <div class="text-sm text-gray-500">{{ $b->kategori->nama }} · {{ $b->kondisi }} · 📍 {{ $b->lokasi }}</div>
          @if ($b->verifikasi === 'ditolak' && $b->alasan_tolak)
            <p class="text-sm text-red-600 mt-1">Alasan: {{ $b->alasan_tolak }}</p>
          @endif

          <div class="mt-2 flex gap-4 text-sm">
            @if ($b->status !== 'ditarik' && in_array($b->verifikasi, ['menunggu', 'ditolak']))
              <a href="/barang-saya/{{ $b->id }}/ubah" class="text-green-800 font-semibold">
                {{ $b->verifikasi === 'ditolak' ? '✏️ Perbaiki & ajukan ulang' : '✏️ Ubah' }}
              </a>
            @endif
            @if ($b->status === 'tersedia')
              <form method="POST" action="/barang-saya/{{ $b->id }}/tarik" onsubmit="return confirm('Tarik barang ini?')">@csrf
                <button class="text-red-600">Tarik Barang</button>
              </form>
            @endif
          </div>
        </div>
      </div>
    @empty
      <p class="text-gray-500">Kamu belum membagikan barang.</p>
    @endforelse
  </div>
</body>
</html>