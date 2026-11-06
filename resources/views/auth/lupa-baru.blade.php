@extends('auth.layout')
@section('judul', 'Atur Ulang')
@section('isi')
  <h1 class="text-2xl font-bold text-green-800">{{ $tujuan === 'pin' ? 'PIN Baru' : 'Password Baru' }}</h1>
  @if ($errors->any())
    <div class="text-sm text-red-600">@foreach ($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>
  @endif
  <form method="POST" action="/lupa/baru" class="space-y-3">@csrf
    @if ($tujuan === 'pin')
      <input type="password" name="pin" inputmode="numeric" maxlength="6" placeholder="PIN baru (6 angka)" class="w-full border rounded-lg px-3 py-2 tracking-widest" required>
      <input type="password" name="pin_confirmation" inputmode="numeric" maxlength="6" placeholder="Ulangi PIN baru" class="w-full border rounded-lg px-3 py-2 tracking-widest" required>
    @else
      <input type="password" name="password" placeholder="Password baru (min. 12 karakter)" class="w-full border rounded-lg px-3 py-2" required>
    @include('partials.syarat-password')
      <input type="password" name="password_confirmation" placeholder="Ulangi password baru" class="w-full border rounded-lg px-3 py-2" required>
    @endif
    <button class="w-full bg-green-800 text-white rounded-lg py-2 font-semibold">Simpan</button>
  </form>
@endsection