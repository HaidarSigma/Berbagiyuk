@extends('admin.layout')
@section('judul', 'Verifikasi Barang')
@section('isi')
  <div class="bg-green-50 rounded-2xl p-4 text-sm mb-5">
    <div class="font-semibold mb-1">Aturan barang yang boleh dibagikan</div>
    <ul class="list-disc ml-5 text-gray-600 space-y-0.5">
      <li>Kondisi layak pakai (tidak rusak parah, tidak kotor atau berbau)</li>
      <li>Gratis, tidak dijual</li>
      <li>Makanan dan minuman belum kedaluwarsa dan kemasan masih tersegel</li>
      <li>Elektronik masih berfungsi normal</li>
      <li>Bukan barang terlarang atau berbahaya</li>
      <li>Foto jelas dan sesuai barang asli, lokasi dan deskripsi diisi dengan benar</li>
    </ul>
  </div>

  <div class="space-y-4">
    @forelse ($barang as $b)
      <div class="bg-white rounded-2xl shadow-sm p-4 flex flex-col lg:flex-row gap-4">
        <div class="lg:w-48 h-40 lg:h-auto shrink-0 rounded-xl bg-gray-200 overflow-hidden">
          @if ($b->foto)<img src="{{ asset('storage/'.$b->foto) }}" class="w-full h-full object-cover">@endif
        </div>

        <div class="flex-1 space-y-1">
          <div class="font-extrabold">{{ $b->judul }}</div>
          <div class="text-sm text-gray-500">{{ $b->kategori->nama }} · {{ $b->kondisi }} · 📍 {{ $b->lokasi }}</div>
          <div class="text-xs text-gray-400">oleh {{ $b->user->name }} · {{ $b->created_at->locale('id')->diffForHumans() }}</div>
          @if ($b->deskripsi)<p class="text-sm text-gray-600 pt-1">{{ $b->deskripsi }}</p>@endif
          @if ($b->latitude)
            <a target="_blank" href="https://www.google.com/maps?q={{ $b->latitude }},{{ $b->longitude }}" class="inline-block text-sm text-green-800 font-semibold pt-1">🗺️ Lihat titik di peta →</a>
          @endif
        </div>

        <div class="lg:w-64 space-y-2">
          <form method="POST" action="/admin/barang/{{ $b->id }}/setujui">@csrf
            <button class="w-full bg-green-700 text-white py-2 rounded-lg text-sm font-semibold hover:bg-green-800">✔ Setujui</button>
          </form>
          <form method="POST" action="/admin/barang/{{ $b->id }}/tolak" class="space-y-2">@csrf
            <textarea name="alasan_tolak" rows="2" required placeholder="Alasan penolakan (wajib)" class="w-full border rounded-lg px-3 py-2 text-sm"></textarea>
            <button class="w-full bg-red-600 text-white py-2 rounded-lg text-sm font-semibold hover:bg-red-700">✖ Tolak</button>
          </form>
        </div>
      </div>
    @empty
      <p class="text-gray-500">Tidak ada barang yang menunggu verifikasi. 🎉</p>
    @endforelse
  </div>
@endsection