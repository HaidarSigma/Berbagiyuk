@extends('layouts.situs')
@section('judul', 'Cari Barang - BerbagiYuk')
@section('isi')
  <h1 class="text-2xl font-extrabold">Cari Barang</h1>

  <form method="GET" action="/cari" class="mt-4 bg-white rounded-2xl shadow-sm p-4 grid md:grid-cols-5 gap-3">
    <input name="q" value="{{ request('q') }}" placeholder="Cari nama, deskripsi, atau area..." class="md:col-span-2 border rounded-lg px-3 py-2 text-sm">
    <select name="kategori" class="border rounded-lg px-3 py-2 text-sm">
      <option value="">Semua kategori</option>
      @foreach ($kategori as $k)
        <option value="{{ $k->id }}" @selected(request('kategori') == $k->id)>{{ $k->nama }}</option>
      @endforeach
    </select>
    <select name="kondisi" class="border rounded-lg px-3 py-2 text-sm">
      <option value="">Semua kondisi</option>
      @foreach (['Baru', 'Layak Pakai', 'Perlu Perbaikan'] as $kd)
        <option @selected(request('kondisi') == $kd)>{{ $kd }}</option>
      @endforeach
    </select>
    <button class="bg-green-800 text-white rounded-lg px-4 py-2 text-sm font-semibold hover:bg-green-900">Cari</button>
  </form>

  <p class="text-sm text-gray-500 mt-4">{{ $barang->total() }} barang ditemukan</p>

  <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-3">
    @forelse ($barang as $b)
      @include('partials.kartu-barang', ['b' => $b])
    @empty
      <p class="col-span-full text-gray-500">Tidak ada barang yang cocok. Coba kata kunci atau filter lain.</p>
    @endforelse
  </div>

  <div class="mt-6">{{ $barang->links() }}</div>
@endsection