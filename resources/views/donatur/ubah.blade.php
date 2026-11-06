<!DOCTYPE html>
<html lang="id" class="overflow-y-scroll">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ubah Barang - BerbagiYuk</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body class="bg-gray-50 p-6">
  <form method="POST" action="/barang-saya/{{ $barang->id }}/ubah" enctype="multipart/form-data" class="max-w-xl mx-auto bg-white rounded-2xl shadow-sm p-8 space-y-4">
    @csrf
    <div class="flex justify-between items-center">
      <h1 class="text-2xl font-bold text-green-800">Ubah Barang</h1>
      <a href="/barang-saya" class="text-sm text-green-800">← Barang Saya</a>
    </div>

    @if ($barang->verifikasi === 'ditolak' && $barang->alasan_tolak)
      <div class="bg-red-50 text-red-700 text-sm rounded-lg p-3">
        <b>Ditolak admin:</b> {{ $barang->alasan_tolak }}<br>
        Perbaiki bagian yang dimaksud, lalu simpan untuk mengajukan ulang.
      </div>
    @endif

    @if ($errors->any())
      <div class="text-sm text-red-600">@foreach ($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>
    @endif

    <input name="judul" value="{{ old('judul', $barang->judul) }}" placeholder="Nama barang" class="w-full border rounded-lg px-3 py-2" required>

    <div class="grid grid-cols-2 gap-3">
      <select name="kategori_id" class="border rounded-lg px-3 py-2" required>
        @foreach ($kategori as $k)
          <option value="{{ $k->id }}" @selected(old('kategori_id', $barang->kategori_id) == $k->id)>{{ $k->nama }}</option>
        @endforeach
      </select>
      <select name="kondisi" class="border rounded-lg px-3 py-2" required>
        @foreach (['Baru', 'Layak Pakai', 'Perlu Perbaikan'] as $kd)
          <option @selected(old('kondisi', $barang->kondisi) == $kd)>{{ $kd }}</option>
        @endforeach
      </select>
    </div>

    <textarea name="deskripsi" rows="3" placeholder="Deskripsi barang" class="w-full border rounded-lg px-3 py-2" required>{{ old('deskripsi', $barang->deskripsi) }}</textarea>

    <div>
      <label class="text-sm text-gray-600">Foto barang (kosongkan jika tidak ingin mengganti, maks. 2 MB)</label>
      @if ($barang->foto)
        <img src="{{ asset('storage/'.$barang->foto) }}" class="w-32 h-32 object-cover rounded-lg mt-2">
      @endif
      <input type="file" name="foto" accept="image/*" class="w-full text-sm mt-2">
    </div>

    <div>
      <label class="text-sm text-gray-600">Area (tampil di publik)</label>
      <input name="lokasi" value="{{ old('lokasi', $barang->lokasi) }}" placeholder="Contoh: Laweyan, Surakarta" class="w-full border rounded-lg px-3 py-2 mt-1" required>
    </div>

    <div>
      <p class="text-sm text-gray-600 mb-1">Klik peta untuk menandai titik pengambilan (hanya dilihat admin dan petugas)</p>
      <div id="map" class="h-64 rounded-lg z-0"></div>
      <input type="hidden" name="latitude" id="lat" value="{{ old('latitude', $barang->latitude) }}">
      <input type="hidden" name="longitude" id="lng" value="{{ old('longitude', $barang->longitude) }}">
    </div>

        <div>
      <p class="text-sm text-gray-600 mb-1">Boleh diambil oleh</p>
      <div class="grid grid-cols-2 gap-2 text-sm">
        @foreach (\App\Models\Barang::JENIS as $v => $l)
          <label class="flex items-center gap-2 border rounded-lg px-3 py-2">
            <input type="checkbox" name="syarat_penerima[]" value="{{ $v }}" @checked(in_array($v, old('syarat_penerima', $barang->syarat())))> {{ $l }}
          </label>
        @endforeach
      </div>
    </div>

    <button class="w-full bg-green-800 text-white rounded-lg py-2 font-semibold">Simpan &amp; Ajukan Ulang</button>
  </form>

  <script>
    const awal = [{{ $barang->latitude ?? -7.5755 }}, {{ $barang->longitude ?? 110.8243 }}];
    const map = L.map('map').setView(awal, 14);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {attribution: '© OpenStreetMap'}).addTo(map);
    let marker = @if ($barang->latitude) L.marker(awal).addTo(map) @else null @endif;
    map.on('click', e => {
      marker ? marker.setLatLng(e.latlng) : marker = L.marker(e.latlng).addTo(map);
      lat.value = e.latlng.lat.toFixed(7);
      lng.value = e.latlng.lng.toFixed(7);
    });
  </script>
</body>
</html>