@extends('auth.layout')
@section('judul', 'Masukkan PIN')
@section('isi')
  <h1 class="text-2xl font-bold text-green-800">Masukkan PIN</h1>
  @error('pin') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
  <form method="POST" action="/pin/masuk" class="space-y-3">@csrf
    <input type="password" name="pin" inputmode="numeric" maxlength="6" placeholder="PIN 6 angka" class="w-full border rounded-lg px-3 py-2 tracking-widest" required autofocus>
    <button class="w-full bg-green-800 text-white rounded-lg py-2 font-semibold">Lanjut</button>
  </form>
  <a href="/lupa?tujuan=pin" class="block text-sm text-green-800">Lupa PIN?</a>
  <form method="POST" action="/keluar">@csrf<button class="text-sm text-gray-500">Keluar</button></form>
@endsection