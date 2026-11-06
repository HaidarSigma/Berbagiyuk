@extends('layouts.situs')
@section('judul', 'Pengaturan - BerbagiYuk')
@section('isi')
  @php
    $punyaLokasi = in_array($u->role, ['donatur', 'penerima']);
    $ok = fn ($b) => session('bagian') === $b ? session('status') : null;
  @endphp

  <div class="max-w-2xl mx-auto space-y-6">
    <div>
      <h1 class="text-2xl font-extrabold">Pengaturan</h1>
      <p class="text-sm text-gray-500">{{ $u->email }} · {{ ucfirst($u->role) }}</p>
    </div>

    {{-- Foto profil --}}
<div class="bg-white rounded-2xl shadow-sm p-6 space-y-3">
  <h2 class="font-extrabold">Foto Profil</h2>
  @if ($ok('foto')) <div class="bg-green-50 text-green-800 text-sm rounded-lg p-3">{{ $ok('foto') }}</div> @endif
  @foreach ($errors->foto->all() as $e) <p class="text-sm text-red-600">{{ $e }}</p> @endforeach

  <div class="flex items-center gap-4">
    @if ($u->foto_profil)
      <img src="{{ asset('storage/'.$u->foto_profil) }}" class="w-20 h-20 rounded-full object-cover border" alt="">
    @else
      <div class="w-20 h-20 rounded-full bg-green-800 text-white flex items-center justify-center text-3xl font-bold">{{ strtoupper(substr($u->name, 0, 1)) }}</div>
    @endif

    <form method="POST" action="/pengaturan/foto" enctype="multipart/form-data" class="flex-1 space-y-2">
      @csrf
      <input type="file" name="foto" accept="image/png,image/jpeg,image/webp" class="text-sm w-full" required>
      <p class="text-xs text-gray-500">JPG, PNG, atau WEBP, maksimal 2 MB. Foto berbentuk persegi hasilnya paling rapi.</p>
      <button class="bg-green-800 text-white rounded-lg px-5 py-2 text-sm font-semibold hover:bg-green-900">Unggah Foto</button>
    </form>
  </div>

  @if ($u->foto_profil)
    <form method="POST" action="/pengaturan/foto/hapus" onsubmit="return confirm('Hapus foto profil?')">@csrf
      <button class="text-sm text-red-600">Hapus foto</button>
    </form>
  @endif
</div>

    {{-- Profil --}}
    <form method="POST" action="/pengaturan/profil" class="bg-white rounded-2xl shadow-sm p-6 space-y-3">
      @csrf
      <h2 class="font-extrabold">Profil</h2>
      @if ($ok('profil')) <div class="bg-green-50 text-green-800 text-sm rounded-lg p-3">{{ $ok('profil') }}</div> @endif
      @foreach ($errors->profil->all() as $e) <p class="text-sm text-red-600">{{ $e }}</p> @endforeach

      <input name="name" value="{{ old('name', $u->name) }}" placeholder="Nama lengkap" class="w-full border rounded-lg px-3 py-2" required>
      <input name="no_hp" value="{{ old('no_hp', $u->no_hp) }}" placeholder="No. HP" class="w-full border rounded-lg px-3 py-2" required>

      @if ($punyaLokasi)
        <textarea name="alamat" rows="2" placeholder="Alamat lengkap" class="w-full border rounded-lg px-3 py-2" required>{{ old('alamat', $u->alamat) }}</textarea>
        <div>
          <p class="text-sm text-gray-600 mb-1">Klik peta untuk memperbarui titik lokasimu</p>
          <div id="map" class="h-56 rounded-lg z-0"></div>
          <input type="hidden" name="latitude" id="lat" value="{{ old('latitude', $u->latitude) }}">
          <input type="hidden" name="longitude" id="lng" value="{{ old('longitude', $u->longitude) }}">
        </div>
      @endif

      <button class="bg-green-800 text-white rounded-lg px-5 py-2 text-sm font-semibold hover:bg-green-900">Simpan Profil</button>
    </form>

    {{-- Ganti password --}}
    <form method="POST" action="/pengaturan/password" class="bg-white rounded-2xl shadow-sm p-6 space-y-3">
      @csrf
      <h2 class="font-extrabold">Ganti Password</h2>
      @if ($ok('password')) <div class="bg-green-50 text-green-800 text-sm rounded-lg p-3">{{ $ok('password') }}</div> @endif
      @foreach ($errors->password->all() as $e) <p class="text-sm text-red-600">{{ $e }}</p> @endforeach

      <input type="password" name="password_lama" placeholder="Password lama" class="w-full border rounded-lg px-3 py-2" required>
      <input type="password" name="password" placeholder="Password baru (min. 12 karakter)" class="w-full border rounded-lg px-3 py-2" required>
      @include('partials.syarat-password')
      <input type="password" name="password_confirmation" placeholder="Ulangi password baru" class="w-full border rounded-lg px-3 py-2" required>

      <button class="bg-green-800 text-white rounded-lg px-5 py-2 text-sm font-semibold hover:bg-green-900">Ganti Password</button>
    </form>

    {{-- Ganti PIN --}}
    <form method="POST" action="/pengaturan/pin" class="bg-white rounded-2xl shadow-sm p-6 space-y-3">
      @csrf
      <h2 class="font-extrabold">Ganti PIN</h2>
      @if ($ok('pin')) <div class="bg-green-50 text-green-800 text-sm rounded-lg p-3">{{ $ok('pin') }}</div> @endif
      @foreach ($errors->pin->all() as $e) <p class="text-sm text-red-600">{{ $e }}</p> @endforeach

      <input type="password" name="pin_lama" inputmode="numeric" maxlength="6" placeholder="PIN lama" class="w-full border rounded-lg px-3 py-2 tracking-widest" required>
      <input type="password" name="pin" inputmode="numeric" maxlength="6" placeholder="PIN baru (6 angka)" class="w-full border rounded-lg px-3 py-2 tracking-widest" required>
      <input type="password" name="pin_confirmation" inputmode="numeric" maxlength="6" placeholder="Ulangi PIN baru" class="w-full border rounded-lg px-3 py-2 tracking-widest" required>

      <button class="bg-green-800 text-white rounded-lg px-5 py-2 text-sm font-semibold hover:bg-green-900">Ganti PIN</button>
    </form>
  </div>

  @if ($punyaLokasi)
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
      const awal = [{{ $u->latitude ?? -7.5755 }}, {{ $u->longitude ?? 110.8243 }}];
      const map = L.map('map').setView(awal, 14);
      L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {attribution: '© OpenStreetMap'}).addTo(map);
      let marker = @if ($u->latitude) L.marker(awal).addTo(map) @else null @endif;
      map.on('click', e => {
        marker ? marker.setLatLng(e.latlng) : marker = L.marker(e.latlng).addTo(map);
        lat.value = e.latlng.lat.toFixed(7);
        lng.value = e.latlng.lng.toFixed(7);
      });
    </script>
  @endif
@endsection