<!DOCTYPE html>
<html lang="id" class="overflow-y-scroll">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Status Verifikasi - BerbagiYuk</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
  @php
    $u = auth()->user();
    $info = [
      'menunggu'  => ['⏳', 'Sedang diperiksa', 'bg-yellow-100 text-yellow-800', 'Admin sedang memeriksa surat keteranganmu. Kamu sudah bisa melihat barang, tapi belum bisa mengajukan permintaan.'],
      'disetujui' => ['✅', 'Terverifikasi', 'bg-green-100 text-green-800', 'Akunmu sudah diverifikasi. Kamu bisa mengajukan permintaan barang.'],
      'ditolak'   => ['❌', 'Ditolak', 'bg-red-100 text-red-700', 'Verifikasi belum berhasil. Perbaiki sesuai alasan di bawah.'],
    ][$u->status_penerima] ?? ['', '-', '', ''];
  @endphp
  <div class="bg-white rounded-2xl shadow-sm p-8 w-full max-w-md space-y-4">
    <div class="flex justify-between items-center">
      <h1 class="text-2xl font-bold text-green-800">Status Verifikasi</h1>
      <a href="/" class="text-sm text-green-800">← Beranda</a>
    </div>
    <div class="text-4xl">{{ $info[0] }}</div>
    <span class="inline-block text-sm font-semibold px-3 py-1 rounded-full {{ $info[2] }}">{{ $info[1] }}</span>
    <p class="text-sm text-gray-600">{{ $info[3] }}</p>
    @if ($u->status_penerima === 'ditolak' && $u->alasan_tolak_penerima)
      <p class="text-sm text-red-600 bg-red-50 rounded-lg p-3"><b>Alasan:</b> {{ $u->alasan_tolak_penerima }}</p>
    @endif
  </div>
</body>
</html>