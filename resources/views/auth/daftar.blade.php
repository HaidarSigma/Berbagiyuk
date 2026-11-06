<!DOCTYPE html>
<html lang="id" class="overflow-y-scroll">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Daftar - BerbagiYuk</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
  <form method="POST" action="/daftar" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm p-8 w-full max-w-lg space-y-4">
    @csrf
    <h1 class="text-2xl font-bold text-green-800">Daftar</h1>
    @if ($errors->any())
      <div class="text-sm text-red-600">@foreach ($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>
    @endif

    <div class="flex gap-4 text-sm">
      <label><input type="radio" name="role" value="donatur" {{ old('role', 'donatur') == 'donatur' ? 'checked' : '' }}> Donatur (berbagi barang)</label>
      <label><input type="radio" name="role" value="penerima" {{ old('role') == 'penerima' ? 'checked' : '' }}> Penerima</label>
    </div>

    <input name="name" value="{{ old('name') }}" placeholder="Nama lengkap" class="w-full border rounded-lg px-3 py-2" required>
    <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" class="w-full border rounded-lg px-3 py-2" required>
    <input name="no_hp" value="{{ old('no_hp') }}" placeholder="No. HP" class="w-full border rounded-lg px-3 py-2" required>
    <input type="password" name="password" placeholder="Password (min. 12 karakter)" class="w-full border rounded-lg px-3 py-2" required>
    @include('partials.syarat-password')
    <input type="password" name="password_confirmation" placeholder="Ulangi password" class="w-full border rounded-lg px-3 py-2" required>

    {{-- Khusus penerima --}}
    <div id="boxPenerima" class="hidden space-y-3 bg-green-50 rounded-xl p-4">
      <select name="jenis_penerima" class="w-full border rounded-lg px-3 py-2 bg-white">
        <option value="">Jenis penerima</option>
        @foreach (['keluarga_kurang_mampu' => 'Keluarga kurang mampu', 'panti_yayasan' => 'Panti asuhan / yayasan', 'korban_bencana' => 'Korban bencana', 'pelajar' => 'Pelajar'] as $v => $l)
          <option value="{{ $v }}" @selected(old('jenis_penerima') == $v)>{{ $l }}</option>
        @endforeach
      </select>
      <div>
        <label class="text-sm text-gray-600">Surat keterangan dari RT, kelurahan, atau lembaga (JPG, PNG, atau PDF, maks. 2 MB)</label>
        <input type="file" name="dokumen" accept=".jpg,.jpeg,.png,.pdf" class="w-full text-sm mt-1">
        <p class="text-xs text-gray-500 mt-1">Hanya dilihat admin untuk verifikasi, tidak ditampilkan ke publik.</p>
      </div>
    </div>

    <textarea name="alamat" placeholder="Alamat lengkap" class="w-full border rounded-lg px-3 py-2" required>{{ old('alamat') }}</textarea>

    <div>
      <p class="text-sm text-gray-600 mb-1">Klik peta untuk menandai lokasimu</p>
      <div id="map" class="h-64 rounded-lg z-0"></div>
      <input type="hidden" name="latitude" id="lat" value="{{ old('latitude') }}">
      <input type="hidden" name="longitude" id="lng" value="{{ old('longitude') }}">
    </div>

    <button class="w-full bg-green-800 text-white rounded-lg py-2 font-semibold">Daftar</button>
    <p class="text-sm text-center text-gray-500">Sudah punya akun? <a href="/masuk" class="text-green-800">Masuk</a></p>
  </form>

  <script>
    const box = document.getElementById('boxPenerima');
    const cek = () => box.classList.toggle('hidden', document.querySelector('input[name=role]:checked').value !== 'penerima');
    document.querySelectorAll('input[name=role]').forEach(r => r.addEventListener('change', cek));
    cek();

    const map = L.map('map').setView([-7.5755, 110.8243], 13);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {attribution: '© OpenStreetMap'}).addTo(map);
    let marker;
    map.on('click', e => {
      marker ? marker.setLatLng(e.latlng) : marker = L.marker(e.latlng).addTo(map);
      lat.value = e.latlng.lat.toFixed(7);
      lng.value = e.latlng.lng.toFixed(7);
    });
  </script>
</body>
</html>