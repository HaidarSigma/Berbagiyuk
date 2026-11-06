@extends('layouts.situs')
@section('judul', 'Permintaan Saya - BerbagiYuk')
@section('isi')
  @php
    $label = [
      'menunggu'   => ['Menunggu persetujuan', 'bg-yellow-100 text-yellow-800'],
      'disetujui'  => ['Disetujui', 'bg-green-100 text-green-800'],
      'ditolak'    => ['Ditolak', 'bg-red-100 text-red-700'],
      'dibatalkan' => ['Dibatalkan', 'bg-gray-100 text-gray-600'],
    ];
    $aktif = $permintaan->whereIn('status', ['menunggu', 'disetujui'])->count();
  @endphp

  <div class="flex justify-between items-center">
    <h1 class="text-2xl font-extrabold">Permintaan Saya</h1>
    <span class="text-sm bg-white rounded-full shadow-sm px-3 py-1">Permintaan aktif: <b>{{ $aktif }}/3</b></span>
  </div>

  @if (session('status'))
    <div class="mt-4 bg-green-50 text-green-800 text-sm rounded-lg p-3">{{ session('status') }}</div>
  @endif

  <div class="mt-4 space-y-3">
    @forelse ($permintaan as $p)
      <div class="bg-white rounded-2xl shadow-sm p-4 flex gap-4">
        <div class="w-24 h-24 shrink-0 rounded-lg bg-gray-200 overflow-hidden">
          @if ($p->barang->foto)<img src="{{ asset('storage/'.$p->barang->foto) }}" class="w-full h-full object-cover">@endif
        </div>
        <div class="flex-1 space-y-1">
          <div class="flex justify-between items-start gap-2">
            <a href="/barang/{{ $p->barang->id }}" class="font-bold hover:text-green-800">{{ $p->barang->judul }}</a>
            <span class="text-xs px-2 py-0.5 rounded-full whitespace-nowrap {{ $label[$p->status][1] }}">{{ $label[$p->status][0] }}</span>
          </div>
          <div class="text-sm text-gray-500">{{ $p->barang->kategori->nama }} · 📍 {{ $p->barang->lokasi }}</div>
          <p class="text-sm text-gray-600">“{{ $p->pesan }}”</p>
          @if ($p->status === 'ditolak' && $p->alasan_tolak)
            <p class="text-sm text-red-600"><b>Alasan:</b> {{ $p->alasan_tolak }}</p>
          @endif
          @if ($p->status === 'disetujui')
            <p class="text-sm text-green-700">Permintaanmu disetujui. Petugas akan menghubungimu untuk pengiriman.</p>
          @endif
          <div class="flex gap-4 text-xs text-gray-400 items-center pt-1">
            <span>diajukan {{ $p->created_at->locale('id')->diffForHumans() }}</span>
            @if ($p->status === 'menunggu')
              <form method="POST" action="/permintaan-saya/{{ $p->id }}/batal" onsubmit="return confirm('Batalkan permintaan ini?')">@csrf
                <button class="text-red-600 font-semibold">Batalkan</button>
              </form>
            @endif
          </div>
        </div>
      </div>
    @empty
      <p class="text-gray-500">Kamu belum mengajukan permintaan. <a href="/cari" class="text-green-800 font-semibold">Cari barang →</a></p>
    @endforelse
  </div>
@endsection