@php
  $ikon  = ['👕','📚','🪑','🏠','🎒','💻','🍽️','⋯'];
  $warna = ['bg-green-100','bg-blue-100','bg-orange-100','bg-purple-100','bg-rose-100','bg-cyan-100','bg-red-100','bg-gray-100'];
@endphp
<a href="/barang/{{ $b->id }}" class="group bg-white rounded-2xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition overflow-hidden">
  <div class="h-32 relative flex items-center justify-center {{ $warna[$b->kategori_id - 1] ?? 'bg-gray-100' }}">
    @if ($b->foto)
      <img src="{{ asset('storage/'.$b->foto) }}" class="absolute inset-0 w-full h-full object-cover">
    @else
      <span class="text-5xl group-hover:scale-110 transition">{{ $ikon[$b->kategori_id - 1] ?? '📦' }}</span>
    @endif
    <span class="absolute bottom-2 left-2 bg-green-700 text-white text-[11px] font-semibold px-2 py-0.5 rounded-md">Tersedia</span>
  </div>
  <div class="p-3">
    <div class="text-sm font-bold leading-snug">{{ $b->judul }}</div>
    <span class="inline-block mt-1 text-[11px] bg-green-50 text-green-800 px-2 py-0.5 rounded">{{ $b->kondisi }}</span>
    <div class="text-xs text-gray-500 mt-2">📍 {{ $b->lokasi }}</div>
    <div class="text-xs text-gray-400 mt-0.5">{{ $b->created_at->locale('id')->diffForHumans() }}</div>
  </div>
</a>