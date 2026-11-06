@extends('auth.layout')
@section('judul', 'Verifikasi Email')
@section('isi')
  <h1 class="text-2xl font-bold text-green-800">Cek emailmu</h1>
  <p class="text-sm text-gray-600">Kami mengirim tautan verifikasi ke <b>{{ auth()->user()->email }}</b>. Klik tautan itu untuk melanjutkan.</p>
  @if (session('status')) <p class="text-sm text-green-700">{{ session('status') }}</p> @endif
  <form method="POST" action="/email/kirim-ulang">@csrf
    <button class="w-full border-2 border-green-800 text-green-800 rounded-lg py-2 font-semibold">Kirim ulang email</button>
  </form>
  <form method="POST" action="/keluar">@csrf<button class="text-sm text-gray-500">Keluar</button></form>
@endsection