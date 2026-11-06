@extends('admin.layout')
@section('judul', 'Permintaan Masuk')
@section('isi')
  <div class="bg-green-50 rounded-2xl p-4 text-sm mb-5">
    <div class="font-semibold mb-1">Yang diperiksa</div>
    <ul class="list-disc ml-5 text-gray-600 space-y-0.5">
      <li>Alasan kebutuhan masuk akal dan sesuai jenis penerima</li>
      <li>Jika satu barang diminta banyak orang, pilih yang paling membutuhkan. Permintaan lain untuk barang itu otomatis ditolak</li>
    </ul>
  </div>

  <div class="space-y-4">
    @forelse ($permintaan as $p)
      @php $sama = $permintaan->where('barang_id', $p->barang_id)->count(); @endphp
      <div class="bg-white rounded-2xl shadow-sm p-4 flex flex-col lg:flex-row gap-4">
        <div class="lg:w-56 shrink-0 flex gap-3 lg:block">
          <div class="w-20 h-20 lg:w-full lg:h-32 rounded-xl bg-gray-200 overflow-hidden">
            @if ($p->barang->foto)<img src="{{ asset('storage/'.$p->barang->foto) }}" class="w-full h-full object-cover">@endif
          </div>
          <div class="lg:mt-2">
            <div class="font-bold text-sm">{{ $p->barang->judul }}</div>
            <div class="text-xs text-gray-500">{{ $p->barang->kategori->nama }} · 📍 {{ $p->barang->lokasi }}</div>
            @if ($sama > 1)<div class="text-xs text-yellow-700 mt-1">{{ $sama }} peminta untuk barang ini</div>@endif
          </div>
        </div>

        <div class="flex-1 space-y-1">
          <div class="font-extrabold">{{ $p->penerima->name }}</div>
          <span class="inline-block text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded-full">{{ \App\Models\Barang::JENIS[$p->penerima->jenis_penerima] ?? '-' }}</span>
          <div class="text-sm text-gray-500">📞 {{ $p->penerima->no_hp }}</div>
          <div class="text-sm text-gray-600">📍 {{ $p->penerima->alamat }}</div>
          <p class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3 mt-2">“{{ $p->pesan }}”</p>
          <div class="text-xs text-gray-400">diajukan {{ $p->created_at->locale('id')->diffForHumans() }}</div>
        </div>

        <div class="lg:w-64 space-y-2">
          <form method="POST" action="/admin/permintaan/{{ $p->id }}/setujui">@csrf
            <button class="w-full bg-green-700 text-white py-2 rounded-lg text-sm font-semibold hover:bg-green-800">✔ Setujui</button>
          </form>
          <form method="POST" action="/admin/permintaan/{{ $p->id }}/tolak" class="space-y-2">@csrf
            <textarea name="alasan_tolak" rows="2" required placeholder="Alasan penolakan (wajib)" class="w-full border rounded-lg px-3 py-2 text-sm"></textarea>
            <button class="w-full bg-red-600 text-white py-2 rounded-lg text-sm font-semibold hover:bg-red-700">✖ Tolak</button>
          </form>
        </div>
      </div>
    @empty
      <p class="text-gray-500">Tidak ada permintaan yang menunggu. 🎉</p>
    @endforelse
  </div>
@endsection