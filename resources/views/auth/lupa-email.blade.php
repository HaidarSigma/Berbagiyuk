@extends('auth.layout')
@section('judul', 'Lupa ' . $tujuan)
@section('isi')
  <h1 class="text-2xl font-bold text-green-800">Lupa {{ $tujuan === 'pin' ? 'PIN' : 'Password' }}</h1>
  <p class="text-sm text-gray-600">Masukkan email akunmu. Kami akan mengirim kode 6 angka.</p>
  <div class="bg-yellow-50 text-yellow-800 text-xs rounded-lg p-3">
  Akun admin dan petugas tidak dapat direset di sini. Hubungi atasan atau pengelola sistem.
  </div>
  @error('email') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
  <form method="POST" action="/lupa" class="space-y-3">@csrf
    <input type="hidden" name="tujuan" value="{{ $tujuan }}">
    <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" class="w-full border rounded-lg px-3 py-2" required>
    <button class="w-full bg-green-800 text-white rounded-lg py-2 font-semibold">Kirim Kode</button>
  </form>
  <a href="/masuk" class="block text-sm text-gray-500">← Kembali ke halaman masuk</a>
@endsection