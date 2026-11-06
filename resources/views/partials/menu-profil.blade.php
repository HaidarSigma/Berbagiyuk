<div class="flex items-center gap-3 text-sm whitespace-nowrap">
  @auth
    @if (auth()->user()->hasVerifiedEmail() && session('pin_ok'))
      @php $u = auth()->user(); @endphp
      <details class="relative" id="menuProfil">
        <summary class="list-none cursor-pointer flex items-center gap-2 select-none [&::-webkit-details-marker]:hidden">
          @if ($u->foto_profil)
  <img src="{{ asset('storage/'.$u->foto_profil) }}" class="w-8 h-8 rounded-full object-cover" alt="">
@else
  <span class="w-8 h-8 rounded-full bg-green-800 text-white flex items-center justify-center font-bold text-sm">{{ strtoupper(substr($u->name, 0, 1)) }}</span>
@endif
          <span class="hidden sm:block font-semibold">{{ \Illuminate\Support\Str::before($u->name, ' ') }}</span>
          <span class="text-gray-400 text-xs">▾</span>
        </summary>

        <div class="absolute right-0 mt-2 w-60 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-40">
          <div class="px-4 py-2 border-b">
            <div class="font-semibold text-sm">{{ $u->name }}</div>
            <div class="text-xs text-gray-500">{{ $u->email }}</div>
            <span class="inline-block mt-1 text-[11px] bg-green-100 text-green-800 px-2 py-0.5 rounded-full">{{ ucfirst($u->role) }}</span>
          </div>

          @if ($u->role === 'admin')
            <a href="/admin" class="block px-4 py-2 hover:bg-gray-50">🛠️ Panel Admin</a>
          @endif
          @if ($u->role === 'donatur')
            <a href="/barang-saya" class="block px-4 py-2 hover:bg-gray-50">📦 Barang Saya</a>
          @endif
          @if ($u->role === 'penerima')
            <a href="/permintaan-saya" class="block px-4 py-2 hover:bg-gray-50">📨 Permintaan Saya</a>
            <a href="/status-penerima" class="block px-4 py-2 hover:bg-gray-50">✅ Status Verifikasi</a>
          @endif

          <a href="/pengaturan" class="block px-4 py-2 hover:bg-gray-50">⚙️ Pengaturan</a>
          <form method="POST" action="/keluar" class="border-t mt-1 pt-1">@csrf
            <button class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50">🚪 Keluar</button>
          </form>
        </div>
      </details>
      <script>
        document.addEventListener('click', e => {
          const d = document.getElementById('menuProfil');
          if (d && !d.contains(e.target)) d.removeAttribute('open');
        });
      </script>
    @else
      <a href="/pin/masuk" class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-lg font-semibold">Selesaikan verifikasi</a>
      <form method="POST" action="/keluar">@csrf<button class="text-gray-500">Keluar</button></form>
    @endif
  @else
    <a href="/masuk" class="font-semibold">Masuk</a>
    <a href="/daftar" class="bg-green-800 text-white px-4 py-2 rounded-lg hover:bg-green-900 transition">Daftar</a>
  @endauth
</div>