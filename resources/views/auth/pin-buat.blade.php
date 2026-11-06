@extends('auth.layout')
@section('judul', 'Buat PIN')
@section('isi')
  <h1 class="text-2xl font-bold text-green-800">Buat PIN</h1>
  <p class="text-sm text-gray-600">PIN 6 angka sebagai lapisan keamanan tambahan untuk akunmu.</p>
  @error('pin') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
  <form method="POST" action="/pin/buat" class="space-y-3">@csrf
    <input type="password" name="pin" inputmode="numeric" maxlength="6" placeholder="PIN 6 angka" class="w-full border rounded-lg px-3 py-2 tracking-widest" required>
    <input type="password" name="pin_confirmation" inputmode="numeric" maxlength="6" placeholder="Ulangi PIN" class="w-full border rounded-lg px-3 py-2 tracking-widest" required>
    <button class="w-full bg-green-800 text-white rounded-lg py-2 font-semibold">Simpan PIN</button>
  </form>
@endsection