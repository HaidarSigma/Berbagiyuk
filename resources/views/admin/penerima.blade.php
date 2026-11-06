@extends('admin.layout')
@section('judul', 'Verifikasi Penerima')
@section('isi')
  @php
    $jenis = ['keluarga_kurang_mampu' => 'Keluarga kurang mampu', 'panti_yayasan' => 'Panti asuhan / yayasan', 'korban_bencana' => 'Korban bencana', 'pelajar' => 'Pelajar'];
  @endphp

  <div class="bg-green-50 rounded-2xl p-4 text-sm mb-5">
    <div class="font-semibold mb-1">Yang diperiksa</div>
    <ul class="list-disc ml-5 text-gray-600 space-y-0.5">
      <li>Surat keterangan jelas terbaca dan dikeluarkan RT, kelurahan, atau lembaga</li>
      <li>Nama dan alamat di surat sesuai dengan data pendaftaran</li>
      <li>Jenis penerima sesuai dengan isi surat</li>
    </ul>
  </div>

  <div class="space-y-4">
    @forelse ($penerima as $u)
      <div class="bg-white rounded-2xl shadow-sm p-4 flex flex-col lg:flex-row gap-4">
        <div class="flex-1 space-y-1">
          <div class="font-extrabold">{{ $u->name }}</div>
          <span class="inline-block text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded-full">{{ $jenis[$u->jenis_penerima] ?? '-' }}</span>
          <div class="text-sm text-gray-500">📞 {{ $u->no_hp }} · ✉️ {{ $u->email }}</div>
          <div class="text-sm text-gray-600">📍 {{ $u->alamat }}</div>
          <div class="flex gap-4 pt-1 text-sm font-semibold text-green-800">
            @if ($u->dokumen)
              <a target="_blank" href="/admin/penerima/{{ $u->id }}/dokumen">📄 Lihat surat keterangan →</a>
            @else
              <span class="text-gray-400 font-normal">Belum ada surat</span>
            @endif
            @if ($u->latitude)
              <a target="_blank" href="https://www.google.com/maps?q={{ $u->latitude }},{{ $u->longitude }}">🗺️ Lihat lokasi →</a>
            @endif
          </div>
          <div class="text-xs text-gray-400">mendaftar {{ $u->created_at->locale('id')->diffForHumans() }}</div>
        </div>

        <div class="lg:w-64 space-y-2">
          <form method="POST" action="/admin/penerima/{{ $u->id }}/setujui">@csrf
            <button class="w-full bg-green-700 text-white py-2 rounded-lg text-sm font-semibold hover:bg-green-800">✔ Setujui</button>
          </form>
          <form method="POST" action="/admin/penerima/{{ $u->id }}/tolak" class="space-y-2">@csrf
            <textarea name="alasan_tolak_penerima" rows="2" required placeholder="Alasan penolakan (wajib)" class="w-full border rounded-lg px-3 py-2 text-sm"></textarea>
            <button class="w-full bg-red-600 text-white py-2 rounded-lg text-sm font-semibold hover:bg-red-700">✖ Tolak</button>
          </form>
        </div>
      </div>
    @empty
      <p class="text-gray-500">Tidak ada penerima yang menunggu verifikasi. 🎉</p>
    @endforelse
  </div>
@endsection