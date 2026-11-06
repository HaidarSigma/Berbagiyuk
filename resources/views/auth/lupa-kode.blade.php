@extends('auth.layout')
@section('judul', 'Masukkan Kode')
@section('isi')
  <h1 class="text-2xl font-bold text-green-800">Masukkan Kode</h1>
  @if (session('status')) <p class="text-sm text-green-700">{{ session('status') }}</p> @endif
  @error('kode') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
  <form method="POST" action="/lupa/kode" class="space-y-3">@csrf
    <input name="kode" inputmode="numeric" maxlength="6" placeholder="Kode 6 angka" class="w-full border rounded-lg px-3 py-2 tracking-widest text-center" required autofocus>
    <button class="w-full bg-green-800 text-white rounded-lg py-2 font-semibold">Lanjut</button>
  </form>
  <p class="text-xs text-gray-500">Kode berlaku 10 menit. Belum menerima? <a href="/lupa?tujuan={{ session('reset_tujuan') }}" class="text-green-800">Minta kode baru</a></p>
@endsection