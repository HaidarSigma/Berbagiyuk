@extends('admin.layout')
@section('judul', 'Dashboard')
@section('isi')
  @php
    $kartu = [
      ['Menunggu Verifikasi', $menunggu, '⏳', 'bg-yellow-100'],
      ['Barang Tersedia', $tersedia, '📦', 'bg-green-100'],
      ['Barang Ditolak', $ditolak, '🚫', 'bg-red-100'],
      ['Donatur', $donatur, '🎁', 'bg-blue-100'],
      ['Penerima', $penerima, '👥', 'bg-purple-100'],
    ];
  @endphp

  <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
    @foreach ($kartu as [$label, $angka, $ic, $warna])
      <div class="bg-white rounded-2xl p-4 shadow-sm">
        <div class="w-10 h-10 rounded-xl {{ $warna }} flex items-center justify-center text-xl">{{ $ic }}</div>
        <div class="text-2xl font-extrabold mt-3">{{ $angka }}</div>
        <div class="text-xs text-gray-500">{{ $label }}</div>
      </div>
    @endforeach
  </div>

  <div class="bg-white rounded-2xl shadow-sm mt-6 p-5">
    <div class="flex justify-between items-center mb-3">
      <h2 class="font-extrabold">Barang menunggu verifikasi</h2>
      <a href="/admin/barang" class="text-sm text-green-800 font-semibold">Lihat semua →</a>
    </div>
    @forelse ($terbaru as $b)
      <div class="flex justify-between items-center py-3 border-t text-sm">
        <div>
          <div class="font-semibold">{{ $b->judul }}</div>
          <div class="text-xs text-gray-500">{{ $b->kategori->nama }} · 📍 {{ $b->lokasi }} · oleh {{ $b->user->name }}</div>
        </div>
        <span class="text-xs text-gray-400">{{ $b->created_at->locale('id')->diffForHumans() }}</span>
      </div>
    @empty
      <p class="text-sm text-gray-500">Tidak ada barang yang menunggu. 🎉</p>
    @endforelse
  </div>
@endsection