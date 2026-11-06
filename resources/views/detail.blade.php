@extends('layouts.situs')
@section('judul', $barang->judul . ' - BerbagiYuk')
@section('isi')
  @php
    [$boleh, $pesan] = $barang->statusPengajuan(auth()->user());
    $aktif = auth()->check()
        ? \App\Models\Permintaan::where('penerima_id', auth()->id())->whereIn('status', ['menunggu', 'disetujui'])->get()
        : collect();
    $sudah = $aktif->contains('barang_id', $barang->id);
    $penuh = $aktif->count() >= 3;
    $ikon  = ['👕','📚','🪑','🏠','🎒','💻','🍽️','⋯'];
    $warna = ['bg-green-100','bg-blue-100','bg-orange-100','bg-purple-100','bg-rose-100','bg-cyan-100','bg-red-100','bg-gray-100'];
  @endphp

  <a href="/cari" class="text-sm text-green-800 font-semibold">← Kembali ke pencarian</a>

  <div class="mt-3 grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm overflow-hidden">
      <div class="h-72 relative flex items-center justify-center {{ $warna[$barang->kategori_id - 1] ?? 'bg-gray-100' }}">
        @if ($barang->foto)
          <img src="{{ asset('storage/'.$barang->foto) }}" class="absolute inset-0 w-full h-full object-cover">
        @else
          <span class="text-8xl">{{ $ikon[$barang->kategori_id - 1] ?? '📦' }}</span>
        @endif
      </div>
      <div class="p-6 space-y-3">
        <div class="flex flex-wrap gap-2 text-xs">
          <span class="bg-green-100 text-green-800 font-semibold px-2.5 py-1 rounded-full">{{ $barang->kategori->nama }}</span>
          <span class="bg-gray-100 text-gray-700 px-2.5 py-1 rounded-full">{{ $barang->kondisi }}</span>
        </div>
        <h1 class="text-2xl font-extrabold">{{ $barang->judul }}</h1>
        <div class="text-sm text-gray-500">
          📍 {{ $barang->lokasi }} · dibagikan {{ $barang->created_at->locale('id')->diffForHumans() }}
          oleh {{ \Illuminate\Support\Str::before($barang->user->name, ' ') }}
        </div>
        <p class="text-gray-700 whitespace-pre-line">{{ $barang->deskripsi }}</p>
      </div>
    </div>

    <aside class="space-y-4">
      <div class="bg-white rounded-2xl shadow-sm p-5 space-y-3">
        <h2 class="font-extrabold">Boleh diambil oleh</h2>
        <div class="flex flex-wrap gap-2">
          @foreach ($barang->syarat() as $s)
            <span class="text-xs bg-green-50 text-green-800 px-2.5 py-1 rounded-full">{{ \App\Models\Barang::JENIS[$s] }}</span>
          @endforeach
        </div>
      </div>

      <div class="bg-white rounded-2xl shadow-sm p-5 space-y-3">
        @if ($boleh && $sudah)
          <div class="bg-yellow-50 text-yellow-800 text-sm rounded-lg p-3">Kamu sudah mengajukan permintaan untuk barang ini.</div>
          <a href="/permintaan-saya" class="inline-block text-sm text-green-800 font-semibold">Lihat status permintaan →</a>
        @elseif ($boleh && $penuh)
          <div class="bg-yellow-50 text-yellow-800 text-sm rounded-lg p-3">Kamu sudah memiliki 3 permintaan aktif. Tunggu salah satunya selesai atau batalkan dulu.</div>
          <a href="/permintaan-saya" class="inline-block text-sm text-green-800 font-semibold">Lihat permintaanku →</a>
        @elseif ($boleh)
          <form method="POST" action="/barang/{{ $barang->id }}/ajukan" class="space-y-3">
            @csrf
            <label class="text-sm font-semibold">Mengapa kamu membutuhkan barang ini?</label>
            <textarea name="pesan" rows="4" required minlength="10" maxlength="500" placeholder="Ceritakan singkat kebutuhanmu..." class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('pesan') }}</textarea>
            @error('pesan') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
            <button class="w-full bg-green-800 text-white py-2.5 rounded-xl font-semibold hover:bg-green-900 transition">Ajukan Permintaan</button>
            <p class="text-xs text-gray-500">Admin akan memeriksa permintaanmu. Permintaan aktif: {{ $aktif->count() }}/3.</p>
          </form>
        @else
          <button disabled class="w-full bg-gray-200 text-gray-500 py-2.5 rounded-xl font-semibold cursor-not-allowed">Ajukan Permintaan</button>
          <p class="text-sm text-gray-600">{{ $pesan }}</p>
          @guest
            <a href="/masuk" class="inline-block text-sm text-green-800 font-semibold">Masuk atau daftar →</a>
          @endguest
          @if (auth()->check() && auth()->user()->role === 'penerima' && auth()->user()->status_penerima !== 'disetujui')
            <a href="/status-penerima" class="inline-block text-sm text-green-800 font-semibold">Lihat status verifikasi →</a>
          @endif
        @endif
      </div>
    </aside>
  </div>
@endsection