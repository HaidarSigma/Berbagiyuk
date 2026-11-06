@extends('admin.layout')
@section('judul', 'Log Aktivitas')
@section('isi')
   @php $waspada = ['login_gagal', 'pin_salah', 'pin_terkunci', 'dokumen_dibuka', 'reset_gagal', 'reset_staf_ditolak']; @endphp

  <div class="grid sm:grid-cols-2 gap-4 mb-5">
    <div class="bg-white rounded-2xl p-4 shadow-sm">
      <div class="text-xs text-gray-500">Percobaan masuk gagal (24 jam terakhir)</div>
      <div class="text-3xl font-extrabold mt-1 {{ $gagal >= 5 ? 'text-red-600' : 'text-green-800' }}">{{ $gagal }}</div>
      @if ($gagal >= 5)<div class="text-xs text-red-600 mt-1">Cukup banyak. Periksa tabel di bawah.</div>@endif
    </div>
    <form method="GET" class="bg-white rounded-2xl p-4 shadow-sm flex items-end gap-3">
      <div class="flex-1">
        <label class="text-xs text-gray-500">Filter aksi</label>
        <select name="aksi" class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
          <option value="">Semua aksi</option>
          @foreach ($daftarAksi as $a)<option @selected(request('aksi') == $a)>{{ $a }}</option>@endforeach
        </select>
      </div>
      <button class="bg-green-800 text-white rounded-lg px-4 py-2 text-sm font-semibold">Terapkan</button>
    </form>
  </div>

  <div class="bg-white rounded-2xl shadow-sm overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="text-left text-xs text-gray-500 border-b">
        <tr><th class="p-3">Waktu</th><th class="p-3">Pengguna</th><th class="p-3">Aksi</th><th class="p-3">Keterangan</th><th class="p-3">IP</th></tr>
      </thead>
      <tbody>
        @forelse ($log as $l)
          <tr class="border-b last:border-0">
            <td class="p-3 whitespace-nowrap text-gray-500">{{ $l->created_at->format('d M Y H:i:s') }}</td>
            <td class="p-3">{{ $l->user->name ?? '-' }}</td>
            <td class="p-3"><span class="text-xs px-2 py-0.5 rounded-full {{ in_array($l->aksi, $waspada) ? 'bg-red-100 text-red-700' : 'bg-green-50 text-green-800' }}">{{ $l->aksi }}</span></td>
            <td class="p-3 text-gray-600">{{ $l->keterangan }}</td>
            <td class="p-3 text-gray-400">{{ $l->ip }}</td>
          </tr>
        @empty
          <tr><td colspan="5" class="p-4 text-gray-500">Belum ada aktivitas tercatat.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="mt-4">{{ $log->links() }}</div>
@endsection